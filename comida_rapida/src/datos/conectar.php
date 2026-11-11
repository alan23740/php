<?php
declare(strict_types=1);

function conectar(): PDO
{
    $rutaConfiguracion = __DIR__ . '/../config/conexion.php';
    if (!is_file($rutaConfiguracion)) {
        throw new RuntimeException(
            'Falta src/config/conexion.php; créalo a partir de conexion.ejemplo.php.'
        );
    }

    $configuracion = require $rutaConfiguracion;
    if (!is_array($configuracion)) {
        throw new RuntimeException('La configuración de conexión debe devolver un arreglo.');
    }

    foreach (['host', 'puerto', 'base', 'usuario', 'clave'] as $campo) {
        if (!array_key_exists($campo, $configuracion)) {
            throw new RuntimeException("Falta el valor de conexión: {$campo}.");
        }
    }

    $dsn = sprintf(
        'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
        $configuracion['host'],
        $configuracion['puerto'],
        $configuracion['base']
    );

    return new PDO($dsn, $configuracion['usuario'], $configuracion['clave'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
}
