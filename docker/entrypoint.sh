#!/bin/sh
set -e

echo "=========================================================="
echo " Starting Laravel 11 Production Runtime on Render.com"
echo "=========================================================="

# 1. Dynamic Render $PORT binding
PORT="${PORT:-80}"
echo "==> Binding Nginx to target Render port: ${PORT}"

TARGET_NGINX_CONF="/etc/nginx/http.d/default.conf"
if [ ! -f "$TARGET_NGINX_CONF" ]; then
    TARGET_NGINX_CONF="/etc/nginx/conf.d/default.conf"
fi

if [ -f "$TARGET_NGINX_CONF" ]; then
    sed -i "s/PORT_PLACEHOLDER/${PORT}/g" "$TARGET_NGINX_CONF"
else
    echo "Warning: Nginx configuration file not found at ${TARGET_NGINX_CONF}."
fi

# 2. Storage and cache directory permissions
echo "==> Preparing storage directories & permissions..."
mkdir -p /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/logs \
         /var/www/html/storage/app/public \
         /var/www/html/bootstrap/cache

chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 3. Create Storage Symlink
echo "==> Creating storage symlink..."
php artisan storage:link --force || true

# 4. Execute Database Migrations Safely
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    echo "==> Checking database connection and running pending migrations..."
    MAX_TRIES=10
    COUNT=0
    MIGRATED=0

    while [ $COUNT -lt $MAX_TRIES ]; do
        COUNT=$((COUNT + 1))
        echo "--> Attempting migration run ($COUNT/$MAX_TRIES)..."
        if php artisan migrate --force; then
            echo "--> Database migrations completed successfully."
            MIGRATED=1
            break
        fi
        echo "--> Database connection unavailable yet. Retrying in 3 seconds..."
        sleep 3
    done

    if [ $MIGRATED -eq 0 ]; then
        echo "ERROR: Failed to run database migrations after $MAX_TRIES attempts."
        # If strict database check is not disabled, fail fast
        if [ "${FAIL_ON_MIGRATION_ERROR:-false}" = "true" ]; then
            exit 1
        fi
    fi
fi

# 5. Optional Auto-Seeder on First Boot
if [ "${RUN_SEEDER:-false}" = "true" ]; then
    echo "==> Seeding database..."
    php artisan db:seed --force || echo "Warning: Seed command encountered non-fatal error."
fi

# 6. Framework Caching for Maximum Performance
if [ "${APP_ENV:-production}" = "production" ]; then
    echo "==> Caching Laravel configuration, routes, and views..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

echo "==> Application initialized successfully. Starting Supervisord..."
exec "$@"

