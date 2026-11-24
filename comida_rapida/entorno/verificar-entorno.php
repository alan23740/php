<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/datos/conectar.php';

if (PHP_VERSION_ID < 80300) {
    fwrite(STDERR, "ERROR: se requiere PHP 8.3 o posterior; versión actual: " . PHP_VERSION . PHP_EOL);
    exit(1);
}

if (!extension_loaded('pdo_mysql')) {
    fwrite(STDERR, "ERROR: falta la extensión pdo_mysql." . PHP_EOL);
    exit(1);
}

printf("PHP %s: OK\n", PHP_VERSION);
echo "Extensión pdo_mysql: OK\n";

try {
    $pdo = conectar();
    $version = $pdo->query('SELECT VERSION()')->fetchColumn();
    $base = $pdo->query('SELECT DATABASE()')->fetchColumn();
    printf("Conexión PDO a %s: OK (MariaDB/MySQL %s)\n", $base, $version);
} catch (PDOException $error) {
    fwrite(STDERR, "ERROR: no se pudo conectar a MariaDB/MySQL: {$error->getMessage()}" . PHP_EOL);
    exit(1);
}
