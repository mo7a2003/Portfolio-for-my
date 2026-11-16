#!/bin/sh
set -e

echo "=========================================================="
echo " Starting Laravel 11 Production Runtime"
echo "=========================================================="

# 1. Dynamic Port Binding for Render / Railway / Cloud Hosts
PORT="${PORT:-80}"
echo "==> Configuring port binding: ${PORT}"

TARGET_NGINX_CONF="/etc/nginx/http.d/default.conf"
if [ ! -f "$TARGET_NGINX_CONF" ]; then
    TARGET_NGINX_CONF="/etc/nginx/conf.d/default.conf"
fi

if [ -f "$TARGET_NGINX_CONF" ]; then
    sed -i "s/PORT_PLACEHOLDER/${PORT}/g" "$TARGET_NGINX_CONF"
else
    echo "Notice: Default Nginx configuration file not found at ${TARGET_NGINX_CONF}."
fi

# 2. Storage, Cache, and SQLite Database Directory Permissions
echo "==> Setting up storage and database directory permissions..."
mkdir -p /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/logs \
         /var/www/html/storage/app/public \
         /var/www/html/bootstrap/cache \
         /var/www/html/database

chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# If SQLite is used or present, ensure full write permissions for www-data
if [ ! -f /var/www/html/database/database.sqlite ]; then
    touch /var/www/html/database/database.sqlite
fi
chmod -R 777 /var/www/html/database
chown -R www-data:www-data /var/www/html/database

# 3. Ensure Storage Public Symlink
echo "==> Ensuring public storage symlink exists..."
php artisan storage:link --force || true

# 4. Check for APP_KEY (Generate if missing)
if [ -z "$APP_KEY" ]; then
    echo "==> APP_KEY not detected, generating application encryption key..."
    php artisan key:generate --force || true
fi

# 5. Automatically Run Database Migrations
echo "==> Automatically running database migrations: php artisan migrate --force"
MAX_TRIES=10
COUNT=0
MIGRATED=0

while [ $COUNT -lt $MAX_TRIES ]; do
    COUNT=$((COUNT + 1))
    echo "--> Running php artisan migrate --force (Attempt $COUNT/$MAX_TRIES)..."
    if php artisan migrate --force; then
        echo "--> Database migrations completed successfully."
        MIGRATED=1
        break
    fi
    echo "--> Database connection unavailable or initializing. Retrying in 3 seconds..."
    sleep 3
done

if [ $MIGRATED -eq 0 ]; then
    echo "WARNING: Migrations could not be applied automatically after $MAX_TRIES attempts."
    if [ "${FAIL_ON_MIGRATION_ERROR:-false}" = "true" ]; then
        exit 1
    fi
fi

# 6. Re-apply Permissions on Database (prevents 'readonly database' in SQLite)
chown -R www-data:www-data /var/www/html/database
chmod -R 777 /var/www/html/database

# 7. Optional Database Seeder (if RUN_SEEDER=true)
if [ "${RUN_SEEDER:-false}" = "true" ]; then
    echo "==> Seeding database (RUN_SEEDER=true)..."
    php artisan db:seed --force || echo "Warning: Seed command encountered non-fatal error."
    chown -R www-data:www-data /var/www/html/database
    chmod -R 777 /var/www/html/database
fi

# 8. Framework Caching for Production Optimization
if [ "${APP_ENV:-production}" = "production" ]; then
    echo "==> Caching Laravel configuration, routes, and views..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

# Ensure absolute ownership and permissions for storage and bootstrap cache before web server starts
echo "==> Finalizing storage and cache permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 9. Smooth Startup: Launch Supervisord (Nginx + PHP-FPM) or User CMD
echo "==> Application initialized successfully. Starting Web Server..."
if [ "$#" -eq 0 ]; then
    exec /usr/bin/supervisord -c /etc/supervisord.conf
else
    exec "$@"
fi
