# ==============================================================================
# Stage 1: Vendor Builder (Composer Dependencies)
# ==============================================================================
FROM composer:2.8 AS vendor

WORKDIR /app

# Copy Composer manifests
COPY composer.json composer.lock ./

# Install production dependencies only (no dev packages, optimized autoloader)
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts \
    --ignore-platform-reqs

# ==============================================================================
# Stage 2: Production Application Runtime (PHP 8.3 FPM + Nginx + Supervisord)
# ==============================================================================
FROM php:8.3-fpm-alpine AS production

LABEL maintainer="Alexandre Vance <contact@alexandrevance.dev>"
LABEL description="Production-ready Laravel 11 REST API for Interactive 3D Portfolio"

# Environment defaults
ENV APP_ENV=production \
    APP_DEBUG=false \
    PORT=80

WORKDIR /var/www/html

# Install system dependencies, Nginx, Supervisord, and build tools for PHP extensions
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    git \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-dev \
    oniguruma-dev \
    shadow

# Configure and compile PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_pgsql \
        pgsql \
        bcmath \
        zip \
        intl \
        gd \
        exif \
        opcache

# Copy custom PHP configuration
COPY docker/php.ini $PHP_INI_DIR/conf.d/99-custom.ini

# Copy Nginx server configuration
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# Copy Supervisord configuration
COPY docker/supervisord.conf /etc/supervisord.conf

# Copy vendor dependencies from Stage 1
COPY --from=vendor /app/vendor /var/www/html/vendor

# Copy entire application codebase
COPY . /var/www/html

# Prepare storage, logs, and cache directories
RUN mkdir -p /var/www/html/storage/framework/cache/data \
             /var/www/html/storage/framework/sessions \
             /var/www/html/storage/framework/views \
             /var/www/html/storage/logs \
             /var/www/html/storage/app/public \
             /var/www/html/bootstrap/cache \
             /var/run/nginx \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copy entrypoint script, strip any Windows CRLF endings, and set executable permissions
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh \
    && chmod +x /usr/local/bin/entrypoint.sh

# Expose HTTP port (Render / Cloud dynamic $PORT)
EXPOSE 80 10000

# Container Startup:
# 1. Entrypoint automatically runs database migrations: `php artisan migrate --force`
# 2. CMD starts Supervisord to smoothly run Nginx and PHP-FPM in production
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]

