# 🐳 SMM Panel - Docker Deployment Guide

This guide explains how to deploy the SMM Panel application using Docker, whether for local development or production hosting.

## 📋 Prerequisites

- Docker Engine 20.10+ installed
- Docker Compose v2.0+ installed
- At least 2GB of free disk space
- For production: A server with at least 2GB RAM

### Install Docker

#### On Ubuntu/Debian
```bash
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER
```

#### On CentOS/RHEL
```bash
sudo yum install -y docker
sudo systemctl start docker
sudo systemctl enable docker
```

#### Install Docker Compose
```bash
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
```

## 🚀 Quick Start (Local Development)

### 1. Clone the Repository
```bash
git clone <your-github-repo-url>
cd smm-panel
```

### 2. Configure Environment
```bash
# Copy the example environment file
cp .env.example .env

# Edit .env and update database credentials
# For Docker, use these settings:
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=smm_panel
DB_USERNAME=smm_user
DB_PASSWORD=your_secure_password
```

### 3. Build and Start Containers
```bash
# Build and start all containers
docker-compose up -d

# View logs
docker-compose logs -f
```

### 4. Initialize the Application
```bash
# Install PHP dependencies
docker-compose exec app composer install

# Install Node.js dependencies
docker-compose exec app npm install

# Generate application key
docker-compose exec app php artisan key:generate

# Run migrations and seeders
docker-compose exec app php artisan migrate --seed

# Build frontend assets
docker-compose exec app npm run build
```

### 5. Access the Application
- **URL**: http://localhost:8945
- **Admin**: admin@smmpanel.com / password
- **User**: user@smmpanel.com / password

## 🏭 Production Deployment

### Enhanced Docker Compose Setup

For production, we'll use an enhanced `docker-compose.prod.yml` with:
- Redis for caching and queues
- Dedicated scheduler container for cron jobs
- Queue worker for background jobs
- Proper resource limits
- Health checks

### 1. Prepare Production Environment

```bash
# Create .env for production
cp .env.example .env

# Edit .env with production settings
nano .env
```

**Important Production Settings:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=smm_panel
DB_USERNAME=smm_user
DB_PASSWORD=STRONG_PASSWORD_HERE

CACHE_STORE=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### 2. Deploy with Production Config

```bash
# Build containers for production
docker-compose -f docker-compose.prod.yml up -d --build

# Initialize application
docker-compose -f docker-compose.prod.yml exec app composer install --optimize-autoloader --no-dev
docker-compose -f docker-compose.prod.yml exec app php artisan key:generate
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force --seed
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache
docker-compose -f docker-compose.prod.yml exec app npm install
docker-compose -f docker-compose.prod.yml exec app npm run build

# Set proper permissions
docker-compose -f docker-compose.prod.yml exec app chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
```

### 3. Setup SSL with Nginx Proxy (Recommended)

Use nginx-proxy and Let's Encrypt companion for automatic SSL:

```bash
# Create external network
docker network create nginx-proxy

# Run nginx-proxy
docker run -d -p 80:80 -p 443:443 \
  --name nginx-proxy \
  --network nginx-proxy \
  -v /var/run/docker.sock:/tmp/docker.sock:ro \
  -v certs:/etc/nginx/certs \
  -v vhost:/etc/nginx/vhost.d \
  -v html:/usr/share/nginx/html \
  nginxproxy/nginx-proxy

# Run Let's Encrypt companion
docker run -d \
  --name nginx-proxy-acme \
  --network nginx-proxy \
  --volumes-from nginx-proxy \
  -v /var/run/docker.sock:/var/run/docker.sock:ro \
  -v acme:/etc/acme.sh \
  nginxproxy/acme-companion
```

Then update `docker-compose.prod.yml` web service to include:
```yaml
environment:
  - VIRTUAL_HOST=yourdomain.com
  - LETSENCRYPT_HOST=yourdomain.com
  - LETSENCRYPT_EMAIL=your@email.com
networks:
  - smm-network
  - nginx-proxy
```

## 📊 Container Management

### Useful Commands

