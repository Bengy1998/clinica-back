#!/bin/bash

# SCRIPT DE CONFIGURACIÓN RÁPIDA LARAVEL PRODUCCIÓN
# =================================================
# Ejecutar después de clonar el proyecto en el servidor
# chmod +x quick_setup_production.sh && ./quick_setup_production.sh

set -e

echo "🚀 CONFIGURACIÓN RÁPIDA LARAVEL PARA PRODUCCIÓN"
echo "==============================================="

# Colores
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

print_step() {
    echo -e "${GREEN}[PASO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[AVISO]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Verificar que estamos en el directorio correcto
if [ ! -f "docker-compose.yml" ]; then
    print_error "No se encontró docker-compose.yml. Ejecuta este script desde el directorio del proyecto."
    exit 1
fi

# Solicitar información del dominio
echo ""
read -p "¿Cuál es tu dominio? (ejemplo: midominio.com): " DOMAIN
read -p "¿Este es un entorno de producción? (y/n): " IS_PRODUCTION

# Crear archivo .env basado en el entorno
print_step "Configurando archivo .env..."

if [ -f ".env" ]; then
    print_warning "El archivo .env ya existe. Creando backup..."
    cp .env .env.backup.$(date +%Y%m%d_%H%M%S)
fi

cat > .env << EOF
APP_NAME="Clínica"
APP_ENV=$([ "$IS_PRODUCTION" = "y" ] && echo "production" || echo "local")
APP_KEY=
APP_DEBUG=$([ "$IS_PRODUCTION" = "y" ] && echo "false" || echo "true")
APP_URL=https://$DOMAIN

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=clinica
DB_USERNAME=root
DB_PASSWORD=root2025

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@$DOMAIN"
MAIL_FROM_NAME="\${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="\${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="\${PUSHER_HOST}"
VITE_PUSHER_PORT="\${PUSHER_PORT}"
VITE_PUSHER_SCHEME="\${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="\${PUSHER_APP_CLUSTER}"

JWT_SECRET=
JWT_ALGO=HS256
EOF

print_step "Archivo .env creado"

# Verificar si Docker está instalado
if ! command -v docker &> /dev/null; then
    print_error "Docker no está instalado. Instálalo primero."
    exit 1
fi

# Verificar si Docker Compose está instalado
if ! command -v docker-compose &> /dev/null; then
    print_error "Docker Compose no está instalado. Instálalo primero."
    exit 1
fi

# Construir e iniciar contenedores
print_step "Construyendo e iniciando contenedores Docker..."
docker-compose down -v 2>/dev/null || true
docker-compose up -d --build

# Esperar a que MySQL esté listo
print_step "Esperando a que MySQL esté disponible..."
sleep 30

# Verificar que los contenedores estén funcionando
if ! docker-compose ps | grep -q "Up"; then
    print_error "Los contenedores no se iniciaron correctamente"
    docker-compose logs
    exit 1
fi

# Instalar dependencias de Composer
print_step "Instalando dependencias de Composer..."
if [ "$IS_PRODUCTION" = "y" ]; then
    docker-compose exec php composer install --optimize-autoloader --no-dev --no-interaction
else
    docker-compose exec php composer install --no-interaction
fi

# Generar clave de aplicación
print_step "Generando clave de aplicación..."
docker-compose exec php php artisan key:generate --force

# Configurar JWT si existe
if docker-compose exec php php artisan list | grep -q "jwt:secret"; then
    print_step "Configurando JWT..."
    docker-compose exec php php artisan jwt:secret --force
fi

# Ejecutar migraciones
print_step "Ejecutando migraciones de base de datos..."
docker-compose exec php php artisan migrate --force

# Ejecutar seeders si no es producción
if [ "$IS_PRODUCTION" != "y" ]; then
    print_step "Ejecutando seeders..."
    docker-compose exec php php artisan db:seed --force
fi

# Crear enlace de storage
print_step "Creando enlace de almacenamiento..."
docker-compose exec php php artisan storage:link

# Optimizar para producción
if [ "$IS_PRODUCTION" = "y" ]; then
    print_step "Optimizando para producción..."
    docker-compose exec php php artisan config:cache
    docker-compose exec php php artisan route:cache
    docker-compose exec php php artisan view:cache
    docker-compose exec php php artisan optimize
else
    print_step "Limpiando caché de desarrollo..."
    docker-compose exec php php artisan optimize:clear
fi

# Configurar permisos
print_step "Configurando permisos de archivos..."
docker-compose exec php chmod -R 755 /var/www/html
docker-compose exec php chmod -R 775 storage bootstrap/cache

# Verificar que la aplicación funcione
print_step "Verificando que la aplicación funcione..."
sleep 10

if curl -sf http://localhost:8080 > /dev/null; then
    print_step "✅ Aplicación funcionando correctamente en http://localhost:8080"
else
    print_warning "⚠️ La aplicación podría no estar funcionando correctamente"
    print_warning "Verifica los logs: docker-compose logs"
fi

# Mostrar información de configuración SSL si es producción
if [ "$IS_PRODUCTION" = "y" ]; then
    echo ""
    echo "🔐 CONFIGURACIÓN SSL"
    echo "==================="
    print_step "Para configurar SSL con tu dominio $DOMAIN:"
    print_step "1. Ejecuta: chmod +x setup_ssl.sh && sudo ./setup_ssl.sh"
    print_step "2. O configura SSL manualmente siguiendo ubuntu22_server_guide.txt"
    echo ""
fi

# Crear archivo de información del proyecto
cat > PROJECT_INFO.txt << EOF
INFORMACIÓN DEL PROYECTO CLÍNICA
==============================

Dominio: $DOMAIN
Entorno: $([ "$IS_PRODUCTION" = "y" ] && echo "Producción" || echo "Desarrollo")
Fecha de configuración: $(date)

URLS DE ACCESO:
- Aplicación: http://localhost:8080 (interno)
- Aplicación (con SSL): https://$DOMAIN
- MySQL: localhost:3306

CREDENCIALES MYSQL:
- Usuario: root
- Contraseña: root2025
- Base de datos: clinica

COMANDOS ÚTILES:
- Ver logs: docker-compose logs -f
- Reiniciar: docker-compose restart
- Entrar a PHP: docker-compose exec php sh
- Ejecutar Artisan: docker-compose exec php php artisan [comando]
- Backup DB: docker-compose exec mysql mysqldump -u root -proot2025 clinica > backup.sql

ARCHIVOS IMPORTANTES:
- Configuración Docker: docker-compose.yml
- Variables de entorno: .env
- Comandos completos: comandos_docker_laravel.txt
- Guía Ubuntu: ubuntu22_server_guide.txt
- Setup SSL: setup_ssl.sh

PRÓXIMOS PASOS:
$([ "$IS_PRODUCTION" = "y" ] && echo "1. Configurar SSL ejecutando: ./setup_ssl.sh" || echo "1. Continuar desarrollo")
2. Configurar dominio DNS apuntando a este servidor
3. Configurar backups automáticos
4. Configurar monitoreo
EOF

# Mostrar resumen final
echo ""
echo "🎉 CONFIGURACIÓN COMPLETADA"
echo "=========================="
print_step "Información del proyecto guardada en: PROJECT_INFO.txt"
print_step "Comandos completos disponibles en: comandos_docker_laravel.txt"
print_step "Guía de Ubuntu disponible en: ubuntu22_server_guide.txt"
echo ""

if [ "$IS_PRODUCTION" = "y" ]; then
    print_step "🔧 Para completar la configuración de producción:"
    print_step "1. Ejecutar: ./setup_ssl.sh"
    print_step "2. Configurar DNS de $DOMAIN apuntando a este servidor"
    print_step "3. Revisar configuración de firewall"
else
    print_step "🔧 Para desarrollo:"
    print_step "1. La aplicación está disponible en http://localhost:8080"
    print_step "2. Puedes conectar a MySQL en localhost:3306"
fi

echo ""
print_step "📋 Estado de contenedores:"
docker-compose ps

echo ""
print_step "📊 Para ver logs en tiempo real:"
print_step "docker-compose logs -f"

echo ""
print_step "✅ ¡Configuración completada exitosamente!"
