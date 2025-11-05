#!/bin/bash

# SCRIPT DE INSTALACIÓN LIMPIA - SOLO IP
# ======================================
# Este script automatiza la instalación inicial del proyecto
# Resultado: Aplicación funcionando en http://IP_DEL_SERVIDOR

set -e

echo "🚀 INSTALACIÓN LIMPIA DEL PROYECTO CLÍNICA"
echo "=========================================="

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

# Verificar que se ejecute como usuario normal (no root)
if [[ $EUID -eq 0 ]]; then
   print_error "No ejecutes este script como root. Usa tu usuario normal."
   exit 1
fi

# Verificar Ubuntu
if ! grep -q "Ubuntu 22.04" /etc/os-release 2>/dev/null; then
    print_warning "Este script está optimizado para Ubuntu 22.04"
    read -p "¿Continuar de todas formas? (y/n): " continue_anyway
    if [[ $continue_anyway != "y" && $continue_anyway != "Y" ]]; then
        exit 1
    fi
fi

print_step "Verificando permisos sudo..."
sudo -v

print_step "Actualizando sistema..."
sudo apt update && sudo apt upgrade -y

print_step "Instalando dependencias básicas..."
sudo apt install -y apt-transport-https ca-certificates curl gnupg lsb-release git

print_step "Configurando repositorio Docker..."
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg
echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

print_step "Instalando Docker..."
sudo apt update
sudo apt install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin

print_step "Instalando Docker Compose standalone..."
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

print_step "Configurando usuario Docker..."
sudo usermod -aG docker $USER

print_step "Configurando firewall básico..."
sudo apt install -y ufw
sudo ufw --force enable
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 3306/tcp

print_step "Creando directorio del proyecto..."
sudo mkdir -p /var/www
cd /var/www

# Solicitar URL del repositorio
echo ""
read -p "Ingresa la URL del repositorio Git (ejemplo: git@github.com:Bengy1998/clinica-back.git): " REPO_URL

if [[ -z "$REPO_URL" ]]; then
    print_error "URL del repositorio es requerida"
    exit 1
fi

print_step "Clonando proyecto..."
sudo git clone "$REPO_URL" clinica 2>/dev/null || {
    print_warning "El directorio clinica ya existe. ¿Quieres eliminarlo y volver a clonar?"
    read -p "(y/n): " recreate
    if [[ $recreate == "y" || $recreate == "Y" ]]; then
        sudo rm -rf clinica
        sudo git clone "$REPO_URL" clinica
    fi
}

print_step "Configurando permisos del proyecto..."
sudo chown -R $USER:$USER clinica
cd clinica

# Verificar que existe docker-compose.yml
if [ ! -f "docker-compose.yml" ]; then
    print_error "No se encontró docker-compose.yml en el proyecto"
    print_error "Verifica que clonaste el repositorio correcto"
    exit 1
fi

print_step "Configurando variables de entorno..."
if [ ! -f ".env" ]; then
    if [ -f ".env.example" ]; then
        cp .env.example .env
        print_step "Archivo .env creado desde .env.example"
    else
        print_step "Creando archivo .env básico..."
        cat > .env << EOF
APP_NAME="Clínica"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://$(curl -s -4 icanhazip.com 2>/dev/null || echo "localhost")

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=clinica
DB_USERNAME=root
DB_PASSWORD=root2025

CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
EOF
    fi
else
    print_step "Archivo .env ya existe, no se modificará"
fi

print_step "IMPORTANTE: Se requiere reiniciar para aplicar cambios de Docker"
print_warning "El servidor se reiniciará en 10 segundos. Presiona Ctrl+C para cancelar."

echo ""
echo "🔄 DESPUÉS DEL REINICIO, EJECUTA:"
echo "=================================="
echo "cd /var/www/clinica"
echo "chmod +x post_reboot_setup.sh"
echo "./post_reboot_setup.sh"
echo ""

sleep 10
sudo reboot
