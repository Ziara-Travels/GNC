#!/bin/bash
set -euo pipefail

cd /var/www/html

PORT="${PORT:-8080}"

# Force SQLite for Render persistent disk deployment (ignore any MySQL env vars)
export DB_CONNECTION=sqlite
export DB_DATABASE="/var/data/database.sqlite"
DB_PATH="/var/data/database.sqlite"

# Scratch space for session/cache writers. Note Blade does NOT compile here --
# it compiles into storage/framework/views, which the pool user must be able to
# write. TMPDIR only affects other temp-file consumers.
mkdir -p /tmp/laravel-temp
chown -R www-data:www-data /tmp/laravel-temp
chmod -R 775 /tmp/laravel-temp
export TMPDIR="/tmp/laravel-temp"

# Keep the default sys_temp_dir (/tmp, mode 1777) usable: tempnam() falls back
# to it whenever the directory it was given is unusable, and it is the last
# line of defence for any other temp-file writer in the app.
chmod 1777 /tmp

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

# Pin the pool to www-data. Alpine's php84-fpm ships www.conf with user/group
# unset, so a root-run master falls back to the "nobody" account, which cannot
# write storage/. Blade then calls tempnam(storage/framework/views), PHP falls
# back to sys_temp_dir and emits "file created in the system's temporary
# directory" -- which Laravel promotes to an ErrorException. Normalise the pool
# the same way the Dockerfile does, so behaviour cannot drift between builds.
FPM_CONF=/usr/local/etc/php-fpm.d/www.conf
sed -i -E '/^;?(user|group) = /d' "$FPM_CONF"
printf 'user = www-data\ngroup = www-data\n' >> "$FPM_CONF"

# Preserve the environment (TMPDIR and friends) into the pool.
sed -i -E '/^;?clear_env = /d' "$FPM_CONF"
printf 'clear_env = no\nenv[TMPDIR] = /tmp/laravel-temp\n' >> "$FPM_CONF"

grep -q '^user = www-data$' "$FPM_CONF" \
    || { echo "FATAL: could not pin the FPM pool to www-data" >&2; exit 1; }

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

# Prove, as the pool user, that the exact operation Blade performs at request
# time succeeds. tempnam() only raises "file created in the system's temporary
# directory" when it cannot use the directory it was given, so we assert the
# probe lands in the resolved view.compiled path and abort the boot otherwise.
# Booting the kernel matters here: config/view.php resolves the compiled path
# through realpath(), which yields false if the directory is absent, and Blade
# would then try to write to /<hash>.php at the filesystem root.
echo "Verifying compiled-view writability as www-data..."
su-exec www-data php -r '
require "/var/www/html/vendor/autoload.php";
$app = require "/var/www/html/bootstrap/app.php";
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$dir  = config("view.compiled");
$fail = [];

if (! is_string($dir) || $dir === "")   { $fail[] = "view.compiled is not a path: " . var_export($dir, true); }
elseif (! is_dir($dir))                 { $fail[] = "view.compiled dir missing: " . $dir; }
elseif (! is_writable($dir))            { $fail[] = "view.compiled dir not writable: " . $dir; }
if (! is_writable(sys_get_temp_dir()))  { $fail[] = "sys temp not writable: " . sys_get_temp_dir(); }

if (! $fail) {
    set_error_handler(static fn () => true);
    $probe = tempnam($dir, "bladecheck");
    restore_error_handler();
    if (! is_string($probe) || $probe === "") {
        $fail[] = "tempnam() returned no file";
    } else {
        if (realpath($dir) === false || realpath(dirname($probe)) !== realpath($dir)) {
            $fail[] = "tempnam() fell back to " . dirname($probe);
        }
        @unlink($probe);
    }
}

if ($fail) {
    fwrite(STDERR, "FATAL: compiled-view check failed:\n  - " . implode("\n  - ", $fail) . "\n");
    exit(1);
}
echo "  view.compiled=" . $dir . " writable; tempnam() stays inside it\n";
'

echo "Starting services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
