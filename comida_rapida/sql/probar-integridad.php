<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/datos/conectar.php';

$pdo = conectar();
$pruebas = [
    [
        'caso' => 'Producto con categoría inexistente',
        'sql' => "INSERT INTO productos (id, nombre, descripcion, precio, stock, categoria_id, imagen) VALUES (9001, 'Prueba', 'Categoría inexistente', 1.00, 1, 999999, 'prueba.jpg')",
        'codigo' => 1452,
    ],
    [
        'caso' => 'Nombre de categoría duplicado',
        'sql' => "INSERT INTO categorias (id, nombre) VALUES (9001, 'Hamburguesas')",
        'codigo' => 1062,
    ],
    [
        'caso' => 'Borrar categoría con productos',
        'sql' => "DELETE FROM categorias WHERE nombre = 'Hamburguesas'",
        'codigo' => 1451,
    ],
    [
        'caso' => 'Producto sin descripción',
        'sql' => "INSERT INTO productos (id, nombre, precio, stock, categoria_id, imagen) SELECT 9001, 'Prueba', 1.00, 1, id, 'prueba.jpg' FROM categorias WHERE nombre = 'Hamburguesas'",
        'codigo' => 1364,
    ],
    [
        'caso' => 'Consulta a tabla mal escrita',
        'sql' => 'SELECT * FROM productoss',
        'codigo' => 1146,
    ],
];

foreach ($pruebas as $indice => $prueba) {
    $numero = $indice + 1;
    $pdo->beginTransaction();

    try {
        $pdo->exec($prueba['sql']);
        $pdo->rollBack();
        throw new RuntimeException("El caso {$numero} no produjo el error esperado.");
    } catch (PDOException $error) {
        $informacion = $error->errorInfo;
        $codigo = (int) ($informacion[1] ?? 0);

        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        if ($codigo !== $prueba['codigo']) {
            throw new RuntimeException(
                "El caso {$numero} esperaba el código {$prueba['codigo']} y recibió {$codigo}: "
                . $error->getMessage()
            );
        }

        printf(
            "%d. %s — SQLSTATE %s, error MariaDB %d: %s (ROLLBACK aplicado).%s",
            $numero,
            $prueba['caso'],
            $informacion[0] ?? (string) $error->getCode(),
            $codigo,
            $informacion[2] ?? $error->getMessage(),
            PHP_EOL
        );
    }
}

echo "OK: las cinco pruebas produjeron el error esperado y no dejaron cambios.\n";
