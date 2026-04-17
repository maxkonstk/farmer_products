#!/usr/bin/env sh
set -eu

APP_ROOT="${APP_ROOT:-/var/www/html}"
ATTEMPTS="${MIGRATION_ATTEMPTS:-20}"
DELAY_SECONDS="${MIGRATION_DELAY_SECONDS:-3}"

cd "$APP_ROOT"

i=1
while [ "$i" -le "$ATTEMPTS" ]; do
    if php artisan migrate --force; then
        exit 0
    fi

    echo "Migration attempt ${i}/${ATTEMPTS} failed, retrying in ${DELAY_SECONDS}s..." >&2
    i=$((i + 1))
    sleep "$DELAY_SECONDS"
done

echo "Migrations failed after ${ATTEMPTS} attempts." >&2
exit 1
