#!/bin/bash

# SCRIPT DE RECONSTRUCCIÓN RÁPIDA PARA TESTING
# ============================================

echo "🔄 Reconstruyendo contenedor PHP con cambios..."

# Parar contenedores
echo "Parando contenedores..."
docker-compose down

# Eliminar contenedor PHP específicamente
echo "Eliminando contenedor PHP anterior..."
docker container rm -f clinica_php 2>/dev/null || true

# Reconstruir solo PHP sin caché
echo "Reconstruyendo PHP sin caché..."
docker-compose build --no-cache php

# Iniciar de nuevo
echo "Iniciando contenedores..."
docker-compose up -d

# Esperar y verificar
echo "Esperando 10 segundos..."
sleep 10

echo "Estado de contenedores:"
docker-compose ps

echo ""
echo "Logs de PHP (últimas 20 líneas):"
docker-compose logs --tail=20 php

echo ""
echo "¿Todo funcionó? Presiona Ctrl+C para salir o Enter para ver logs en vivo..."
read -r
docker-compose logs -f php
