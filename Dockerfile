# syntax = docker/dockerfile:1

# Vite 8 requires Node ^20.19.0 || >=22.12.0
# Use Node 22 Alpine as base, install PHP-FPM + extensions via apk
FROM node:22-alpine as base

# Install system dependencies + PHP 8.4 + extensions
# php84 packages are in Alpine main/community repos
RUN apk add --no-cache \
    # PHP 8.4 + FPM + required extensions
    php84 \
    php84-fpm \
    php84-ctype \
    php84-curl \
    php84-dom \
    php84-fileinfo \
    php84-gd \
    php84-iconv \
    php84-intl \
    php84-mbstring \
    php84-opcache \
    php84-openssl \
    php84-pdo \
    php84-pdo_mysql \
    php84-pdo_pgsql \
    php84-pdo_sqlite \
    php84-phar \
    php84-session \
    php84-simplexml \
    php84-tokenizer \
    php84-xml \
    php84-xmlreader \
    php84-zip \
    php84-bcmath \
    php84-pcntl \
    php84-sockets \
    # Build deps for any pecl extensions
    $PHPIZE_DEPS \
    linux-headers \
    icu-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    postgresql-dev \
    sqlite-dev \
    # Runtime deps
    nginx \
    supervisor \
    bash \
    git \
    curl \
    # Composer deps
    unzip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configure PHP-FPM (Alpine php84 uses /etc/php84/php-fpm.d/www.conf)
# Default is usually a unix socket; force TCP 127.0.0.1:9000 to match nginx.conf.template
RUN sed -i -E 's|^;?listen = .*|listen = 127.0.0.1:9000|' /etc/php84/php-fpm.d/www.conf \
    && mkdir -p /run/php-fpm \
    # Create symlink so start.sh's path /usr/local/etc/php-fpm.d/www.conf works
    && mkdir -p /usr/local/etc /usr/local/bin \
    && ln -s /etc/php84 /usr/local/etc/php \
    # Symlink php84 -> php so composer and artisan work
    && ln -s /usr/bin/php84 /usr/local/bin/php

# Set working directory
WORKDIR /var/www/html

# Copy composer files first for caching.
# Scripts are deferred because `artisan` does not exist yet: composer's
# post-autoload-dump hook runs `php artisan package:discover`, which exits 1
# and aborts the build when artisan is missing.
COPY composer.json composer.lock ./

RUN composer install --no-dev --prefer-dist --no-scripts --no-autoloader --no-interaction --no-progress

# Copy package files
COPY package.json package-lock.json* ./

# Install Node dependencies
RUN npm ci --legacy-peer-deps

# Copy application code
COPY . .

# Rebuild the autoloader with the application classes in place and run
# package discovery (this is the hook that needs artisan).
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --no-progress

# Build frontend assets
RUN npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Create SQLite database file
RUN touch /var/www/html/database/database.sqlite \
    && chown www-data:www-data /var/www/html/database/database.sqlite \
    && chmod 664 /var/www/html/database/database.sqlite

# Copy nginx and supervisor configs
COPY docker/nginx.conf.template /usr/local/share/nginx/nginx.conf.template
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Create startup script
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Expose port
EXPOSE 8080

# Start
CMD ["/usr/local/bin/start.sh"]