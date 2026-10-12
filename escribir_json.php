<?php
$contenido = file_get_contents("datos.json");
$datos = json_decode($contenido, true);

$datos["estudiantes"][] = [
    "id" => 3,
    "nombre" => "Nuevo Estudiante",
    "promedio" => 88.0
];

file_put_contents("datos.json", json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Registro agregado correctamente.";