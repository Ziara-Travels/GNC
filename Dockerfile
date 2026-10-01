# syntax = docker/dockerfile:1

# Vite 8 requires Node ^20.19.0 || >=22.12.0
ARG NODE_VERSION=22.14.0
FROM node:${NODE_VERSION}-slim as node_base

# Must satisfy composer.json ("php": "^8.4")
    FROM php:8.4-fpm-alpine as php_base

# Install system dependencies
RUN apk add --no-cache \
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
    nginx \
    supervisor \
    bash \
    git \
    curl

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    pdo_sqlite \
    zip \
    gd \
    intl \
    opcache \
    bcmath \
    mbstring \
    pcntl \
    sockets

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first for caching.
# Scripts are deferred because `artisan` does not exist yet: composer's
# post-autoload-dump hook runs `php artisan package:discover`, which exits 1
# and aborts the build when artisan is missing.
COPY composer.json composer.lock ./

RUN composer install --no-dev --prefer-dist --no-scripts --no-autoloader --no-interaction --no-progress

# Make npm/node available from the node stage (php-fpm-alpine ships neither)
COPY --from=node_base /usr/local/bin/node /usr/local/bin/node
COPY --from=node_base /usr/local/lib/node_modules /usr/local/lib/node_modules
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm

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