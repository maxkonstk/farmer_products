#!/usr/bin/env sh
set -eu

# Railway runs pre-deploy in a separate container without mounted volumes.
# Keep only database mutations here.
php artisan migrate --force
