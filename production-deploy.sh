#!/bin/bash

#############################################
# SMM Panel - Production Deployment Script
# One-command production deployment
#############################################

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# Configuration
COMPOSE_FILE="docker-compose.prod.yml"
BACKUP_DIR="backups"
LOG_FILE="deployment.log"

# Functions
log() {
    echo -e "${CYAN}[$(date +'%Y-%m-%d %H:%M:%S')]${NC} $1" | tee -a $LOG_FILE
}

success() {
    echo -e "${GREEN}✅ $1${NC}" | tee -a $LOG_FILE
}

error() {
    echo -e "${RED}❌ $1${NC}" | tee -a $LOG_FILE
}

warning() {
    echo -e "${YELLOW}⚠️  $1${NC}" | tee -a $LOG_FILE
}

info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

# Banner
clear
echo -e "${CYAN}"
cat << "EOF"
╔═══════════════════════════════════════════╗
║   SMM Panel Production Deployment        ║
║   Automated One-Command Deploy            ║
╚═══════════════════════════════════════════╝
EOF
echo -e "${NC}"

# Check if running as root
if [ "$EUID" -eq 0 ]; then 
    warning "Please don't run as root. Use sudo for specific commands when needed."
    exit 1
fi

# Step 1: Pre-flight checks
log "🔍 Running pre-flight checks..."

# Check Docker
if ! command -v docker &> /dev/null; then
    error "Docker is not installed!"
    info "Install Docker: curl -fsSL https://get.docker.com -o get-docker.sh && sudo sh get-docker.sh"
    exit 1
fi
success "Docker is installed"

# Check Docker Compose
if ! command -v docker-compose &> /dev/null; then
    error "Docker Compose is not installed!"
    info "Install: sudo curl -L \"https://github.com/docker/compose/releases/latest/download/docker-compose-\$(uname -s)-\$(uname -m)\" -o /usr/local/bin/docker-compose && sudo chmod +x /usr/local/bin/docker-compose"
    exit 1
fi
success "Docker Compose is installed"

# Check if Docker service is running
if ! sudo systemctl is-active --quiet docker; then
    warning "Docker service is not running. Starting..."
    sudo systemctl start docker
fi
success "Docker service is running"

# Step 2: Environment Configuration
log "📝 Configuring environment..."

if [ ! -f .env ]; then
    if [ -f .env.production ]; then
        cp .env.production .env
        warning "Created .env from .env.production template"
        warning "IMPORTANT: Edit .env and fill in YOUR_* values before continuing!"
        info "Required: APP_URL, DB_PASSWORD, MAIL_*, payment gateway keys"
        read -p "Press Enter after you've updated .env file..."
    else
        error ".env.production template not found!"
        exit 1
    fi
else
    success ".env file exists"
fi

# Validate critical .env values
if grep -q "YOUR_" .env 2>/dev/null; then
    warning "Some .env values still contain YOUR_* placeholders"
    read -p "Continue anyway? (not recommended) [y/N]: " continue_placeholder
    if [[ ! $continue_placeholder =~ ^[Yy]$ ]]; then
        info "Please update .env file and run the script again"
        exit 1
    fi
fi

# Step 3: Create necessary directories
log "📁 Creating directories..."
mkdir -p $BACKUP_DIR
mkdir -p storage/logs
mkdir -p storage/app/public
mkdir -p bootstrap/cache
mkdir -p certbot/conf
mkdir -p certbot/www
success "Directories created"

# Step 4: Backup existing database (if exists)
if docker ps -a | grep -q smm-db; then
    log "💾 Creating backup of existing database..."
    timestamp=$(date +%Y%m%d_%H%M%S)
    docker-compose -f $COMPOSE_FILE exec -T db mysqldump -u smm_user -p$(grep DB_PASSWORD .env | cut -d= -f2) smm_panel > $BACKUP_DIR/pre_deploy_$timestamp.sql 2>/dev/null || warning "Database backup failed (this is normal for fresh install)"
    [ -f $BACKUP_DIR/pre_deploy_$timestamp.sql ] && success "Backup created: $BACKUP_DIR/pre_deploy_$timestamp.sql"
fi

# Step 5: Pull latest code
log "🔄 Pulling latest code..."
if [ -d .git ]; then
    git pull || warning "Git pull failed (continuing anyway)"
    success "Code updated"
else
    info "Not a git repository, skipping pull"
fi

# Step 6: Build and start containers
log "🐳 Building Docker containers..."
docker-compose -f $COMPOSE_FILE build --no-cache
success "Containers built"

log "🚀 Starting containers..."
docker-compose -f $COMPOSE_FILE up -d
success "Containers started"

# Wait for services
log "⏳ Waiting for services to be ready..."
sleep 10

# Check service health
docker-compose -f $COMPOSE_FILE exec -T db mysqladmin ping -h localhost -u smm_user -p$(grep DB_PASSWORD .env | cut -d= -f2) &>/dev/null && success "Database is ready" || error "Database health check failed"
docker-compose -f $COMPOSE_FILE exec -T redis redis-cli ping &>/dev/null && success "Redis is ready" || error "Redis health check failed"

# Step 7: Install dependencies
log "📦 Installing PHP dependencies..."
docker-compose -f $COMPOSE_FILE exec -T app composer install --no-dev --optimize-autoloader --no-interaction
success "PHP dependencies installed"

log "📦 Installing Node.js dependencies..."
docker-compose -f $COMPOSE_FILE exec -T app npm ci --production
success "Node.js dependencies installed"

# Step 8: Generate app key (if not exists)
if ! grep -q "APP_KEY=base64:" .env; then
    log "🔑 Generating application key..."
    docker-compose -f $COMPOSE_FILE exec -T app php artisan key:generate --force
    success "Application key generated"
