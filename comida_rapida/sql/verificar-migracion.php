<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/datos/conectar.php';

$rutaJson = __DIR__ . '/../datos/datos.json';
$contenido = file_get_contents($rutaJson);
if ($contenido === false) {
    throw new RuntimeException("No se pudo leer {$rutaJson}.");
}

$esperados = json_decode($contenido, true, 512, JSON_THROW_ON_ERROR);
$consulta = <<<'SQL'
    SELECT p.id, p.nombre, c.nombre AS categoria, p.precio, p.descripcion, p.stock, p.imagen
    FROM productos AS p
    INNER JOIN categorias AS c ON c.id = p.categoria_id
    ORDER BY p.id
    SQL;
$actuales = conectar()->query($consulta)->fetchAll();

$normalizar = static function (array $producto): array {
    return [
        'id' => (int) $producto['id'],
        'nombre' => (string) $producto['nombre'],
        'categoria' => (string) $producto['categoria'],
        'precio' => number_format((float) $producto['precio'], 2, '.', ''),
        'descripcion' => (string) $producto['descripcion'],
        'stock' => (int) $producto['stock'],
        'imagen' => (string) $producto['imagen'],
    ];
};

$esperados = array_map($normalizar, $esperados);
$actuales = array_map($normalizar, $actuales);
if ($esperados !== $actuales) {
    fwrite(STDERR, "La consulta SQL no coincide con datos/datos.json.\n");
    exit(1);
}

printf("OK: los %d productos de MariaDB coinciden con datos/datos.json.\n", count($actuales));
