# 🚀 SMM Panel - Production Deployment Guide

[![Docker](https://img.shields.io/badge/Docker-Ready-blue.svg)](https://www.docker.com/)
[![Production](https://img.shields.io/badge/Production-Ready-green.svg)]()
[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com/)

**One-Command Production Deployment** for your SMM Panel application. Deploy to any server with Docker in minutes!

---

## ⚡ Quick Start - Production Deployment

### Prerequisites
- Ubuntu 20.04+ / Debian 10+ / CentOS 8+
- 2GB RAM minimum (4GB recommended)
- Docker & Docker Compose installed
- Domain name (optional, for SSL)

### One-Command Deploy

```bash
# Clone repository
git clone <your-repo-url>
cd smm-panel

# Make deploy script executable
chmod +x production-deploy.sh scripts/*.sh

# Run production deployment
./production-deploy.sh
```

That's it! The script will:
- ✅ Check system requirements
- ✅ Configure environment
- ✅ Build Docker containers  
- ✅ Install dependencies
- ✅ Run migrations
- ✅ Build assets
- ✅ Setup SSL (optional)
- ✅ Configure backups (optional)

**Access:** `http://your-server-ip:8945`  
**Admin:** `admin@smmpanel.com` / `password`

---

## 📋 Manual Installation

### Step 1: Install Docker

```bash
# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Verify installation
docker --version
docker-compose --version
```

### Step 2: Configure Environment

```bash
# Copy production environment template
cp .env.production .env

# Edit configuration
nano .env
```

**Required configurations:**
- `APP_URL` - Your domain (e.g., https://yourdomain.com)
- `DB_PASSWORD` - Strong database password
- `MAIL_*` - Email service credentials
- Payment gateway keys (eSewa, Khalti)

### Step 3: Deploy

```bash
# Using Make (Recommended)
make prod-deploy

# OR using Docker Compose directly
docker-compose -f docker-compose.prod.yml up -d --build
docker-compose -f docker-compose.prod.yml exec app composer install --no-dev
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
docker-compose -f docker-compose.prod.yml exec app npm run build
docker-compose -f docker-compose.prod.yml exec app php artisan optimize
```

---

## 🛠️ Management Commands

### Using Makefile

```bash
# Production deployment
make prod-deploy

# View logs
make prod-logs

# Restart services
make prod-restart

# Database backup
make db-backup

# Update application
make update

# Health check
make health-check

# View all commands
make help
```

### Using Scripts

```bash
# Backup database & files
./scripts/backup.sh

# Restore from backup
./scripts/restore.sh

# Health monitoring
./scripts/health-check.sh
```

---

## 🔐 SSL Setup (HTTPS)

### Automatic SSL with Let's Encrypt

During deployment, select "yes" when prompted for SSL setup, or run manually:

```bash
# Install SSL certificate
make ssl-install
# Follow prompts to enter domain and email

# Auto-renew (add to crontab)
0 0 1 * * cd /path/to/smm-panel && make ssl-renew
```

### Manual Nginx Configuration

See [DOCKER_DEPLOYMENT.md](DOCKER_DEPLOYMENT.md#ssl-configuration) for detailed SSL setup.

---

## 💾 Backup & Restore

### Automated Daily Backups

During deployment, select "yes" for automated backups to schedule daily backups at 2 AM.

Or setup manually:

```bash
# Add to crontab
crontab -e

# Add this line:
0 2 * * * cd /path/to/smm-panel && ./scripts/backup.sh >> backups/backup.log 2>&1
```

### Manual Backup

```bash
# Backup database & files
./scripts/backup.sh

# Backups saved to: backups/database/ and backups/files/
```

### Restore from Backup

```bash
# Interactive restore
./scripts/restore.sh

# Select backup from list, confirm, and restore
```

---

## 📊 Monitoring & Health Checks

### Manual Health Check

```bash
./scripts/health-check.sh
```

Checks:
- Container status
- Web server response
- Database connectivity  
- Redis connectivity
- Disk space usage
- Application errors
- Queue workers
- SSL certificate expiry

### Automated Monitoring

Setup cron for periodic health checks:

```bash
# Check every 5 minutes
*/5 * * * * cd /path/to/smm-panel && ./scripts/health-check.sh
```

### Log Monitoring

```bash
# Application logs
docker-compose -f docker-compose.prod.yml logs -f app

# Database logs
docker-compose -f docker-compose.prod.yml logs -f db

# All logs
make prod-logs
```

---

## 🔧 Common Operations

### Update Application

```bash
# Pull latest code and update
make update

# OR manually:
git pull
docker-compose -f docker-compose.prod.yml exec app composer install --no-dev
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
docker-compose -f docker-compose.prod.yml exec app npm run build
docker-compose -f docker-compose.prod.yml exec app php artisan optimize
docker-compose -f docker-compose.prod.yml restart
```

### Clear Caches

```bash
make cache-clear

# OR:
docker-compose -f docker-compose.prod.yml exec app php artisan cache:clear
docker-compose -f docker-compose.prod.yml exec app php artisan config:clear
```

### Fix Permissions

```bash
make permissions

# OR:
docker-compose -f docker-compose.prod.yml exec app chown -R www-data:www-data /var/www/storage
```

### Access Container Shell

```bash
docker-compose -f docker-compose.prod.yml exec app bash
```

---

## 🌐 Server Configuration

### Firewall Setup

```bash
# Allow HTTP & HTTPS
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw allow 22/tcp
sudo ufw enable
```

### Using Reverse Proxy (Nginx/Apache)

If you have existing web server, proxy to port 8945:

**Nginx example:**
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    
    location / {
        proxy_pass http://localhost:8945;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
    }
}
```

---

## 🚨 Troubleshooting

### Containers won't start

```bash
# Check logs
docker-compose -f docker-compose.prod.yml logs

# Rebuild without cache
docker-compose -f docker-compose.prod.yml build --no-cache
docker-compose -f docker-compose.prod.yml up -d
```

### Database connection errors

```bash
# Check database is running
docker-compose -f docker-compose.prod.yml ps db

# Check .env DB_HOST=db (not localhost)

# Restart database
docker-compose -f docker-compose.prod.yml restart db
```

### Permission denied errors

```bash
make permissions
```

### Out of disk space

```bash
# Clean Docker
docker system prune -af
docker volume prune -f

# Clean old backups
find backups -mtime +30 -delete
```

---

## 📁 Project Structure

```
smm-panel/
├── production-deploy.sh     # One-command deployment
├── docker-compose.prod.yml  # Production Docker config
├── .env.production          # Production env template
├── Makefile                 # Management commands
├── scripts/
│   ├── backup.sh           # Automated backup
│   ├── restore.sh          # Database restore
│   └── health-check.sh     # Health monitoring
├── backups/                # Backup storage
├── docker/
│   ├── nginx/              # Nginx config
│   └── mysql/              # MySQL config
└── DOCKER_DEPLOYMENT.md    # Detailed docs
```

---

## 🔐 Security Checklist

- [ ] Change default admin password (`password`)
- [ ] Set strong `DB_PASSWORD` in `.env`
- [ ] Configure firewall (ports 80, 443, 22 only)
- [ ] Enable HTTPS/SSL
- [ ] Never commit `.env` to git
- [ ] Setup automated backups
- [ ] Configure monitoring/alerts
- [ ] Update payment gateway credentials
- [ ] Setup email service (SMTP)
- [ ] Regular security updates

---

## 📈 Performance Optimization

The production setup includes:

- ✅ **PHP OPcache** - Optimized bytecode caching
- ✅ **Redis** - Fast caching & queue backend
- ✅ **Dedicated Queue Workers** - Background job processing
- ✅ **Laravel Scheduler** - Automated cron tasks
- ✅ **Asset Minification** - Optimized CSS/JS
- ✅ **Database Indexing** - Fast queries
- ✅ **Resource Limits** - Container memory management

---

## 📞 Support & Documentation

- **Detailed Guide:** [DOCKER_DEPLOYMENT.md](DOCKER_DEPLOYMENT.md)
- **Laravel Docs:** https://laravel.com/docs
- **Docker Docs:** https://docs.docker.com

---

## 📄 License

SMM Panel - Laravel Application

---

**Need Help?**

1. Check logs: `make prod-logs`
2. Run health check: `./scripts/health-check.sh`
3. Review documentation: `DOCKER_DEPLOYMENT.md`
4. Check container status: `docker-compose ps`

---

**Last Updated:** January 2026  
**Version:** 2.0.0 (Production Ready)
