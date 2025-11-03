#!/bin/sh

set -e

echo "🚀 Starting Laravel application setup..."

# Wait for MySQL to be ready
echo "⏳ Waiting for MySQL to be ready..."
until nc -z mysql 3306; do
  echo "Waiting for MySQL..."
  sleep 2
done
echo "✅ MySQL is ready!"

# Switch to root temporarily for setup commands that might need elevated permissions
if [ "$(id -u)" != "0" ]; then
    echo "Running as www-data user, switching to application directory..."
    cd /var/www/html
else
    # If running as root, switch to www-data for application commands
    echo "Running as root, will execute commands as www-data..."
fi

# Install Composer dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# Clear Laravel caches and optimize
echo "🧹 Clearing Laravel caches..."
php artisan optimize:clear --quiet

echo "🗂️ Clearing view cache..."
php artisan view:clear --quiet

# Generate application key if not exists
if [ ! -f .env ]; then
    echo "⚠️ .env file not found, copying from .env.example..."
    cp .env.example .env
fi

# Check if APP_KEY is empty
if ! grep -q "APP_KEY=.*[^[:space:]]" .env 2>/dev/null || grep -q "APP_KEY=$" .env 2>/dev/null; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force --quiet
else
    echo "🔑 Application key already exists."
fi

# Create storage link
echo "🔗 Creating storage symbolic link..."
php artisan storage:link --quiet 2>/dev/null || echo "Storage link already exists or failed to create."

# Set proper permissions (if running as root)
if [ "$(id -u)" = "0" ]; then
    echo "🔧 Setting proper permissions..."
    chown -R www-data:www-data /var/www/html
    chmod -R 775 storage bootstrap/cache

    echo "👤 Switching to www-data user..."
    # Alpine Linux doesn't have su-exec by default, using su instead
    exec su www-data -s /bin/sh -c "exec $*"
fi

echo "✅ Laravel application setup completed!"

# Execute the main command
exec "$@"
