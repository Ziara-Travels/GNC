#!/bin/bash
set -euo pipefail

cd /var/www/html

PORT="${PORT:-8080}"

# Force SQLite for Render persistent disk deployment (ignore any MySQL env vars)
export DB_CONNECTION=sqlite
export DB_DATABASE="/var/data/database.sqlite"
DB_PATH="/var/data/database.sqlite"

# Set up temp directory for Blade compilation BEFORE any artisan commands
mkdir -p /tmp/laravel-temp
chown -R www-data:www-data /tmp/laravel-temp
chmod -R 775 /tmp/laravel-temp
export TMPDIR="/tmp/laravel-temp"

echo "Starting Ziara Travels deployment (port: ${PORT}, database: ${DB_PATH})..."

# Render mounts the persistent disk over the database directory, so the image
# cannot ship a pre-created database file. Create it here if it is missing.
mkdir -p "$(dirname "${DB_PATH}")"
if [ ! -f "${DB_PATH}" ]; then
    echo "Creating SQLite database at ${DB_PATH}"
    touch "${DB_PATH}"
fi

# PHP-FPM listens on TCP rather than a unix socket: supervisord runs it as
# root but www-data (the pool user) cannot create a socket file in /var/run.
sed -i -E 's|^;?listen = .*|listen = 127.0.0.1:9000|' /usr/local/etc/php-fpm.d/www.conf
grep -q '^listen = 127.0.0.1:9000' /usr/local/etc/php-fpm.d/www.conf \
    || echo "WARNING: could not set php-fpm listen address"

# Fix temp directory for Blade compilation - PHP-FPM clears env by default
# Set temp dir directly in PHP-FPM config and preserve environment
sed -i -E 's|^;?clear_env = .*|clear_env = no|' /usr/local/etc/php-fpm.d/www.conf
grep -q '^clear_env = no' /usr/local/etc/php-fpm.d/www.conf \
    || echo 'clear_env = no' >> /usr/local/etc/php-fpm.d/www.conf

# Set temp directory in PHP-FPM config (for upload_tmp_dir and tempnam())
sed -i '/^;?php_admin_value\[upload_tmp_dir\]/d' /usr/local/etc/php-fpm.d/www.conf
echo 'php_admin_value[upload_tmp_dir] = /tmp/laravel-temp' >> /usr/local/etc/php-fpm.d/www.conf
sed -i '/^;?php_admin_value\[sys_temp_dir\]/d' /usr/local/etc/php-fpm.d/www.conf
echo 'php_admin_value[sys_temp_dir] = /tmp/laravel-temp' >> /usr/local/etc/php-fpm.d/www.conf

# Also pass TMPDIR via env
sed -i '/^env\[TMPDIR\]/d' /usr/local/etc/php-fpm.d/www.conf
echo 'env[TMPDIR] = /tmp/laravel-temp' >> /usr/local/etc/php-fpm.d/www.conf

# Render routes to the port it injects via $PORT.
sed "s/__PORT__/${PORT}/g" /usr/local/share/nginx/nginx.conf.template \
    > /etc/nginx/http.d/default.conf

# Writable directories the framework needs. Created here (rather than relied on
# from the image) because the file session/cache/view writers must have them.
mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views \
         storage/logs bootstrap/cache

echo "Running migrations..."
php artisan migrate --force

# Roles/permissions are required by the authorization middleware. This seeder
# is idempotent (firstOrCreate), so it is safe on every boot. The other
# seeders are deliberately NOT run: they create demo accounts with well-known
# passwords and are not idempotent.
php artisan db:seed --class=PermissionSeeder --force

echo "Optimizing Laravel..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Everything written above ran as root; the FPM/scheduler workers run as
# www-data and need to own the writable state.
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache "$(dirname "${DB_PATH}")"
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
chmod 664 "${DB_PATH}"

if [ ! -L /var/www/html/public/storage ]; then
    php artisan storage:link
fi

echo "Starting services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf