# Makefile para gestionar el entorno Docker de Laravel
.PHONY: help build up down restart logs shell php-shell mysql-shell composer artisan migrate seed fresh test clean

# Configuración por defecto
DOCKER_COMPOSE=docker-compose
PROJECT_NAME=clinica

# Ayuda
help: ## Muestra esta ayuda
	@echo "Comandos disponibles para el proyecto $(PROJECT_NAME):"
	@echo ""
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

# Construcción y gestión de contenedores
build: ## Construye las imágenes Docker
	$(DOCKER_COMPOSE) build --no-cache

up: ## Inicia todos los servicios
	$(DOCKER_COMPOSE) up -d

down: ## Detiene todos los servicios
	$(DOCKER_COMPOSE) down

restart: ## Reinicia todos los servicios
	$(DOCKER_COMPOSE) restart

stop: ## Detiene los servicios sin eliminar contenedores
	$(DOCKER_COMPOSE) stop

start: ## Inicia los servicios existentes
	$(DOCKER_COMPOSE) start

# Logs
logs: ## Muestra logs de todos los servicios
	$(DOCKER_COMPOSE) logs -f

logs-nginx: ## Muestra logs de Nginx
	$(DOCKER_COMPOSE) logs -f nginx

logs-php: ## Muestra logs de PHP-FPM
	$(DOCKER_COMPOSE) logs -f php

logs-mysql: ## Muestra logs de MySQL
	$(DOCKER_COMPOSE) logs -f mysql

# Shell access
shell: ## Accede al shell del contenedor PHP
	$(DOCKER_COMPOSE) exec php sh

php-shell: ## Accede al shell del contenedor PHP (alias)
	$(DOCKER_COMPOSE) exec php sh

mysql-shell: ## Accede al shell de MySQL
	$(DOCKER_COMPOSE) exec mysql mysql -u root -p clinica

nginx-shell: ## Accede al shell del contenedor Nginx
	$(DOCKER_COMPOSE) exec nginx sh

# Comandos Laravel
composer: ## Ejecuta comandos de Composer (ej: make composer install)
	$(DOCKER_COMPOSE) exec php composer $(filter-out $@,$(MAKECMDGOALS))

artisan: ## Ejecuta comandos de Artisan (ej: make artisan migrate)
	$(DOCKER_COMPOSE) exec php php artisan $(filter-out $@,$(MAKECMDGOALS))

migrate: ## Ejecuta las migraciones
	$(DOCKER_COMPOSE) exec php php artisan migrate

migrate-fresh: ## Ejecuta fresh migrations con seeds
	$(DOCKER_COMPOSE) exec php php artisan migrate:fresh --seed

seed: ## Ejecuta los seeders
	$(DOCKER_COMPOSE) exec php php artisan db:seed

rollback: ## Hace rollback de la última migración
	$(DOCKER_COMPOSE) exec php php artisan migrate:rollback

# Testing
test: ## Ejecuta los tests
	$(DOCKER_COMPOSE) exec php php artisan test

test-unit: ## Ejecuta solo tests unitarios
	$(DOCKER_COMPOSE) exec php php artisan test --testsuite=Unit

test-feature: ## Ejecuta solo tests de funcionalidad
	$(DOCKER_COMPOSE) exec php php artisan test --testsuite=Feature

# Optimización y limpieza
optimize: ## Optimiza la aplicación Laravel
	$(DOCKER_COMPOSE) exec php php artisan optimize

clear-cache: ## Limpia todas las cachés
	$(DOCKER_COMPOSE) exec php php artisan optimize:clear
	$(DOCKER_COMPOSE) exec php php artisan view:clear
	$(DOCKER_COMPOSE) exec php php artisan config:clear
	$(DOCKER_COMPOSE) exec php php artisan route:clear

# Instalación inicial
install: ## Instalación inicial del proyecto
	@echo "🚀 Iniciando instalación del proyecto..."
	$(DOCKER_COMPOSE) up -d --build
	@echo "⏳ Esperando que los servicios estén listos..."
	sleep 30
	$(DOCKER_COMPOSE) exec php composer install
	@echo "✅ Proyecto instalado correctamente!"
	@echo "🌐 Aplicación disponible en: http://localhost"
	@echo "🗄️ MySQL disponible en: localhost:3306 (usuario: root, contraseña: root2025)"

# Limpieza
clean: ## Limpia contenedores, imágenes y volúmenes no utilizados
	$(DOCKER_COMPOSE) down -v
	docker system prune -af

clean-all: ## Limpia todo incluyendo volúmenes de base de datos
	$(DOCKER_COMPOSE) down -v
	docker system prune -af --volumes

# Estado del sistema
status: ## Muestra el estado de los contenedores
	$(DOCKER_COMPOSE) ps

# Permite pasar argumentos a los comandos
%:
	@:
