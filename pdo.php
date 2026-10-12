<?php
$host = "localhost";
$db   = "estudiantes";
$user = "usuario";
$pass = "123456";
$puerto = "3306";

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$puerto;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $consulta = $pdo->query("SELECT * FROM estudiantes_prueba");
    foreach ($consulta as $fila) {
        echo $fila["nombre"] . ": " . $fila["promedio"] . PHP_EOL;
    }
} catch (PDOException $error) {
    echo "Error de conexión: " . $error->getMessage();
}