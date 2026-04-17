#!/usr/bin/env sh
set -eu

exec php artisan queue:work \
    --queue="${QUEUE_WORKER_QUEUE:-default}" \
    --tries="${QUEUE_WORKER_TRIES:-3}" \
    --sleep="${QUEUE_WORKER_SLEEP:-1}" \
    --timeout="${QUEUE_WORKER_TIMEOUT:-90}"
