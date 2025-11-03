#!/bin/bash

# SCRIPT DE CONFIGURACIÓN SSL AUTOMÁTICA PARA LARAVEL DOCKER
# ===========================================================
# Este script automatiza la configuración SSL para tu dominio
# Ejecutar como: chmod +x setup_ssl.sh && sudo ./setup_ssl.sh

set -e

echo "🔐 CONFIGURACIÓN SSL PARA CLÍNICA LARAVEL"
echo "=========================================="

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Función para imprimir con colores
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Verificar que se ejecute como root
if [[ $EUID -ne 0 ]]; then
   print_error "Este script debe ejecutarse como root (usar sudo)"
   exit 1
fi

# Solicitar información del dominio
echo ""
read -p "Ingresa tu dominio (ejemplo: midominio.com): " DOMAIN
read -p "¿Incluir www.$DOMAIN? (y/n): " INCLUDE_WWW

if [[ $INCLUDE_WWW == "y" || $INCLUDE_WWW == "Y" ]]; then
    DOMAIN_LIST="$DOMAIN www.$DOMAIN"
    CERTBOT_DOMAINS="-d $DOMAIN -d www.$DOMAIN"
else
    DOMAIN_LIST="$DOMAIN"
    CERTBOT_DOMAINS="-d $DOMAIN"
fi

print_status "Configurando SSL para: $DOMAIN_LIST"

# Verificar que Docker esté instalado
if ! command -v docker &> /dev/null; then
    print_error "Docker no está instalado. Instálalo primero."
    exit 1
fi

# Verificar que Nginx esté instalado en el host
if ! command -v nginx &> /dev/null; then
    print_warning "Nginx no está instalado en el host. Instalando..."
    apt update
    apt install nginx -y
fi

# Instalar Certbot
if ! command -v certbot &> /dev/null; then
    print_status "Instalando Certbot..."
    apt update
    apt install certbot python3-certbot-nginx -y
fi

# Detener servicios temporalmente
print_status "Deteniendo servicios temporalmente..."
systemctl stop nginx 2>/dev/null || true

# Navegar al directorio del proyecto
PROJECT_DIR="/var/www/clinica"
if [ ! -d "$PROJECT_DIR" ]; then
    read -p "Ingresa la ruta completa del proyecto: " PROJECT_DIR
fi

cd "$PROJECT_DIR"

# Detener contenedores Docker
print_status "Deteniendo contenedores Docker..."
docker-compose down 2>/dev/null || true

# Configurar Nginx como reverse proxy
print_status "Configurando Nginx reverse proxy..."

cat > /etc/nginx/sites-available/clinica << EOF
# Configuración HTTP (temporal para validación SSL)
server {
    listen 80;
    server_name $DOMAIN_LIST;

    # Permitir validación de Let's Encrypt
    location /.well-known/acme-challenge/ {
        root /var/www/html;
    }

    # Redireccionar todo el resto a HTTPS
    location / {
        return 301 https://\$server_name\$request_uri;
    }
}

# Configuración HTTPS
server {
    listen 443 ssl http2;
    server_name $DOMAIN_LIST;

    # Certificados SSL (se configurarán automáticamente por Certbot)
    ssl_certificate /etc/letsencrypt/live/$DOMAIN/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/$DOMAIN/privkey.pem;

    # Configuración SSL moderna
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-SHA384:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-SHA256:DHE-RSA-AES256-SHA256:DHE-RSA-AES128-SHA256:ECDHE-RSA-AES256-SHA:ECDHE-RSA-AES128-SHA:DHE-RSA-AES256-SHA:DHE-RSA-AES128-SHA:AES256-GCM-SHA384:AES128-GCM-SHA256:AES256-SHA256:AES128-SHA256:AES256-SHA:AES128-SHA:HIGH:!aNULL:!eNULL:!EXPORT:!DES:!RC4:!MD5:!PSK:!SRP:!CAMELLIA;
    ssl_prefer_server_ciphers off;
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 10m;

    # Headers de seguridad
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains; preload" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    # Configuración del proxy
    location / {
        proxy_pass http://127.0.0.1:8080;  # Puerto interno Docker
        proxy_set_header Host \$host;
        proxy_set_header X-Real-IP \$remote_addr;
        proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto \$scheme;
        proxy_set_header X-Forwarded-Host \$server_name;

        # Timeouts
        proxy_connect_timeout 60s;
        proxy_send_timeout 60s;
        proxy_read_timeout 60s;

        # Buffer settings
        proxy_buffer_size 4k;
        proxy_buffers 8 4k;
        proxy_busy_buffers_size 8k;
    }

    # Logs específicos
    access_log /var/log/nginx/clinica_access.log;
    error_log /var/log/nginx/clinica_error.log;
}
EOF

