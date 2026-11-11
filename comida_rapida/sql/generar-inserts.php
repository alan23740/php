<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/modelos/Producto.php';

$rutaJson = __DIR__ . '/../datos/datos.json';
$contenido = file_get_contents($rutaJson);
if ($contenido === false) {
    throw new RuntimeException("No se pudo leer {$rutaJson}.");
}

$productos = json_decode($contenido, true, 512, JSON_THROW_ON_ERROR);
if (!is_array($productos)) {
    throw new RuntimeException('datos.json debe contener una lista de productos.');
}

$productos = array_map(
    static fn (array $datos): Producto => Producto::desdeArray($datos),
    $productos
);

$categorias = [];
foreach ($productos as $producto) {
    $nombreCategoria = $producto->getCategoria()->getNombre();
    $categorias[$nombreCategoria] ??= count($categorias) + 1;
}

$literal = static fn (string $valor): string => 'CONVERT(0x' . bin2hex($valor) . ' USING utf8mb4)';

echo "SET NAMES utf8mb4;\n\n";
if ($categorias !== []) {
    echo "INSERT INTO categorias (id, nombre) VALUES\n";
    $filas = [];
    foreach ($categorias as $nombre => $id) {
        $filas[] = sprintf('    (%d, %s)', $id, $literal((string) $nombre));
    }
    echo implode(",\n", $filas) . ";\n\n";
}

if ($productos !== []) {
    echo "INSERT INTO productos (id, nombre, descripcion, precio, stock, categoria_id, imagen) VALUES\n";
    $filas = [];
    foreach ($productos as $producto) {
        $nombreCategoria = $producto->getCategoria()->getNombre();
        $filas[] = sprintf(
            '    (%d, %s, %s, %s, %d, %d, %s)',
            $producto->getId(),
            $literal($producto->getNombre()),
            $literal($producto->getDescripcion()),
            number_format($producto->getPrecio(), 2, '.', ''),
            $producto->getStock(),
            $categorias[$nombreCategoria],
            $literal($producto->getImagen())
        );
    }
    echo implode(",\n", $filas) . ";\n";
}
