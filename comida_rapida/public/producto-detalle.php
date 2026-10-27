<?php
require_once __DIR__ . '/../src/modelos/Producto.php';

$datosProductos = json_decode(file_get_contents(__DIR__ . '/../datos/datos.json'), true, 512, JSON_THROW_ON_ERROR);
$productos = array_map(static fn (array $datos): Producto => Producto::desdeArray($datos), $datosProductos);
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$producto = null;

foreach ($productos as $item) {
    if ($id !== false && $id !== null && $item->getId() === $id) {
        $producto = $item;
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalle del producto - Buen Bocado</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>
    <header class="site-header">
        <div class="inner">
            <div class="brand">Buen Bocado</div>
            <nav class="main-nav" aria-label="Navegación principal">
                <a href="index.php">Inicio</a>
                <a href="productos.php">Menú</a>
                <a href="contacto.php">Contacto</a>
            </nav>
        </div>
    </header>
    <main class="page-shell">
        <?php if ($producto === null): ?>
            <h1 class="catalog-title">Producto no encontrado</h1>
            <p><a class="text-link" href="productos.php">Volver al menú</a></p>
        <?php else: ?>
            <article class="content-card detail-card">
                <h1 class="catalog-title"><?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?></h1>
                <p>Categoría: <?= htmlspecialchars($producto->getCategoria()->getNombre(), ENT_QUOTES, 'UTF-8') ?></p>
                <p class="product-price">Precio: $<?= number_format($producto->getPrecio(), 2) ?></p>
                <p><?= htmlspecialchars($producto->getDescripcion(), ENT_QUOTES, 'UTF-8') ?></p>
                <p>Disponibles: <?= $producto->getStock() ?></p>
                <a class="text-link" href="productos.php">Volver al menú</a>
            </article>
        <?php endif; ?>
    </main>
    <footer class="site-footer">
        <p>&copy; 2026 Buen Bocado</p>
    </footer>
</body>
</html>