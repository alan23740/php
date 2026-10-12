<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio = filter_input(INPUT_POST, 'precio', FILTER_VALIDATE_FLOAT);
    $stock = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT);

    if ($nombre === '' || $categoria === '' || $descripcion === '' || $precio === false || $precio === null || $precio < 0 || $stock === false || $stock === null || $stock < 0) {
        http_response_code(400);
        exit('Revisa los datos del producto e inténtalo de nuevo.');
    }

    $archivoDatos = __DIR__ . '/../datos/datos.json';
    $productos = json_decode(file_get_contents($archivoDatos), true) ?? [];
    $siguienteId = 1;

    foreach ($productos as $producto) {
        $siguienteId = max($siguienteId, (int) $producto['id'] + 1);
    }

    $productos[] = [
        'id' => $siguienteId,
        'nombre' => $nombre,
        'categoria' => $categoria,
        'precio' => $precio,
        'descripcion' => $descripcion,
        'stock' => $stock,
    ];

    $json = json_encode($productos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if ($json === false || file_put_contents($archivoDatos, $json . PHP_EOL, LOCK_EX) === false) {
        http_response_code(500);
        exit('No se pudo guardar el producto.');
    }

    header('Location: productos.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agregar producto - Buen Bocado</title>
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
        <h1 class="catalog-title">Agregar producto</h1>
        <section class="content-card form-card">
            <form class="product-form" method="post" action="agregar-producto.php">
                <div class="form-field">
                    <label for="nombre">Nombre</label>
                    <input id="nombre" name="nombre" type="text" required>
                </div>
                <div class="form-field">
                    <label for="categoria">Categoría</label>
                    <input id="categoria" name="categoria" type="text" required>
                </div>
                <div class="form-field">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" required></textarea>
                </div>
                <div class="form-field">
                    <label for="precio">Precio</label>
                    <input id="precio" name="precio" type="number" min="0" step="0.01" required>
                </div>
                <div class="form-field">
                    <label for="stock">Existencias</label>
                    <input id="stock" name="stock" type="number" min="0" step="1" required>
                </div>
                <button type="submit">Guardar producto</button>
            </form>
        </section>
    </main>
    <footer class="site-footer">
        <p>&copy; 2026 Buen Bocado</p>
    </footer>
</body>
</html>