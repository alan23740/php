<?php
$productos = json_decode(file_get_contents(__DIR__ . '/../datos/datos.json'), true) ?? [];
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$producto = null;

foreach ($productos as $item) {
    if ($id !== false && $id !== null && (int) $item['id'] === $id) {
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
    <link rel="stylesheet" href="styles.css">
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
                <h1 class="catalog-title"><?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8') ?></h1>
                <p>Categoría: <?= htmlspecialchars($producto['categoria'], ENT_QUOTES, 'UTF-8') ?></p>
                <p class="product-price">Precio: $<?= number_format((float) $producto['precio'], 2) ?></p>
                <p><?= htmlspecialchars($producto['descripcion'], ENT_QUOTES, 'UTF-8') ?></p>
                <p>Disponibles: <?= (int) $producto['stock'] ?></p>
                <a class="text-link" href="productos.php">Volver al menú</a>
            </article>
        <?php endif; ?>
    </main>
    <footer class="site-footer">
        <p>&copy; 2026 Buen Bocado</p>
    </footer>
</body>
</html>