# Habilitar el sitio
ln -sf /etc/nginx/sites-available/clinica /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default

# Verificar configuración de Nginx
print_status "Verificando configuración de Nginx..."
nginx -t

# Iniciar Nginx
systemctl start nginx
systemctl enable nginx

# Crear directorio para validación ACME
mkdir -p /var/www/html

# Obtener certificado SSL
print_status "Obteniendo certificado SSL de Let's Encrypt..."
certbot certonly --webroot -w /var/www/html $CERTBOT_DOMAINS --email admin@$DOMAIN --agree-tos --non-interactive

# Configurar renovación automática
print_status "Configurando renovación automática..."
(crontab -l 2>/dev/null; echo "0 12 * * * /usr/bin/certbot renew --quiet && systemctl reload nginx") | crontab -

# Actualizar docker-compose.yml para usar puerto interno
print_status "Actualizando configuración Docker..."
sed -i 's/"80:80"/"8080:80"/g' docker-compose.yml

# Configurar firewall
print_status "Configurando firewall..."
ufw allow 22/tcp
ufw allow 80/tcp
ufw allow 443/tcp
ufw --force enable

# Recargar Nginx con SSL
systemctl reload nginx

# Iniciar contenedores Docker
print_status "Iniciando contenedores Docker..."
docker-compose up -d --build

# Esperar que los servicios estén listos
print_status "Esperando que los servicios estén listos..."
sleep 30

# Verificar SSL
print_status "Verificando configuración SSL..."
if curl -sf https://$DOMAIN > /dev/null; then
    print_status "✅ SSL configurado correctamente!"
else
    print_warning "⚠️ Verificar manualmente la configuración SSL"
fi

echo ""
echo "🎉 CONFIGURACIÓN COMPLETADA"
echo "=========================="
print_status "Tu sitio ahora está disponible en:"
print_status "🌐 https://$DOMAIN"
if [[ $INCLUDE_WWW == "y" || $INCLUDE_WWW == "Y" ]]; then
    print_status "🌐 https://www.$DOMAIN"
fi
echo ""
print_status "Para verificar el certificado SSL:"
print_status "openssl s_client -connect $DOMAIN:443 -servername $DOMAIN"
echo ""
print_status "Para ver logs:"
print_status "sudo tail -f /var/log/nginx/clinica_error.log"
print_status "docker-compose logs -f"
echo ""
print_warning "IMPORTANTE: Actualiza tu archivo .env con APP_URL=https://$DOMAIN"

# Crear script de mantenimiento
cat > ssl_maintenance.sh << 'EOFSCRIPT'
#!/bin/bash
# Script de mantenimiento SSL

case "$1" in
    "renew")
        echo "Renovando certificados..."
        certbot renew
        systemctl reload nginx
        ;;
    "status")
        echo "Estado de certificados:"
        certbot certificates
        ;;
    "test")
        echo "Probando renovación:"
        certbot renew --dry-run
        ;;
    "logs")
        echo "Logs de Nginx:"
        tail -f /var/log/nginx/clinica_error.log
        ;;
    *)
        echo "Uso: $0 {renew|status|test|logs}"
        echo "  renew  - Renovar certificados"
        echo "  status - Ver estado de certificados"
        echo "  test   - Probar renovación (dry-run)"
        echo "  logs   - Ver logs de Nginx"
        ;;
esac
EOFSCRIPT

chmod +x ssl_maintenance.sh
print_status "Script de mantenimiento creado: ./ssl_maintenance.sh"

echo ""
print_status "🔧 Para futuras actualizaciones del certificado:"
print_status "./ssl_maintenance.sh renew"
