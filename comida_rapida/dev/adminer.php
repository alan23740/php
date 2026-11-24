<?php
declare(strict_types=1);

$adminer = '/usr/share/adminer/adminer.php';
if (!is_file($adminer)) {
    throw new RuntimeException('Instala Adminer con .devcontainer/instalar-entorno.sh.');
}

require $adminer;