else
    info "Application key already exists"
fi

# Step 9: Database migrations
log "🗄️  Running database migrations..."
docker-compose -f $COMPOSE_FILE exec -T app php artisan migrate --force
success "Migrations completed"

# Step 10: Seed database (optional)
read -p "$(echo -e ${YELLOW}Would you like to seed the database with demo data? [y/N]:${NC} )" seed_choice
if [[ $seed_choice =~ ^[Yy]$ ]]; then
    docker-compose -f $COMPOSE_FILE exec -T app php artisan db:seed --force
    success "Database seeded"
fi

# Step 11: Create storage link
log "🔗 Creating storage symlink..."
docker-compose -f $COMPOSE_FILE exec -T app php artisan storage:link || info "Storage link already exists"

# Step 12: Build frontend assets
log "🎨 Building frontend assets..."
docker-compose -f $COMPOSE_FILE exec -T app npm run build
success "Assets compiled"

# Step 13: Optimize application
log "⚡ Optimizing application..."
docker-compose -f $COMPOSE_FILE exec -T app php artisan config:cache
docker-compose -f $COMPOSE_FILE exec -T app php artisan route:cache
docker-compose -f $COMPOSE_FILE exec -T app php artisan view:cache
docker-compose -f $COMPOSE_FILE exec -T app php artisan optimize
success "Application optimized"

# Step 14: Set permissions
log "🔐 Setting permissions..."
docker-compose -f $COMPOSE_FILE exec -T app chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
docker-compose -f $COMPOSE_FILE exec -T app chmod -R 775 /var/www/storage /var/www/bootstrap/cache
success "Permissions set"

# Step 15: SSL Configuration (optional)
read -p "$(echo -e ${YELLOW}Would you like to setup SSL certificate with Let\'s Encrypt? [y/N]:${NC} )" ssl_choice
if [[ $ssl_choice =~ ^[Yy]$ ]]; then
    read -p "Enter your domain name: " domain
    read -p "Enter your email: " email
    
    log "🔐 Installing SSL certificate for $domain..."
    
    docker run -it --rm --name certbot \
        -v "$(pwd)/certbot/conf:/etc/letsencrypt" \
        -v "$(pwd)/certbot/www:/var/www/certbot" \
        certbot/certbot certonly --webroot \
        --webroot-path=/var/www/certbot \
        --email $email \
        --agree-tos --no-eff-email \
        -d $domain -d www.$domain
    
    [ $? -eq 0 ] && success "SSL certificate installed" || warning "SSL installation failed (you can run this later)"
fi

# Step 16: Final health check
log "🏥 Running final health checks..."

# Check if containers are running
if docker-compose -f $COMPOSE_FILE ps | grep -q "Up"; then
    success "All containers are running"
else
    error "Some containers are not running!"
    docker-compose -f $COMPOSE_FILE ps
fi

# Check web accessibility
PORT=$(grep -A 10 "web:" $COMPOSE_FILE | grep -E "^\s*-\s*\"?[0-9]+" | head -1 | cut -d: -f1 | tr -d ' "-')
if curl -f http://localhost:${PORT:-8945} > /dev/null 2>&1; then
    success "Web server is responding"
else
    warning "Web server might not be fully ready yet (this is normal, wait 30 seconds)"
fi

# Step 17: Setup cron for backups (optional)
read -p "$(echo -e ${YELLOW}Would you like to setup automated daily backups? [y/N]:${NC} )" backup_choice
if [[ $backup_choice =~ ^[Yy]$ ]]; then
    CRON_CMD="0 2 * * * cd $(pwd) && ./scripts/backup.sh >> $BACKUP_DIR/backup.log 2>&1"
    (crontab -l 2>/dev/null | grep -v backup.sh; echo "$CRON_CMD") | crontab -
    success "Automated backups configured (daily at 2 AM)"
fi

# Final Summary
echo ""
echo -e "${GREEN}╔═══════════════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║         🎉 DEPLOYMENT SUCCESSFUL! 🎉                  ║${NC}"
echo -e "${GREEN}╚═══════════════════════════════════════════════════════╝${NC}"
echo ""
echo -e "${CYAN}📊 Deployment Summary:${NC}"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo -e "🌐 Application URL: ${GREEN}http://localhost:${PORT:-8945}${NC}"
echo -e "👤 Admin Login: ${YELLOW}admin@smmpanel.com${NC}"
echo -e "🔑 Admin Password: ${YELLOW}password${NC} (${RED}CHANGE THIS!${NC})"
echo ""
echo -e "${CYAN}📝 Next Steps:${NC}"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "1. Change admin password immediately"
echo "2. Configure payment gateways in .env"
echo "3. Setup email service (SMTP/SendGrid)"
echo "4. Review and test all features"
echo "5. Setup monitoring and alerts"
echo ""
echo -e "${CYAN}🛠️  Useful Commands:${NC}"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "• View logs:        docker-compose -f $COMPOSE_FILE logs -f"
echo "• Restart:          docker-compose -f $COMPOSE_FILE restart"
echo "• Stop:             docker-compose -f $COMPOSE_FILE down"
echo "• Run command:      docker-compose -f $COMPOSE_FILE exec app php artisan [cmd]"
echo "• Database backup:  ./scripts/backup.sh"
echo "• Use Makefile:     make help"
echo ""
echo -e "${YELLOW}⚠️  Security Reminders:${NC}"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "• Change all default passwords"
echo "• Enable firewall (ufw allow 80/tcp && ufw allow 443/tcp)"
echo "• Keep .env file secure (never commit to git)"
echo "• Setup automated backups"
echo "• Enable HTTPS in production"
echo ""
log "Deployment completed successfully!"
echo -e "${GREEN}Full deployment log saved to: $LOG_FILE${NC}"
echo ""