```bash
# View running containers
docker-compose ps

# View logs
docker-compose logs -f [service_name]

# Restart a service
docker-compose restart [service_name]

# Stop all containers
docker-compose down

# Stop and remove volumes (CAUTION: deletes database)
docker-compose down -v

# Execute commands in app container
docker-compose exec app php artisan [command]

# Access database
docker-compose exec db mysql -u smm_user -p smm_panel

# SSH into container
docker-compose exec app bash
```

### Maintenance

```bash
# Update application code
git pull
docker-compose exec app composer install
docker-compose exec app php artisan migrate --force
docker-compose exec app npm install
docker-compose exec app npm run build
docker-compose restart app

# Clear caches
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear

# Optimize for production
docker-compose exec app php artisan optimize
```

### Backup Database

```bash
# Export database
docker-compose exec db mysqldump -u smm_user -p smm_panel > backup_$(date +%Y%m%d_%H%M%S).sql

# Restore database
docker-compose exec -T db mysql -u smm_user -p smm_panel < backup.sql
```

## 🔧 Troubleshooting

### Permission Issues
```bash
docker-compose exec app chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
docker-compose exec app chmod -R 775 /var/www/storage /var/www/bootstrap/cache
```

### Database Connection Failed
- Ensure DB_HOST=db in .env (not localhost)
- Wait 30 seconds after starting containers for database to initialize
- Check logs: `docker-compose logs db`

### 502 Bad Gateway
- Check if PHP-FPM is running: `docker-compose exec app php-fpm -t`
- Restart app container: `docker-compose restart app`
- Check nginx logs: `docker-compose logs web`

### Assets Not Loading
```bash
docker-compose exec app npm run build
docker-compose exec app php artisan storage:link
```

### Queue Jobs Not Processing
```bash
# Check if queue worker is running
docker-compose ps worker

# Restart worker
docker-compose restart worker

# View worker logs
docker-compose logs -f worker
```

## 🔐 Security Best Practices

1. **Change Default Passwords**: Update admin and database passwords immediately
2. **Use Strong APP_KEY**: Generated automatically, never share it
3. **Enable HTTPS**: Use SSL certificates in production
4. **Firewall**: Only expose ports 80 and 443
5. **Regular Updates**: Keep Docker images and dependencies updated
6. **Backup**: Schedule regular database backups
7. **Environment Variables**: Never commit .env to git

## 📈 Performance Optimization

1. **Enable OPcache**: Already configured in PHP-FPM
2. **Use Redis**: Switch CACHE_STORE and QUEUE_CONNECTION to redis
3. **CDN**: Serve static assets via CDN
4. **Resource Limits**: Set memory limits in docker-compose.yml
5. **Database Indexing**: Ensure proper indexes on frequently queried columns

## 🌐 Scaling

### Horizontal Scaling (Multiple Workers)
```bash
# Scale queue workers
docker-compose up -d --scale worker=3
```

### Load Balancing
For high traffic, use multiple app containers behind a load balancer:
- Use Docker Swarm or Kubernetes
- Configure shared storage for uploads (S3, NFS)
- Use external MySQL instance (RDS, CloudSQL)

## 📝 Monitoring

### Container Health
```bash
# Check container stats
docker stats

# Health check
docker-compose ps
```

### Application Logs
```bash
# Laravel logs
docker-compose exec app tail -f storage/logs/laravel.log

# Nginx access logs
docker-compose logs -f web

# Database logs
docker-compose logs -f db
```

## 🎯 Next Steps

1. Configure payment gateways (eSewa, Khalti) with production credentials
2. Set up email service (SMTP, SendGrid, Mailgun)
3. Configure monitoring (Sentry, New Relic)
4. Set up automatic backups
5. Configure CDN for static assets
6. Implement rate limiting and DDoS protection

## 📞 Support

For issues or questions:
- Check logs: `docker-compose logs -f`
- Review Laravel documentation: https://laravel.com/docs
- Docker documentation: https://docs.docker.com

---

**Last Updated**: January 2026  
**Version**: 1.0.0
