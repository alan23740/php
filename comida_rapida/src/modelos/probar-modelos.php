<?php
declare(strict_types=1);
require_once __DIR__ . "/Categoria.php";
require_once __DIR__ . "/Producto.php";
$perifericos = new Categoria("Periféricos");
$audio = new Categoria("Audio");
echo $perifericos->getNombre() . PHP_EOL;
echo $audio->getNombre() . PHP_EOL;
$p = Producto::desdeArray([
    "id" => 1,
    "nombre" => "Teclado mecánico",
    "categoria" => $perifericos->getNombre(),
    "precio" => 59.99,
    "descripcion" => "Teclado mecánico con retroiluminación RGB",
    "stock" => 10,
    "imagen" => "teclado.jpg",
]);
echo $p->getNombre() . PHP_EOL;
?>