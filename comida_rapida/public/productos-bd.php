<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/datos/conectar.php';
require_once __DIR__ . '/../src/utilidades/vista.php';

$consulta = <<<'SQL'
    SELECT p.id, p.nombre, c.nombre AS categoria, p.precio, p.descripcion, p.stock, p.imagen
    FROM productos AS p
    INNER JOIN categorias AS c ON c.id = p.categoria_id
    ORDER BY p.id
    SQL;
$registros = conectar()->query($consulta)->fetchAll();
$productos = array_map(
    static fn (array $registro): Producto => Producto::desdeArray($registro),
    $registros
);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Menú desde MariaDB - Buen Bocado</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>
    <header class="site-header">
        <div class="inner">
            <div class="brand">Productos desde MariaDB</div>
            <nav class="main-nav" aria-label="Navegación principal">
                <a href="index.php">Inicio</a>
                <a href="productos.php">Menú JSON</a>
                <a href="contacto.php">Contacto</a>
            </nav>
        </div>
    </header>

    <main class="page-shell">
        <h1 class="catalog-title">Catálogo desde la base de datos</h1>

        <section class="catalog-grid" aria-label="Productos almacenados en MariaDB">
            <?php if ($productos === []): ?>
                <p>La base de datos todavía no contiene productos.</p>
            <?php else: ?>
                <?php foreach ($productos as $producto): ?>
                    <?php mostrarTarjetaProducto($producto, 2); ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>

    <footer class="site-footer">
        <p>&copy; 2026 Buen Bocado</p>
    </footer>
</body>
</html>
