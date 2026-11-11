<?php
declare(strict_types=1);

return [
    'host' => getenv('DB_HOST') ?: '127.0.0.1',
    'puerto' => (int) (getenv('DB_PORT') ?: 3306),
    'base' => getenv('DB_NAME') ?: 'proyecto',
    'usuario' => getenv('DB_USER') ?: 'app',
    'clave' => getenv('DB_PASSWORD') ?: 'app123',
];
