<?php
require_once __DIR__ . '/../src/modelos/Producto.php';

$archivoDatos = __DIR__ . '/../datos/datos.json';
$datosProductos = json_decode(file_get_contents($archivoDatos), true, 512, JSON_THROW_ON_ERROR);
$productos = array_map(static fn (array $datos): Producto => Producto::desdeArray($datos), $datosProductos);
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
                    <article class="product-card">
                        <div class="product-thumb">200 × 150</div>
                        <div class="product-info">
                            <h3 class="product-name"><?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?></h3>
                            <p class="product-category">Categoría: <?= htmlspecialchars($producto->getCategoria()->getNombre(), ENT_QUOTES, 'UTF-8') ?></p>
                            <p><?= htmlspecialchars($producto->getDescripcion(), ENT_QUOTES, 'UTF-8') ?></p>
                            <p class="product-price">Precio: $<?= number_format($producto->getPrecio(), 2) ?></p>
                            <p>Disponibles: <?= $producto->getStock() ?></p>
                            <div class="product-detail">
                                <a href="producto-detalle.php?id=<?= $producto->getId() ?>">Ver detalle</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>

    <footer class="site-footer">
        <p>&copy; 2026 Buen Bocado</p>
    </footer>
</body>
</html>