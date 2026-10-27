<?php
declare(strict_types=1);
require_once __DIR__ . "/Categoria.php";
$perifericos = new Categoria("Periféricos");
$audio = new Categoria("Audio");
echo $perifericos->getNombre() . PHP_EOL;
echo $audio->getNombre() . PHP_EOL;
$p = new Producto(1, "Teclado mecánico", $perifericos, 59.99, "Teclado mecánico con retroiluminación RGB", 10, "teclado.jpg");
echo $p->getNombre() . PHP_EOL;
$mouse = Producto::desdeArray([
    "id" => 2,
    "nombre" => "Mouse inalámbrico",
    "categoria" => $perifericos->getNombre(),
    "precio" => 29.99,
    "descripcion" => "Mouse inalámbrico con sensor óptico",
    "stock" => 15,
    "imagen" => "mouse.jpg",
]);
echo $mouse->getNombre() . '('. $mouse->getPrecioFormateado() . ')' . PHP_EOL;
echo $mouse->estaDisponible() ? "Disponible" : "Agotado" . PHP_EOL;

?>