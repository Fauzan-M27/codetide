#!/bin/bash
set -e

echo "=== Running migrations ==="
php artisan migrate:fresh --force --seed

echo "=== Linking storage ==="
php artisan storage:link || true

echo "=== Clearing caches ==="
php artisan optimize:clear

echo "=== Caching for production ==="
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== Checking Vite manifest ==="
if [ ! -f public/build/manifest.json ]; then
    echo "WARNING: Vite manifest not found! Assets may not load correctly."
else
    echo "Vite manifest found: OK"
fi

echo "=== Starting server ==="
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
