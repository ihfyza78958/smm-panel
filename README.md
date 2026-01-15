# SMM Panel - Laravel Application

A professional SMM (Social Media Marketing) panel built with Laravel 11, featuring wallet system, payment integration, and reseller API.

## 🚀 Quick Deploy to Ubuntu Server

### Prerequisites
- Ubuntu 20.04+ server
- Docker & Docker Compose installed
- 2GB RAM minimum

### Installation Steps

**1. Install Docker (if not installed):**
```bash
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER
newgrp docker
```

**2. Clone Repository:**
```bash
git clone https://github.com/ioprakash/smm-panel.git
cd smm-panel
```

**3. Configure Environment:**
```bash
cp .env.production .env
nano .env
```

Update these required values:
- `APP_URL` - Your server URL
- `DB_PASSWORD` - Strong database password
- `MAIL_*` - Email configuration
- Payment gateway credentials

**4. Deploy:**
```bash
# Make script executable
chmod +x scripts/deploy.sh

# Run deployment
./scripts/deploy.sh
```

**5. Access Application:**
- URL: `http://your-server-ip:8945`
- Admin: `admin@smmpanel.com` / `password`
- User: `user@smmpanel.com` / `password`

**⚠️ Change default passwords immediately!**

---

## 📁 Project Structure

```
smm-panel/
├── app/                    # Laravel application
├── docker/                 # Docker configurations
├── scripts/               # Management scripts
│   ├── deploy.sh         # Deployment script
│   ├── backup.sh         # Backup automation
│   └── restore.sh        # Restore database
├── docker-compose.prod.yml # Production setup
├── Dockerfile             # Container image
├── .env.production        # Environment template
└── README.md             # This file
```

---

## 🛠️ Management Commands

### Start/Stop
```bash
# Start services
docker-compose -f docker-compose.prod.yml up -d

# Stop services
docker-compose -f docker-compose.prod.yml down

# Restart
docker-compose -f docker-compose.prod.yml restart
```

### View Logs
```bash
docker-compose -f docker-compose.prod.yml logs -f
```

### Backup Database
```bash
./scripts/backup.sh
```

### Update Application
```bash
git pull
docker-compose -f docker-compose.prod.yml exec app composer install --no-dev
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
docker-compose -f docker-compose.prod.yml restart
```

---

## 🔧 Troubleshooting

### Composer Error (vendor not found)
```bash
docker-compose -f docker-compose.prod.yml exec app composer install
docker-compose -f docker-compose.prod.yml restart app
```

### Database Connection Error
- Check `.env` has `DB_HOST=db` (not localhost)
- Restart database: `docker-compose -f docker-compose.prod.yml restart db`

### Permission Errors
```bash
docker-compose -f docker-compose.prod.yml exec app chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
```

### Container Not Starting
```bash
docker-compose -f docker-compose.prod.yml logs
docker-compose -f docker-compose.prod.yml down
docker-compose -f docker-compose.prod.yml up -d --build
```

---

## 📊 Services

The application runs with these Docker containers:
- **app** - PHP-FPM (Laravel)
- **web** - Nginx web server
- **db** - MariaDB database
- **redis** - Cache & queue backend
- **scheduler** - Cron jobs
- **worker** - Background jobs

---

## 🔐 Security

1. Change default admin password
2. Use strong `DB_PASSWORD` in `.env`
3. Configure firewall:
```bash
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw allow 8945/tcp
sudo ufw enable
```
4. Setup SSL for production domains
5. Never commit `.env` file

---

## 📞 Support

For issues:
- Check logs: `docker-compose -f docker-compose.prod.yml logs -f`
- Run health check: `./scripts/health-check.sh`
- Verify containers: `docker-compose -f docker-compose.prod.yml ps`

---

## 📄 License

Laravel SMM Panel Application
