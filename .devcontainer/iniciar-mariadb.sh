#!/usr/bin/env bash
set -euo pipefail

raiz_repositorio="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
configuracion="$raiz_repositorio/comida_rapida/src/config/conexion.php"
plantilla="$raiz_repositorio/comida_rapida/src/config/conexion.ejemplo.php"
if [[ ! -f "$configuracion" ]]; then
    cp "$plantilla" "$configuracion"
fi

sudo service mariadb start
sudo mariadb <<'SQL'
CREATE DATABASE IF NOT EXISTS proyecto
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'app'@'127.0.0.1' IDENTIFIED BY 'app123';
CREATE USER IF NOT EXISTS 'app'@'localhost' IDENTIFIED BY 'app123';
ALTER USER 'app'@'127.0.0.1' IDENTIFIED BY 'app123';
ALTER USER 'app'@'localhost' IDENTIFIED BY 'app123';
GRANT ALL PRIVILEGES ON proyecto.* TO 'app'@'127.0.0.1';
GRANT ALL PRIVILEGES ON proyecto.* TO 'app'@'localhost';
FLUSH PRIVILEGES;
SQL

echo "[OK] MariaDB está lista; base de desarrollo: proyecto."
