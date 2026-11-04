<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/datos/ComidaProductos.php';
require_once __DIR__ . '/../src/utilidades/vista.php';

$repositorio = new ComidaProductos(__DIR__ . '/../datos/datos.json');
$productos = $repositorio->obtenerTodos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Menú - Buen Bocado</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>
    <header class="site-header">
        <div class="inner">
            <div class="brand">Productos</div>
            <nav class="main-nav" aria-label="Navegación principal">
                <a href="index.php">Inicio</a>
                <a href="productos.php">Menú</a>
                <a href="contacto.php">Contacto</a>
            </nav>
        </div>
    </header>

    <main class="page-shell">
        <h2 class="catalog-title">Catálogo</h2>

        <div class="catalog-tools">
            <a href="agregar-producto.php">Agregar producto</a>
        </div>

        <section class="catalog-grid" aria-label="Lista de productos">
            <?php if ($productos === []): ?>
                <p>Por ahora no hay productos en el menú.</p>
            <?php else: ?>
                <?php foreach ($productos as $producto): ?>
                    <?php mostrarTarjetaProducto($producto, 3); ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>

    <footer class="site-footer">
        <p>&copy; 2026 Buen Bocado</p>
    </footer>
</body>
</html>