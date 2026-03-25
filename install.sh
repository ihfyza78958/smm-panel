#!/bin/bash

# First-time Installation Script

echo "Welcome to the SMM Panel Installer!"

if [ ! -f .env ]; then
    echo "Creating .env file from .env.example..."
    cp .env.example .env
    echo "Please update your .env file with production credentials, then run this script again."
    exit 1
fi

echo "Starting up containers..."
docker compose -f docker-compose.prod.yml up -d --build

echo "Waiting for database to initialize (15s)..."
sleep 15

echo "Installing composer dependencies inside container..."
docker exec smm-app composer install --no-dev --optimize-autoloader

echo "Generating application key..."
docker exec smm-app php artisan key:generate

echo "Running migrations and seeding database..."
docker exec smm-app php artisan migrate:fresh --seed --force

echo "Linking storage..."
docker exec smm-app php artisan storage:link

echo "Building frontend assets..."
docker exec smm-app npm install
docker exec smm-app npm run build

echo "Caching configurations..."
docker exec smm-app php artisan optimize

echo "Installation Complete! You can now log in at your configured domain."
