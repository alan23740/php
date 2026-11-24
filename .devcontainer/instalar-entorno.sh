#!/usr/bin/env bash
set -euo pipefail

sudo apt-get update
sudo env DEBIAN_FRONTEND=noninteractive apt-get install -y \
    php-cli \
    php-mysql \
    mariadb-server \
    mariadb-client \
    adminer \
    graphviz

php -r '
if (!extension_loaded("pdo_mysql")) {
    fwrite(STDERR, "Falta la extensión pdo_mysql.\n");
    exit(1);
}
echo "PHP y pdo_mysql están listos.\n";
'
