#!/bin/bash

#############################################
# SMM Panel - Database Restore Script
# Restores database from backup
#############################################

set -e

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
NC='\033[0m'

log() {
    echo -e "${CYAN}[$(date +'%Y-%m-%d %H:%M:%S')]${NC} $1"
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

COMPOSE_FILE="docker-compose.prod.yml"
BACKUP_DIR="backups/database"

# Check if backup directory exists
if [ ! -d "$BACKUP_DIR" ]; then
    error "Backup directory not found: $BACKUP_DIR"
fi

# List available backups
echo -e "${CYAN}Available backups:${NC}"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
ls -lh $BACKUP_DIR/*.sql* 2>/dev/null | awk '{print NR". "$9" - "$5" - "$6" "$7" "$8}' || error "No backups found!"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

# Select backup
read -p "Enter backup number to restore (or full path): " choice

if [[ "$choice" =~ ^[0-9]+$ ]]; then
    # Number selected
    BACKUP_FILE=$(ls -1 $BACKUP_DIR/*.sql* 2>/dev/null | sed -n "${choice}p")
else
    # Path provided
    BACKUP_FILE="$choice"
fi

if [ ! -f "$BACKUP_FILE" ]; then
    error "Backup file not found: $BACKUP_FILE"
fi

log "Selected backup: $BACKUP_FILE"

# Confirmation
warning "⚠️  THIS WILL REPLACE ALL CURRENT DATABASE DATA! ⚠️"
read -p "Are you absolutely sure you want to continue? [yes/no]: " confirm

if [ "$confirm" != "yes" ]; then
    log "Restore cancelled"
    exit 0
fi

# Get database credentials
if [ ! -f .env ]; then
    error ".env file not found!"
fi

DB_USER=$(grep ^DB_USERNAME= .env | cut -d= -f2)
DB_PASS=$(grep ^DB_PASSWORD= .env | cut -d= -f2)
DB_NAME=$(grep ^DB_DATABASE= .env | cut -d= -f2)

# Create safety backup before restore
log "📦 Creating safety backup of current database..."
SAFETY_BACKUP="backups/safety_backup_$(date +%Y%m%d_%H%M%S).sql"
docker-compose -f $COMPOSE_FILE exec -T db mysqldump \
    -u "$DB_USER" \
    -p"$DB_PASS" \
    "$DB_NAME" \
    > "$SAFETY_BACKUP" 2>/dev/null || warning "Safety backup failed"

[ -f "$SAFETY_BACKUP" ] && success "Safety backup created: $SAFETY_BACKUP"

# Decompress if needed
TEMP_FILE="$BACKUP_FILE"
if [[ "$BACKUP_FILE" == *.gz ]]; then
    log "Decompressing backup..."
    TEMP_FILE="/tmp/restore_temp_$(date +%s).sql"
    gunzip -c "$BACKUP_FILE" > "$TEMP_FILE"
    success "Decompressed"
fi

# Restore database
log "🔄 Restoring database from backup..."

# Drop and recreate database
docker-compose -f $COMPOSE_FILE exec -T db mysql \
    -u "$DB_USER" \
    -p"$DB_PASS" \
    -e "DROP DATABASE IF EXISTS $DB_NAME; CREATE DATABASE $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" \
    2>/dev/null || error "Failed to recreate database"

# Import backup
docker-compose -f $COMPOSE_FILE exec -T db mysql \
    -u "$DB_USER" \
    -p"$DB_PASS" \
    "$DB_NAME" \
    < "$TEMP_FILE" || error "Restoration failed!"

# Cleanup temp file
[ -f "$TEMP_FILE" ] && [ "$TEMP_FILE" != "$BACKUP_FILE" ] && rm "$TEMP_FILE"

success "Database restored successfully!"

# Clear caches
log "🧹 Clearing application caches..."
docker-compose -f $COMPOSE_FILE exec -T app php artisan cache:clear >/dev/null 2>&1 || true
docker-compose -f $COMPOSE_FILE exec -T app php artisan config:clear >/dev/null 2>&1 || true

success "Caches cleared"

# Summary
echo ""
echo -e "${GREEN}╔═══════════════════════════════════════╗${NC}"
echo -e "${GREEN}║   ✅ RESTORE COMPLETED!               ║${NC}"
echo -e "${GREEN}╚═══════════════════════════════════════╝${NC}"
echo ""
log "Restored from: $BACKUP_FILE"
log "Safety backup: $SAFETY_BACKUP"
echo ""
warning "Please verify your application and data integrity!"
echo ""

exit 0
