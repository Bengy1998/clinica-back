#!/bin/bash

# SCRIPT POST-REINICIO - COMPLETAR INSTALACIÓN
# ============================================
# Ejecutar después del reinicio del servidor

set -e

echo "🔄 COMPLETANDO INSTALACIÓN DESPUÉS DEL REINICIO"
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
    print_error "Ejecuta este script desde el directorio del proyecto (/var/www/clinica)"
    exit 1
fi

print_step "Verificando instalación de Docker..."
if ! command -v docker &> /dev/null; then
    print_error "Docker no está instalado o no está en PATH"
    exit 1
fi

if ! command -v docker-compose &> /dev/null; then
    print_error "Docker Compose no está instalado"
    exit 1
fi

print_step "Verificando permisos Docker..."
if ! docker ps &> /dev/null; then
    print_error "El usuario actual no tiene permisos Docker"
    print_error "Ejecuta: sudo usermod -aG docker $USER && logout"
    exit 1
fi

print_step "Verificando y limpiando contenedores existentes..."

# Verificar si hay contenedores en mal estado
if docker-compose ps | grep -E "(Restarting|Exit)"; then
    print_warning "Detectados contenedores en mal estado, limpiando..."
    docker-compose down --remove-orphans
    docker system prune -f
    sleep 5
elif docker-compose ps | grep -q "Up"; then
    print_step "Los contenedores están funcionando, reiniciando para asegurar estado limpio..."
    docker-compose down
    sleep 5
fi

print_step "Verificando archivo .env..."
if [ ! -f ".env" ]; then
    print_error "Archivo .env no encontrado"
    exit 1
fi

print_step "Construyendo e iniciando contenedores..."
if ! docker-compose up -d --build; then
    print_error "Error al construir/iniciar contenedores"
    print_step "Logs de construcción:"
    docker-compose logs
    exit 1
fi

print_step "Esperando que todos los servicios estén listos..."
echo "Esperando 45 segundos para que todos los servicios inicien completamente..."

# Espera inteligente - verificar cada 5 segundos
for i in {1..9}; do
    echo "Verificando servicios... ($((i*5))/45 segundos)"
    sleep 5

    # Verificar si MySQL responde
    if docker-compose exec -T mysql mysqladmin ping -h localhost -u root -proot2025 &>/dev/null; then
        print_step "✅ MySQL está listo después de $((i*5)) segundos"
        break
    fi

    if [ $i -eq 9 ]; then
        print_warning "MySQL tardó más de 45 segundos, continuando..."
    fi
done

# Verificar que los contenedores estén funcionando
print_step "Verificando estado de contenedores..."
if ! docker-compose ps | grep -q "Up"; then
    print_error "Los contenedores no se iniciaron correctamente"
    print_step "Logs de contenedores:"
    docker-compose logs --tail=20
    exit 1
fi

print_step "Esperando que PHP-FPM esté completamente listo y estable..."

# Función para verificar si el contenedor PHP está estable
wait_for_php_container() {
    local max_attempts=12
    local attempt=1

    while [ $attempt -le $max_attempts ]; do
        echo "Verificando estabilidad del contenedor PHP... (intento $attempt/$max_attempts)"

        # Verificar que el contenedor esté ejecutándose y no reiniciándose
        local php_status=$(docker-compose ps php | grep -v "Name" | awk '{print $4}')

        if [[ "$php_status" == "Up" ]]; then
            # Intentar un comando simple para verificar que PHP responde
            if docker-compose exec -T php php --version >/dev/null 2>&1; then
                print_step "✅ Contenedor PHP estable después de $((attempt * 10)) segundos"
                return 0
            fi
        fi

        if [[ "$php_status" == *"Restarting"* ]]; then
            print_warning "Contenedor PHP reiniciándose, esperando..."
        fi

        sleep 10
        ((attempt++))
    done

    print_error "El contenedor PHP no se estabilizó después de $((max_attempts * 10)) segundos"
    print_step "Estado actual de contenedores:"
    docker-compose ps
    print_step "Logs del contenedor PHP:"
    docker-compose logs --tail=30 php
    return 1
}

