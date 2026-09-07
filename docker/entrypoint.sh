#!/bin/bash
set -e

# Render assigns a dynamic port via the $PORT environment variable (defaults to 80 or 10000)
PORT=${PORT:-80}
echo "Configuring container to listen on port ${PORT}..."

# Update Apache listening port to match Render's assigned port
sed -i "s/Listen [0-9]*/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:[0-9]*>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

# Ensure storage subdirectories exist
mkdir -p /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/app/public/submissions \
         /var/www/html/storage/app/public/templates \
         /var/www/html/bootstrap/cache

# Fix ownership and permissions for www-data
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Link storage/app/public to public/storage
php artisan storage:link --force || true

# Run database migrations if enabled
if [ "${RUN_MIGRATIONS}" = "true" ]; then
    echo "Running database migrations..."
    php artisan migrate --force || true
fi

# Optimization caches for production
if [ "${APP_ENV}" = "production" ]; then
    echo "Caching routes and views for production..."
    php artisan route:cache || true
    php artisan view:cache || true
fi

# Start Apache in the foreground
echo "Starting Apache web server..."
exec apache2-foreground
