<?php
$productos = json_decode(file_get_contents(__DIR__ . '/../datos/datos.json'), true) ?? [];
$destacados = array_slice($productos, 0, 3);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buen Bocado - Comida rápida</title>
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
        <section class="content-card hero">
            <h1 class="catalog-title">Comida rica, sin complicaciones</h1>
            <p>Hamburguesas, acompañamientos y bebidas preparados al momento.</p>
            <a class="button-link" href="productos.php">Ver el menú</a>
        </section>
        <section aria-labelledby="featured-title">
            <h2 id="featured-title" class="catalog-title">Favoritos del menú</h2>
            <div class="featured-grid">
            <?php foreach ($destacados as $producto): ?>
                <article class="content-card featured-card">
                    <h3><?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8') ?></h3>
                    <p><?= htmlspecialchars($producto['descripcion'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p class="product-price">Precio: $<?= number_format((float) $producto['precio'], 2) ?></p>
                    <a class="text-link" href="producto-detalle.php?id=<?= (int) $producto['id'] ?>">Ver detalle</a>
                </article>
            <?php endforeach; ?>
            </div>
        </section>
    </main>
    <footer class="site-footer">
        <p>&copy; 2026 Buen Bocado</p>
    </footer>
</body>
</html>