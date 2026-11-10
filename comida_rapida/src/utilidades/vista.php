<?php
declare(strict_types=1);

require_once __DIR__ . '/../modelos/Producto.php';

// Todo texto que viene de los datos se escapa antes de imprimirse en HTML.
function escapar(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

function mostrarTarjetaProducto(Producto $producto, int $nivelTitulo = 2): void
{
    if ($nivelTitulo < 1 || $nivelTitulo > 6) {
        throw new InvalidArgumentException('El nivel del título debe estar entre 1 y 6.');
    }

    $titulo = 'h' . $nivelTitulo;
    $nombre = escapar($producto->obtenerNombre());
    $categoria = escapar($producto->obtenerCategoria()->getNombre());
    ?>
    <article class="product-card">
        <img class="product-thumb" src="<?= escapar($producto->obtenerImagen()) ?>" alt="Foto de <?= $nombre ?>">
        <div class="product-info">
            <<?= $titulo ?> class="product-name"><?= $nombre ?></<?= $titulo ?>>
            <p class="product-category">Categoría: <?= $categoria ?></p>
            <p><?= escapar($producto->obtenerDescripcion()) ?></p>
            <p class="product-price">Precio: <?= escapar($producto->obtenerPrecioFormateado()) ?></p>
            <?php mostrarDisponibilidad($producto); ?>
            <div class="product-detail">
                <a href="producto-detalle.php?id=<?= escapar((string) $producto->obtenerId()) ?>">Ver detalle de <?= $nombre ?></a>
            </div>
        </div>
    </article>
    <?php
}

function mostrarDisponibilidad(Producto $producto): void
{
    if ($producto->estaDisponible()) {
        echo '<p class="disponibilidad disponible">Disponible: ' . escapar((string) $producto->obtenerStock()) . ' piezas</p>';
        return;
    }

    echo '<p class="disponibilidad agotado">Agotado</p>';
}