#!/bin/bash

##################################
# SMM Panel - Simple Deployment
##################################

set -e

echo "🚀 SMM Panel Deployment"
echo "======================="
echo ""

# Check Docker
if ! command -v docker &> /dev/null; then
    echo "❌ Docker not installed!"
    echo "Install: curl -fsSL https://get.docker.com | sh"
    exit 1
fi

echo "✓ Docker found"

# Check .env
if [ ! -f .env ]; then
    if [ -f .env.production ]; then
        cp .env.production .env
        echo "⚠️  Created .env from template"
        echo "⚠️  Please edit .env and configure required values!"
        read -p "Press Enter after updating .env..."
    else
        echo "❌ .env.production not found!"
        exit 1
    fi
fi

echo "✓ Environment configured"

# Build and start
echo ""
echo "🐳 Starting Docker containers..."
docker-compose -f docker-compose.prod.yml up -d --build

echo ""
echo "⏳ Waiting for services..."
sleep 20

# Install dependencies
echo ""
echo "📦 Installing dependencies..."
docker-compose -f docker-compose.prod.yml exec -T app composer install --no-interaction --no-dev --optimize-autoloader || true

# Setup app
echo ""
echo "🔧 Setting up application..."

# Generate key if needed
if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
    docker-compose -f docker-compose.prod.yml exec -T app php artisan key:generate --force
fi

# Run migrations
docker-compose -f docker-compose.prod.yml exec -T app php artisan migrate --force

# Seed database (optional)
read -p "Seed database with demo data? (y/N): " seed
if [[ $seed =~ ^[Yy]$ ]]; then
    docker-compose -f docker-compose.prod.yml exec -T app php artisan db:seed --force
fi

# Optimize
docker-compose -f docker-compose.prod.yml exec -T app php artisan optimize

# Permissions
docker-compose -f docker-compose.prod.yml exec -T app chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

echo ""
echo "✅ Deployment Complete!"
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Access: http://localhost:8945"
echo "Admin: admin@smmpanel.com / password"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "⚠️  IMPORTANT: Change admin password!"
echo ""
echo "Commands:"
echo "  Logs:    docker-compose -f docker-compose.prod.yml logs -f"
echo "  Restart: docker-compose -f docker-compose.prod.yml restart"
echo "  Stop:    docker-compose -f docker-compose.prod.yml down"
echo ""
