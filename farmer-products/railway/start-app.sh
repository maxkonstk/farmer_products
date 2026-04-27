#!/usr/bin/env sh
set -eu

APP_ROOT="${APP_ROOT:-/var/www/html}"
PORT_VALUE="${PORT:-8080}"

. "$(dirname "$0")/prepare-runtime.sh"

exec php artisan serve --host=0.0.0.0 --port="${PORT_VALUE}"
