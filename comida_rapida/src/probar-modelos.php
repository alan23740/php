<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/datos/ComidaProductos.php';

$repositorio = new RepositorioProductos(__DIR__ . '/../datos/datos.json');

echo '<pre>';
echo 'Registros leidos: ' . count($repositorio->obtenerTodos()) . "\n";
echo 'Primer nombre: ' . $repositorio->obtenerTodos()[0]['nombre'] . "\n";
echo '</pre>';
?>