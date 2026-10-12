<?php
$contenido = file_get_contents("datos.json");
$datos = json_decode($contenido, true);

foreach ($datos["estudiantes"] as $estudiante) {
    echo $estudiante["nombre"] . ": " . $estudiante["promedio"] . PHP_EOL;
}