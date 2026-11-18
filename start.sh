#!/bin/bash
set -e

echo "=== Running migrations ==="
php artisan migrate --force

echo "=== Running seeders ==="
php artisan db:seed --force

echo "=== Linking storage ==="
php artisan storage:link || true

echo "=== Clearing and caching config ==="
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== Starting server ==="
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}

