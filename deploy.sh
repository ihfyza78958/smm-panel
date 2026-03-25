#!/bin/bash

# SMM Panel Automated Deployment Script
# Run this script to easily pull updates and deploy them to production

echo "🚀 Starting Deployment Process..."

echo "📥 Pulling latest changes from Git..."
git pull origin main

echo "🏗️ Rebuilding and starting Docker containers..."
docker compose -f docker-compose.prod.yml up -d --build

echo "🔄 Running database migrations..."
docker exec smm-app php artisan migrate --force

echo "🧹 Clearing and rebuilding Laravel caches..."
docker exec smm-app php artisan optimize:clear
docker exec smm-app php artisan config:cache
docker exec smm-app php artisan event:cache
docker exec smm-app php artisan route:cache
docker exec smm-app php artisan view:cache

echo "✅ Deployment completed successfully! Panel is live."
