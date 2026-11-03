<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/datos/ComidaProductos.php';

$repositorio = new RepositorioProductos(__DIR__ . '/../datos/datos.json');

echo '<pre>';
foreach ($repositorio->obtenerTodos() as $producto) {
    echo $producto->obtenerId() . ' | '
        . $producto->obtenerNombre() . ' | '
        . $producto->obtenerPrecioFormateado() . "\n";
}
echo '</pre>';
?>