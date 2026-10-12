
<?php
header("Content-Type: application/json; charset=utf-8");

$estudiantes = [
    ["id" => 1, "nombre" => "Ana Torres", "promedio" => 91.5],
    ["id" => 2, "nombre" => "Luis Pérez", "promedio" => 78.2]
];

echo json_encode([
    "status" => "ok",
    "data" => $estudiantes
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);