<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos | Tienda Tec</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="site-header">
        <div class="inner">
            <div class="brand">Productos</div>
            <nav class="main-nav" aria-label="Navegación principal">
                <a href="index.php">Inicio</a>
                <a href="productos.php">Catálogo</a>
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
            <article class="product-card">
                <div class="product-thumb">200 × 150</div>
                <div class="product-info">
                    <h3 class="product-name">Mouse Inalámbrico</h3>
                    <p class="product-category">Categoría: Periféricos</p>
                    <p class="product-price">Precio: $299.00</p>
                    <div class="product-detail">
                        <a href="producto-detalle.php?id=1">Ver detalle</a>
                    </div>
                </div>
            </article>

            <article class="product-card">
                <div class="product-thumb">200 × 150</div>
                <div class="product-info">
                    <h3 class="product-name">Teclado Mecánico</h3>
                    <p class="product-category">Categoría: Periféricos</p>
                    <p class="product-price">Precio: $899.00</p>
                    <div class="product-detail">
                        <a href="producto-detalle.php?id=2">Ver detalle</a>
                    </div>
                </div>
            </article>
        </section>
    </main>

    <footer class="site-footer">
        <p>&copy; 2026 Tienda Tec. Todos los derechos reservados.</p>
    </footer>
</body>
</html>