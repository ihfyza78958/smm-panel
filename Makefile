# SMM Panel - Production Management Commands
# Usage: make [command]

.PHONY: help install start stop restart logs deploy backup restore clean optimize update

# Docker Compose file selection
COMPOSE_FILE ?= docker-compose.yml
PROD_COMPOSE = docker-compose.prod.yml

help: ## Show this help message
	@echo "SMM Panel - Management Commands"
	@echo "================================"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

# Development Commands
install: ## Install dependencies (development)
	docker-compose exec app composer install
	docker-compose exec app npm install

start: ## Start development containers
	docker-compose up -d
	@echo "✅ Application started at http://localhost:8945"

stop: ## Stop all containers
	docker-compose down

restart: ## Restart all containers
	docker-compose restart

logs: ## View application logs
	docker-compose logs -f app

shell: ## Access application shell
	docker-compose exec app bash

db-shell: ## Access database shell
	docker-compose exec db mysql -u smm_user -p

# Production Commands
prod-deploy: ## Deploy to production
	@echo "🚀 Deploying to production..."
	docker-compose -f $(PROD_COMPOSE) up -d --build
	@echo "📦 Installing dependencies..."
	docker-compose -f $(PROD_COMPOSE) exec -T app composer install --no-dev --optimize-autoloader
	@echo "🗄️ Running migrations..."
	docker-compose -f $(PROD_COMPOSE) exec -T app php artisan migrate --force
	@echo "⚡ Optimizing..."
	docker-compose -f $(PROD_COMPOSE) exec -T app php artisan optimize
	@echo "🎨 Building assets..."
	docker-compose -f $(PROD_COMPOSE) exec -T app npm run build
	@echo "✅ Production deployment complete!"

prod-start: ## Start production containers
	docker-compose -f $(PROD_COMPOSE) up -d

prod-stop: ## Stop production containers
	docker-compose -f $(PROD_COMPOSE) down

prod-logs: ## View production logs
	docker-compose -f $(PROD_COMPOSE) logs -f

prod-restart: ## Restart production containers
	docker-compose -f $(PROD_COMPOSE) restart

# Database Commands
db-backup: ## Backup database
	@mkdir -p backups
	@echo "📦 Creating database backup..."
	docker-compose exec -T db mysqldump -u smm_user -psmm_panel > backups/backup_$$(date +%Y%m%d_%H%M%S).sql
	@echo "✅ Backup created in backups/ directory"

db-restore: ## Restore database (use: make db-restore FILE=backup.sql)
	@if [ -z "$(FILE)" ]; then echo "❌ Please specify FILE=backup.sql"; exit 1; fi
	@echo "📥 Restoring database from $(FILE)..."
	docker-compose exec -T db mysql -u smm_user -p smm_panel < $(FILE)
	@echo "✅ Database restored"

migrate: ## Run database migrations
	docker-compose exec app php artisan migrate

migrate-fresh: ## Fresh migrations (WARNING: destroys data)
	@read -p "⚠️  This will destroy all data. Continue? [y/N] " -n 1 -r; \
	echo; \
	if [[ $$REPLY =~ ^[Yy]$$ ]]; then \
		docker-compose exec app php artisan migrate:fresh --seed; \
	fi

seed: ## Seed database with demo data
	docker-compose exec app php artisan db:seed

# Cache Commands
cache-clear: ## Clear all caches
	docker-compose exec app php artisan cache:clear
	docker-compose exec app php artisan config:clear
	docker-compose exec app php artisan route:clear
	docker-compose exec app php artisan view:clear

optimize: ## Optimize for production
	docker-compose exec app php artisan config:cache
	docker-compose exec app php artisan route:cache
	docker-compose exec app php artisan view:cache
	docker-compose exec app php artisan optimize

# Asset Commands
assets-dev: ## Build development assets
	docker-compose exec app npm run dev

assets-build: ## Build production assets
	docker-compose exec app npm run build

assets-watch: ## Watch and rebuild assets
	docker-compose exec app npm run dev -- --watch

# Update Commands
update: ## Update application code
	git pull
	docker-compose exec app composer install
	docker-compose exec app php artisan migrate --force
	docker-compose exec app npm install
	docker-compose exec app npm run build
	docker-compose exec app php artisan optimize
	docker-compose restart app

composer-update: ## Update PHP dependencies
	docker-compose exec app composer update

npm-update: ## Update Node dependencies
	docker-compose exec app npm update

# Maintenance Commands
clean: ## Clean temporary files and caches
	docker-compose exec app php artisan cache:clear
	docker-compose exec app php artisan view:clear
	docker-compose exec app rm -rf storage/logs/*.log

permissions: ## Fix storage permissions
	docker-compose exec app chown -R www-data:www-data /var/www/storage
	docker-compose exec app chown -R www-data:www-data /var/www/bootstrap/cache
	docker-compose exec app chmod -R 775 /var/www/storage
	docker-compose exec app chmod -R 775 /var/www/bootstrap/cache

# Testing Commands
test: ## Run tests
	docker-compose exec app php artisan test

test-coverage: ## Run tests with coverage
	docker-compose exec app php artisan test --coverage

# System Commands
status: ## Show container status
	docker-compose ps

stats: ## Show container resource usage
	docker stats

prune: ## Clean up Docker system
	docker system prune -af
	docker volume prune -f

# SSL Commands (for production with certbot)
ssl-install: ## Install SSL certificate
	@echo "🔐 Installing SSL certificate..."
	docker run -it --rm --name certbot \
		-v "$$(pwd)/certbot/conf:/etc/letsencrypt" \
		-v "$$(pwd)/certbot/www:/var/www/certbot" \
		certbot/certbot certonly --webroot \
		--webroot-path=/var/www/certbot \
		--email YOUR_EMAIL@example.com \
		--agree-tos --no-eff-email \
		-d YOUR_DOMAIN.com -d www.YOUR_DOMAIN.com

ssl-renew: ## Renew SSL certificate
	docker run -it --rm --name certbot \
		-v "$$(pwd)/certbot/conf:/etc/letsencrypt" \
		-v "$$(pwd)/certbot/www:/var/www/certbot" \
		certbot/certbot renew

# Monitoring Commands
monitor-logs: ## Monitor all logs
	docker-compose logs -f

monitor-errors: ## Monitor error logs only
	docker-compose exec app tail -f storage/logs/laravel.log

health-check: ## Check service health
	@echo "Checking services health..."
	@curl -f http://localhost:8945 > /dev/null 2>&1 && echo "✅ Web: OK" || echo "❌ Web: FAILED"
	@docker-compose exec -T db mysqladmin ping -h localhost -u smm_user -p 2>&1 | grep -q "mysqld is alive" && echo "✅ Database: OK" || echo "❌ Database: FAILED"
	@docker-compose exec -T redis redis-cli ping 2>&1 | grep -q "PONG" && echo "✅ Redis: OK" || echo "❌ Redis: FAILED"
