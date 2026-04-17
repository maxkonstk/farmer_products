#!/usr/bin/env sh
set -eu

APP_ROOT="${APP_ROOT:-/var/www/html}"

cd "$APP_ROOT"

mkdir -p \
    storage/app/public \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/testing \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache

php artisan storage:link --force >/dev/null 2>&1 || true
