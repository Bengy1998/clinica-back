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

# Asegurar que estamos en el directorio correcto
cd /var/www/html

# Configurar Git para evitar el warning de ownership
echo "🔧 Configuring Git safe directory..."
git config --global --add safe.directory /var/www/html || echo "Git config already set or not needed"

# Configurar permisos apropiados
echo "🔧 Setting up proper permissions..."
if [ "$(id -u)" = "0" ]; then
    # Si ejecutamos como root, configurar permisos y luego cambiar a www-data
    echo "Running as root, setting ownership..."

    # Asegurar que www-data sea el propietario
    chown -R www-data:www-data /var/www/html

    # Crear directorios necesarios con permisos correctos
    mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache vendor
    chown -R www-data:www-data storage bootstrap/cache vendor
    chmod -R 775 storage bootstrap/cache
    chmod -R 755 vendor

    # Install Composer dependencies como www-data
    echo "📦 Installing Composer dependencies as www-data..."
    su www-data -s /bin/sh -c "composer install --no-dev --optimize-autoloader --no-interaction"
else
    # Si ya ejecutamos como www-data
    echo "Running as www-data user..."

    # Crear directorios necesarios
    mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache vendor
    chmod -R 775 storage bootstrap/cache 2>/dev/null || true

    # Install Composer dependencies
    echo "📦 Installing Composer dependencies..."
    composer install --no-dev --optimize-autoloader --no-interaction
fi

# Comandos Laravel ejecutados directamente en el contexto apropiado

# Ejecutar comandos Laravel según el contexto
if [ "$(id -u)" = "0" ]; then
    echo "🎯 Running Laravel commands as www-data..."
    
    # Ejecutar comandos Laravel directamente como www-data
    su www-data -s /bin/sh -c '
        cd /var/www/html
        echo "🧹 Clearing Laravel caches..."
        php artisan optimize:clear --quiet 2>/dev/null || echo "Cache clear skipped (first run)"
        
        echo "🗂️ Clearing view cache..."
        php artisan view:clear --quiet 2>/dev/null || echo "View cache clear skipped"
        
        # Generate application key if not exists
        if [ ! -f .env ]; then
            echo "⚠️ .env file not found, copying from .env.example..."
            cp .env.example .env 2>/dev/null || echo ".env.example not found, skipping"
        fi
        
        # Check if APP_KEY is empty
        if [ -f .env ] && (! grep -q "APP_KEY=.*[^[:space:]]" .env 2>/dev/null || grep -q "APP_KEY=$" .env 2>/dev/null); then
            echo "🔑 Generating application key..."
            php artisan key:generate --force --quiet
        else
            echo "🔑 Application key already exists or .env not ready."
        fi
        
        # Create storage link
        echo "🔗 Creating storage symbolic link..."
        php artisan storage:link --quiet 2>/dev/null || echo "Storage link already exists or skipped."
    '
    
    echo "🔧 Final permissions setup..."
    chown -R www-data:www-data /var/www/html
    chmod -R 775 storage bootstrap/cache 2>/dev/null || true
    
    echo "👤 Starting PHP-FPM as www-data..."
    echo "✅ Laravel application setup completed!"
    # Iniciar PHP-FPM
    exec php-fpm
else
    # Si ya corremos como www-data, ejecutar comandos directamente
    echo "🎯 Already running as www-data, executing Laravel commands..."
    
    echo "🧹 Clearing Laravel caches..."
    php artisan optimize:clear --quiet 2>/dev/null || echo "Cache clear skipped (first run)"
    
    echo "🗂️ Clearing view cache..."
    php artisan view:clear --quiet 2>/dev/null || echo "View cache clear skipped"
    
    # Generate application key if not exists
    if [ ! -f .env ]; then
        echo "⚠️ .env file not found, copying from .env.example..."
        cp .env.example .env 2>/dev/null || echo ".env.example not found, skipping"
    fi
    
    # Check if APP_KEY is empty
    if [ -f .env ] && (! grep -q "APP_KEY=.*[^[:space:]]" .env 2>/dev/null || grep -q "APP_KEY=$" .env 2>/dev/null); then
        echo "🔑 Generating application key..."
        php artisan key:generate --force --quiet
    else
        echo "🔑 Application key already exists or .env not ready."
    fi
    
    # Create storage link
    echo "🔗 Creating storage symbolic link..."
    php artisan storage:link --quiet 2>/dev/null || echo "Storage link already exists or skipped."
    
    echo "✅ Laravel application setup completed!"
    # Execute the main command (PHP-FPM)
    exec "$@"
fi
