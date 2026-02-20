#!/bin/bash

#############################################
# SMM Panel - Automated Backup Script
# Creates database and files backup
#############################################

set -e

# Configuration
BACKUP_DIR="backups"
RETENTION_DAYS=30
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
COMPOSE_FILE="docker-compose.prod.yml"

# Detect docker compose command
if docker compose version &>/dev/null; then
    DC="docker compose"
elif command -v docker-compose &>/dev/null; then
    DC="docker-compose"
else
    echo "Docker Compose not found!"; exit 1
fi

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

log() {
    echo -e "[$(date +'%Y-%m-%d %H:%M:%S')] $1"
}

success() {
    echo -e "${GREEN}✅ $1${NC}"
}

error() {
    echo -e "${RED}❌ $1${NC}"
    exit 1
}

warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

log "Starting backup process..."

# Create backup directory
mkdir -p $BACKUP_DIR/{database,files}

# Get database credentials from .env
if [ ! -f .env ]; then
    error ".env file not found!"
fi

DB_USER=$(grep ^DB_USERNAME= .env | cut -d= -f2)
DB_PASS=$(grep ^DB_PASSWORD= .env | cut -d= -f2)
DB_NAME=$(grep ^DB_DATABASE= .env | cut -d= -f2)

# Backup Database
log "📦 Backing up database..."
BACKUP_FILE="$BACKUP_DIR/database/db_backup_$TIMESTAMP.sql"

$DC -f $COMPOSE_FILE exec -T db mysqldump \
    -u "$DB_USER" \
    -p"$DB_PASS" \
    "$DB_NAME" \
    --single-transaction \
    --quick \
    --lock-tables=false \
    > "$BACKUP_FILE" 2>/dev/null

if [ $? -eq 0 ] && [ -s "$BACKUP_FILE" ]; then
    # Compress backup
    gzip "$BACKUP_FILE"
    BACKUP_FILE="${BACKUP_FILE}.gz"
    BACKUP_SIZE=$(du -h "$BACKUP_FILE" | cut -f1)
    success "Database backed up: $BACKUP_FILE ($BACKUP_SIZE)"
else
    error "Database backup failed!"
fi

# Backup uploaded files
log "📁 Backing up uploaded files..."
FILES_BACKUP="$BACKUP_DIR/files/files_backup_$TIMESTAMP.tar.gz"

tar -czf "$FILES_BACKUP" \
    storage/app/public \
    public/uploads 2>/dev/null || true

if [ -f "$FILES_BACKUP" ]; then
    FILES_SIZE=$(du -h "$FILES_BACKUP" | cut -f1)
    success "Files backed up: $FILES_BACKUP ($FILES_SIZE)"
else
    warning "No files to backup or backup failed"
fi

# Backup .env file (encrypted)
log "🔐 Backing up configuration..."
ENV_BACKUP="$BACKUP_DIR/env_backup_$TIMESTAMP.enc"
if command -v openssl &> /dev/null; then
    # Encrypt .env file with password
    openssl enc -aes-256-cbc -salt -pbkdf2 -in .env -out "$ENV_BACKUP" -k "$(hostname)" 2>/dev/null
    success "Configuration backed up (encrypted): $ENV_BACKUP"
else
    cp .env "$BACKUP_DIR/env_backup_$TIMESTAMP"
    warning "Backed up .env without encryption (openssl not found)"
fi

# Create backup manifest
MANIFEST_FILE="$BACKUP_DIR/manifest_$TIMESTAMP.txt"
cat > "$MANIFEST_FILE" << EOF
SMM Panel Backup Manifest
=========================
Date: $(date)
Hostname: $(hostname)
Database: $BACKUP_FILE
Files: $FILES_BACKUP
Environment: $ENV_BACKUP

Database Size: $(du -h "$BACKUP_FILE" 2>/dev/null | cut -f1 || echo "N/A")
Files Size: $(du -h "$FILES_BACKUP" 2>/dev/null | cut -f1 || echo "N/A")
Total Backup Size: $(du -sh "$BACKUP_DIR" | cut -f1)

Container Status:
$($DC -f $COMPOSE_FILE ps)
EOF

success "Backup manifest created: $MANIFEST_FILE"

# Clean old backups
log "🧹 Cleaning backups older than $RETENTION_DAYS days..."
find $BACKUP_DIR -type f -mtime +$RETENTION_DAYS -delete 2>/dev/null || true
OLD_COUNT=$(find $BACKUP_DIR -type f | wc -l)
success "Cleanup completed. Current backup files: $OLD_COUNT"

# Backup summary
log "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
success "Backup completed successfully!"
log "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
log "Database: $BACKUP_FILE"
log "Files: $FILES_BACKUP"
log "Manifest: $MANIFEST_FILE"
log "Total Size: $(du -sh $BACKUP_DIR | cut -f1)"
log "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

# Optional: Upload to remote storage
# Uncomment and configure for S3, rsync, or other remote backup
# log "☁️  Uploading to remote storage..."
# aws s3 cp $BACKUP_DIR s3://your-bucket/smm-panel-backups/ --recursive
# rsync -avz $BACKUP_DIR user@remote-server:/backups/smm-panel/

exit 0
