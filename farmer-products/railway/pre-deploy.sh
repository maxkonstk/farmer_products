#!/usr/bin/env sh
set -eu

APP_ROOT="${APP_ROOT:-/var/www/html}"
ATTEMPTS="${MIGRATION_ATTEMPTS:-30}"
DELAY_SECONDS="${MIGRATION_DELAY_SECONDS:-3}"
SEED_DEMO_DATA="${SEED_DEMO_DATA:-false}"

cd "$APP_ROOT"

if [ -z "${APP_KEY:-}" ]; then
    echo "APP_KEY is required. Generate it with: php artisan key:generate --show" >&2
    exit 1
fi

if [ -z "${DB_URL:-${DATABASE_URL:-}}" ] && [ -z "${DB_HOST:-}" ]; then
    echo "Database connection is not configured. Set DATABASE_URL/DB_URL or DB_HOST/DB_PORT/DB_DATABASE/DB_USERNAME/DB_PASSWORD." >&2
    exit 1
fi

i=1
while [ "$i" -le "$ATTEMPTS" ]; do
    if php artisan migrate --force; then
        if [ "$SEED_DEMO_DATA" = "true" ]; then
            php artisan db:seed --force
        fi

        exit 0
    fi

    echo "Migration attempt ${i}/${ATTEMPTS} failed, retrying in ${DELAY_SECONDS}s..." >&2
    i=$((i + 1))
    sleep "$DELAY_SECONDS"
done

echo "Migrations failed after ${ATTEMPTS} attempts." >&2
exit 1
