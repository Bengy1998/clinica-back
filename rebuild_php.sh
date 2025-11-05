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

# Verificar logs
echo "Verificando logs de PHP..."
sleep 5
docker-compose logs -f php
