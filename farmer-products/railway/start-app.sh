#!/usr/bin/env sh
set -eu

APP_ROOT="${APP_ROOT:-/var/www/html}"
PORT_VALUE="${PORT:-8080}"

. "$(dirname "$0")/prepare-runtime.sh"

sed "s/__PORT__/${PORT_VALUE}/g" \
    /etc/apache2/sites-available/000-default.conf.template \
    > /etc/apache2/sites-available/000-default.conf

printf 'Listen %s\n' "$PORT_VALUE" > /etc/apache2/ports.conf

exec apache2-foreground
