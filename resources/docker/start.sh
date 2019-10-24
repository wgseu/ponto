#!/usr/bin/env bash

set -e

role=${CONTAINER_ROLE:-app}
env=${APP_ENV:-production}

cd /var/www/html

handle_term() {
    if [[ "$role" = "app" ]]; then

        echo "Stopping nginx..."
        service nginx stop

        echo "Stopping PHP-FPM..."
        kill -TERM "$fpm_pid" 2>/dev/null

    elif [[ "$role" = "sync" ]]; then

        echo "Stopping WebSockets..."
        kill -TERM "$node_pid" 2>/dev/null

    fi
}

trap 'handle_term' TERM INT

if [[ "$role" = "app" ]]; then

    echo "Caching configuration..."
    (php artisan config:cache && php artisan route:cache && php artisan view:cache)

    echo "Starting nginx..."
    service nginx start

    echo "Starting PHP-FPM..."
    php-fpm &
    fpm_pid=$!
    wait "$fpm_pid"

elif [[ "$role" = "sync" ]]; then

    echo "Running the migrations..."
    php artisan migrate --force

    echo "Running the WebSockets..."
    node resources/js/index.js &
    node_pid=$!
    wait "$node_pid"

else
    echo "Could not match the container role \"$role\""
    exit 1
fi
