#!/usr/bin/env sh
set -eu

. "$(dirname "$0")/prepare-runtime.sh"

exec php artisan schedule:work
