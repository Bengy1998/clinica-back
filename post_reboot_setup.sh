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

print_step "Construyendo e iniciando contenedores..."
docker-compose up -d --build

print_step "Esperando que MySQL esté listo..."
echo "Esperando 30 segundos para que MySQL inicie completamente..."
sleep 30

# Verificar que los contenedores estén funcionando
print_step "Verificando estado de contenedores..."
if ! docker-compose ps | grep -q "Up"; then
    print_error "Los contenedores no se iniciaron correctamente"
    docker-compose logs
    exit 1
fi

print_step "Generando clave de aplicación..."
docker-compose exec php php artisan key:generate --force

print_step "Ejecutando migraciones..."
docker-compose exec php php artisan migrate --force

print_step "Creando enlace de almacenamiento..."
docker-compose exec php php artisan storage:link

print_step "Instalando dependencias de producción..."
docker-compose exec php composer install --optimize-autoloader --no-dev --no-interaction

print_step "Optimizando aplicación para producción..."
docker-compose exec php php artisan config:cache
docker-compose exec php php artisan route:cache
docker-compose exec php php artisan view:cache

print_step "Configurando permisos finales..."
docker-compose exec php chmod -R 755 /var/www/html
docker-compose exec php chmod -R 775 storage bootstrap/cache

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
