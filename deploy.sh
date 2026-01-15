#!/bin/bash

# SMM Panel - Quick Deploy Script
# This script helps you quickly deploy the SMM Panel using Docker

set -e

echo "🐳 SMM Panel - Docker Deployment Script"
echo "========================================"
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo -e "${RED}❌ Docker is not installed. Please install Docker first.${NC}"
    exit 1
fi

# Check if Docker Compose is installed
if ! command -v docker-compose &> /dev/null; then
    echo -e "${RED}❌ Docker Compose is not installed. Please install Docker Compose first.${NC}"
    exit 1
fi

echo -e "${GREEN}✅ Docker and Docker Compose are installed${NC}"
echo ""

# Choose deployment mode
echo "Select deployment mode:"
echo "1) Development (with hot reload)"
echo "2) Production (optimized, with Redis)"
read -p "Enter your choice (1 or 2): " deploy_mode

# Set environment file
if [ ! -f .env ]; then
    echo -e "${YELLOW}⚠️  .env file not found. Creating from .env.example...${NC}"
    cp .env.example .env
    
    # Update database settings for Docker
    sed -i 's/DB_CONNECTION=sqlite/DB_CONNECTION=mysql/' .env
    sed -i 's/# DB_HOST=127.0.0.1/DB_HOST=db/' .env
    sed -i 's/# DB_PORT=3306/DB_PORT=3306/' .env
    sed -i 's/# DB_DATABASE=laravel/DB_DATABASE=smm_panel/' .env
    sed -i 's/# DB_USERNAME=root/DB_USERNAME=smm_user/' .env
    sed -i 's/# DB_PASSWORD=/DB_PASSWORD=secret123/' .env
    
    echo -e "${GREEN}✅ .env file created${NC}"
    echo -e "${YELLOW}⚠️  Please edit .env file and update DB_PASSWORD with a strong password${NC}"
    read -p "Press Enter to continue after updating .env..."
fi

# Select docker-compose file
if [ "$deploy_mode" = "2" ]; then
    COMPOSE_FILE="docker-compose.prod.yml"
    echo -e "${GREEN}📦 Using production configuration${NC}"
else
    COMPOSE_FILE="docker-compose.yml"
    echo -e "${GREEN}🔧 Using development configuration${NC}"
fi

# Build and start containers
echo ""
echo "🚀 Building and starting Docker containers..."
docker-compose -f $COMPOSE_FILE up -d --build

# Wait for database to be ready
echo ""
echo "⏳ Waiting for database to be ready..."
sleep 15

# Install dependencies and setup application
echo ""
echo "📦 Installing PHP dependencies..."
docker-compose -f $COMPOSE_FILE exec -T app composer install

echo ""
echo "📦 Installing Node.js dependencies..."
docker-compose -f $COMPOSE_FILE exec -T app npm install

# Generate application key
echo ""
echo "🔑 Generating application key..."
docker-compose -f $COMPOSE_FILE exec -T app php artisan key:generate

# Run migrations
echo ""
echo "🗄️  Running database migrations..."
docker-compose -f $COMPOSE_FILE exec -T app php artisan migrate --force

# Seed database
read -p "Do you want to seed the database with demo data? (y/n): " seed_db
if [ "$seed_db" = "y" ] || [ "$seed_db" = "Y" ]; then
    echo "🌱 Seeding database..."
    docker-compose -f $COMPOSE_FILE exec -T app php artisan db:seed --force
fi

# Build frontend assets
echo ""
echo "🎨 Building frontend assets..."
docker-compose -f $COMPOSE_FILE exec -T app npm run build

# Create storage link
echo ""
echo "🔗 Creating storage link..."
docker-compose -f $COMPOSE_FILE exec -T app php artisan storage:link

# Set permissions
echo ""
echo "🔐 Setting proper permissions..."
docker-compose -f $COMPOSE_FILE exec -T app chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Optimize for production
if [ "$deploy_mode" = "2" ]; then
    echo ""
    echo "⚡ Optimizing for production..."
    docker-compose -f $COMPOSE_FILE exec -T app php artisan config:cache
    docker-compose -f $COMPOSE_FILE exec -T app php artisan route:cache
    docker-compose -f $COMPOSE_FILE exec -T app php artisan view:cache
fi

# Display status
echo ""
echo "======================================"
echo -e "${GREEN}✅ Deployment completed successfully!${NC}"
echo "======================================"
echo ""
echo "📊 Container Status:"
docker-compose -f $COMPOSE_FILE ps
echo ""
echo "🌐 Access the application at: http://localhost:8945"
echo ""
echo "👤 Default Admin Credentials:"
echo "   Email: admin@smmpanel.com"
echo "   Password: password"
echo ""
echo "📝 Useful commands:"
echo "   View logs: docker-compose -f $COMPOSE_FILE logs -f"
echo "   Stop containers: docker-compose -f $COMPOSE_FILE down"
echo "   Restart: docker-compose -f $COMPOSE_FILE restart"
echo ""
echo -e "${YELLOW}⚠️  Remember to change default passwords in production!${NC}"
echo ""
