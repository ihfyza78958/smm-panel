# SMM Panel - Quick Deploy Script for Windows
# This script helps you quickly deploy the SMM Panel using Docker

Write-Host "🐳 SMM Panel - Docker Deployment Script" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Check if Docker is installed
try {
    docker --version | Out-Null
} catch {
    Write-Host "❌ Docker is not installed. Please install Docker Desktop first." -ForegroundColor Red
    exit 1
}

# Check if Docker Compose is installed
try {
    docker-compose --version | Out-Null
} catch {
    Write-Host "❌ Docker Compose is not installed. Please install Docker Compose first." -ForegroundColor Red
    exit 1
}

Write-Host "✅ Docker and Docker Compose are installed" -ForegroundColor Green
Write-Host ""

# Choose deployment mode
Write-Host "Select deployment mode:"
Write-Host "1) Development (with hot reload)"
Write-Host "2) Production (optimized, with Redis)"
$deploy_mode = Read-Host "Enter your choice (1 or 2)"

# Set environment file
if (-not (Test-Path .env)) {
    Write-Host "⚠️  .env file not found. Creating from .env.example..." -ForegroundColor Yellow
    Copy-Item .env.example .env
    
    # Update database settings for Docker
    (Get-Content .env) -replace 'DB_CONNECTION=sqlite', 'DB_CONNECTION=mysql' |
    Set-Content .env
    
    (Get-Content .env) -replace '# DB_HOST=127.0.0.1', 'DB_HOST=db' |
    Set-Content .env
    
    (Get-Content .env) -replace '# DB_PORT=3306', 'DB_PORT=3306' |
    Set-Content .env
    
    (Get-Content .env) -replace '# DB_DATABASE=laravel', 'DB_DATABASE=smm_panel' |
    Set-Content .env
    
    (Get-Content .env) -replace '# DB_USERNAME=root', 'DB_USERNAME=smm_user' |
    Set-Content .env
    
    (Get-Content .env) -replace '# DB_PASSWORD=', 'DB_PASSWORD=secret123' |
    Set-Content .env
    
    Write-Host "✅ .env file created" -ForegroundColor Green
    Write-Host "⚠️  Please edit .env file and update DB_PASSWORD with a strong password" -ForegroundColor Yellow
    Read-Host "Press Enter to continue after updating .env"
}

# Select docker-compose file
if ($deploy_mode -eq "2") {
    $COMPOSE_FILE = "docker-compose.prod.yml"
    Write-Host "📦 Using production configuration" -ForegroundColor Green
} else {
    $COMPOSE_FILE = "docker-compose.yml"
    Write-Host "🔧 Using development configuration" -ForegroundColor Green
}

# Build and start containers
Write-Host ""
Write-Host "🚀 Building and starting Docker containers..." -ForegroundColor Cyan
docker-compose -f $COMPOSE_FILE up -d --build

# Wait for database to be ready
Write-Host ""
Write-Host "⏳ Waiting for database to be ready..." -ForegroundColor Yellow
Start-Sleep -Seconds 15

# Install dependencies and setup application
Write-Host ""
Write-Host "📦 Installing PHP dependencies..." -ForegroundColor Cyan
docker-compose -f $COMPOSE_FILE exec -T app composer install

Write-Host ""
Write-Host "📦 Installing Node.js dependencies..." -ForegroundColor Cyan
docker-compose -f $COMPOSE_FILE exec -T app npm install

# Generate application key
Write-Host ""
Write-Host "🔑 Generating application key..." -ForegroundColor Cyan
docker-compose -f $COMPOSE_FILE exec -T app php artisan key:generate

# Run migrations
Write-Host ""
Write-Host "🗄️  Running database migrations..." -ForegroundColor Cyan
docker-compose -f $COMPOSE_FILE exec -T app php artisan migrate --force

# Seed database
$seed_db = Read-Host "Do you want to seed the database with demo data? (y/n)"
if ($seed_db -eq "y" -or $seed_db -eq "Y") {
    Write-Host "🌱 Seeding database..." -ForegroundColor Cyan
    docker-compose -f $COMPOSE_FILE exec -T app php artisan db:seed --force
}

# Build frontend assets
Write-Host ""
Write-Host "🎨 Building frontend assets..." -ForegroundColor Cyan
docker-compose -f $COMPOSE_FILE exec -T app npm run build

# Create storage link
Write-Host ""
Write-Host "🔗 Creating storage link..." -ForegroundColor Cyan
docker-compose -f $COMPOSE_FILE exec -T app php artisan storage:link

# Set permissions
Write-Host ""
Write-Host "🔐 Setting proper permissions..." -ForegroundColor Cyan
docker-compose -f $COMPOSE_FILE exec -T app chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Optimize for production
if ($deploy_mode -eq "2") {
    Write-Host ""
    Write-Host "⚡ Optimizing for production..." -ForegroundColor Cyan
    docker-compose -f $COMPOSE_FILE exec -T app php artisan config:cache
    docker-compose -f $COMPOSE_FILE exec -T app php artisan route:cache
    docker-compose -f $COMPOSE_FILE exec -T app php artisan view:cache
}

# Display status
Write-Host ""
Write-Host "======================================" -ForegroundColor Green
Write-Host "✅ Deployment completed successfully!" -ForegroundColor Green
Write-Host "======================================" -ForegroundColor Green
Write-Host ""
Write-Host "📊 Container Status:" -ForegroundColor Cyan
docker-compose -f $COMPOSE_FILE ps
Write-Host ""
Write-Host "🌐 Access the application at: http://localhost:8945" -ForegroundColor Cyan
Write-Host ""
Write-Host "👤 Default Admin Credentials:" -ForegroundColor Yellow
Write-Host "   Email: admin@smmpanel.com"
Write-Host "   Password: password"
Write-Host ""
Write-Host "📝 Useful commands:" -ForegroundColor Cyan
Write-Host "   View logs: docker-compose -f $COMPOSE_FILE logs -f"
Write-Host "   Stop containers: docker-compose -f $COMPOSE_FILE down"
Write-Host "   Restart: docker-compose -f $COMPOSE_FILE restart"
Write-Host ""
Write-Host "⚠️  Remember to change default passwords in production!" -ForegroundColor Yellow
Write-Host ""
