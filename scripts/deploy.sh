#!/bin/bash

##################################
# SMM Panel - One-Click Deployment
##################################

set -e

# Parse flags
SEED=false
for arg in "$@"; do
    case $arg in
        --seed) SEED=true ;;
        --help|-h)
            echo "Usage: ./scripts/deploy.sh [OPTIONS]"
            echo ""
            echo "Options:"
            echo "  --seed    Seed database with demo data after deployment"
            echo "  --help    Show this help message"
            exit 0
            ;;
    esac
done

echo "🚀 SMM Panel - One-Click Deployment"
echo "====================================="
echo ""

# Detect docker compose command (v2 plugin or v1 standalone)
if docker compose version &>/dev/null; then
    DC="docker compose"
elif command -v docker-compose &>/dev/null; then
    DC="docker-compose"
else
    echo "❌ Docker Compose not found!"
    echo "Install: https://docs.docker.com/compose/install/"
    exit 1
fi

# Check Docker
if ! command -v docker &>/dev/null; then
    echo "❌ Docker not installed!"
    echo "Install: curl -fsSL https://get.docker.com | sh"
    exit 1
fi

echo "✓ Docker found"
echo "✓ Using: $DC"

# Setup .env if missing
if [ ! -f .env ]; then
    if [ -f .env.production ]; then
        cp .env.production .env
        echo "⚠️  Created .env from .env.production template"
        echo "⚠️  IMPORTANT: Edit .env and set your passwords/domain before going live!"
    elif [ -f .env.example ]; then
        cp .env.example .env
        echo "⚠️  Created .env from .env.example template"
        echo "⚠️  IMPORTANT: Edit .env and configure database/Redis settings!"
    else
        echo "❌ No .env template found! Create .env manually."
        exit 1
    fi
fi

echo "✓ Environment configured"

# Build and start all services
echo ""
echo "🐳 Building and starting Docker containers..."
$DC -f docker-compose.prod.yml up -d --build

# Wait for app container to be healthy
echo ""
echo "⏳ Waiting for application to be ready..."
TIMEOUT=120
ELAPSED=0
while [ $ELAPSED -lt $TIMEOUT ]; do
    if $DC -f docker-compose.prod.yml exec -T app php artisan --version &>/dev/null; then
        echo "✅ Application is ready!"
        break
    fi
    sleep 3
    ELAPSED=$((ELAPSED + 3))
    echo "  Waiting... (${ELAPSED}s / ${TIMEOUT}s)"
done

if [ $ELAPSED -ge $TIMEOUT ]; then
    echo "❌ Application did not start within ${TIMEOUT}s"
    echo "Check logs: $DC -f docker-compose.prod.yml logs app"
    exit 1
fi

# Seed database if requested
if [ "$SEED" = true ]; then
    echo ""
    echo "🌱 Seeding database..."
    $DC -f docker-compose.prod.yml exec -T app php artisan db:seed --force --no-interaction
fi

echo ""
echo "✅ Deployment Complete!"
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Access: http://localhost:8945"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "If you seeded: admin@smmpanel.com / password"
echo ""
echo "⚠️  IMPORTANT: Change admin password after first login!"
echo ""
echo "Commands:"
echo "  Logs:    $DC -f docker-compose.prod.yml logs -f"
echo "  Restart: $DC -f docker-compose.prod.yml restart"
echo "  Stop:    $DC -f docker-compose.prod.yml down"
echo "  Seed:    $DC -f docker-compose.prod.yml exec app php artisan db:seed --force"
echo "  Health:  ./scripts/health-check.sh"
echo ""