# Esperar que PHP esté estable
if ! wait_for_php_container; then
    print_error "No se pudo estabilizar el contenedor PHP, intentando solución drástica..."

    print_step "Parando todos los contenedores..."
    docker-compose down --remove-orphans

    print_step "Eliminando contenedor PHP problemático..."
    docker container prune -f

    print_step "Construyendo solo el contenedor PHP..."
    docker-compose build --no-cache php

    print_step "Iniciando contenedores uno por uno..."
    docker-compose up -d mysql
    sleep 15

    print_step "Verificando que MySQL esté funcionando..."
    if ! docker-compose exec -T mysql mysqladmin ping -h localhost -u root -proot2025; then
        print_error "MySQL no responde, verificando logs..."
        docker-compose logs mysql
        exit 1
    fi

    print_step "Iniciando nginx y php..."
    docker-compose up -d

    print_step "Esperando nueva estabilización..."
    sleep 20

    # Verificar una vez más
    if ! wait_for_php_container; then
        print_error "El contenedor PHP sigue fallando después de reconstrucción"
        print_step "Logs detallados del contenedor PHP:"
        docker-compose logs php
        print_step "Entrando en modo diagnóstico manual..."
        print_step "Ejecuta manualmente: docker-compose logs php"
        print_step "Y luego: docker-compose exec php sh"
        exit 1
    fi
fi

print_step "Generando clave de aplicación..."
if ! docker-compose exec -T php php artisan key:generate --force; then
    print_error "Error generando clave de aplicación"
    print_step "Verificando logs de PHP para diagnosticar el problema:"
    docker-compose logs --tail=20 php
    exit 1
fi

print_step "Ejecutando migraciones..."
if ! docker-compose exec -T php php artisan migrate --force; then
    print_error "Error en migraciones, verificando base de datos..."

    # Verificar que MySQL esté accesible
    if docker-compose exec -T mysql mysql -u root -proot2025 -e "SHOW DATABASES;" >/dev/null 2>&1; then
        print_step "Base de datos accesible, verificando configuración Laravel..."
        docker-compose exec -T php php artisan config:clear
        print_step "Reintentando migraciones..."
        if ! docker-compose exec -T php php artisan migrate --force; then
            print_error "Las migraciones fallaron definitivamente"
            print_step "Logs del contenedor PHP:"
            docker-compose logs --tail=20 php
            exit 1
        fi
    else
        print_error "No se puede conectar a MySQL"
        docker-compose logs --tail=20 mysql
        exit 1
    fi
fi

print_step "Creando enlace de almacenamiento..."
docker-compose exec -T php php artisan storage:link

print_step "Instalando dependencias de producción..."
docker-compose exec -T php composer install --optimize-autoloader --no-dev --no-interaction

print_step "Optimizando aplicación para producción..."
docker-compose exec -T php php artisan config:cache
docker-compose exec -T php php artisan route:cache
docker-compose exec -T php php artisan view:cache

print_step "Configurando permisos finales..."
docker-compose exec -T php chmod -R 755 /var/www/html
docker-compose exec -T php chmod -R 775 storage bootstrap/cache

print_step "Verificando funcionamiento..."
sleep 5

# Test local
if curl -sf http://localhost > /dev/null; then
    print_step "✅ Test local exitoso"
else
    print_warning "⚠️ El test local falló, verificando logs..."
    docker-compose logs --tail=10
fi

# Obtener IP pública
PUBLIC_IP=$(curl -s -4 icanhazip.com 2>/dev/null || echo "No se pudo obtener IP")

echo ""
echo "🎉 INSTALACIÓN COMPLETADA"
echo "========================"
print_step "Estado de contenedores:"
docker-compose ps

echo ""
print_step "🌐 ACCESO A LA APLICACIÓN:"
if [ "$PUBLIC_IP" != "No se pudo obtener IP" ]; then
    echo "URL: http://$PUBLIC_IP"
    echo ""
    print_step "Test desde tu computadora:"
    echo "curl -I http://$PUBLIC_IP"
else
    echo "URL: http://TU_IP_PUBLICA"
fi

echo ""
print_step "📋 PRÓXIMOS PASOS:"
echo "1. Verificar que la aplicación carga correctamente"
echo "2. Si quieres agregar dominio y SSL: migracion_a_dominio_ssl.txt"
echo "3. Para uso diario: comandos_docker_laravel.txt"

echo ""
print_step "🔧 COMANDOS ÚTILES:"
echo "Ver logs:        docker-compose logs -f"
echo "Reiniciar:       docker-compose restart"
echo "Ver estado:      docker-compose ps"
echo "Acceso MySQL:    docker-compose exec mysql mysql -u root -proot2025"

echo ""
print_step "📊 ESTADO ACTUAL DEL SISTEMA:"
echo "Uso de disco:"
df -h | head -5

echo ""
echo "Contenedores Docker:"
docker stats --no-stream --format "table {{.Name}}\t{{.CPUPerc}}\t{{.MemUsage}}"

echo ""
print_step "✅ INSTALACIÓN COMPLETADA EXITOSAMENTE"
print_step "La aplicación está lista para usar con IP pública"
