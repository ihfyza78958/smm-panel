#!/bin/bash

#############################################
# SMM Panel - Health Check & Monitoring
# Monitors application health and sends alerts
#############################################

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

COMPOSE_FILE="docker-compose.prod.yml"
LOG_FILE="logs/health-check.log"
mkdir -p logs

# Detect docker compose command
if docker compose version &>/dev/null; then
    DC="docker compose"
elif command -v docker-compose &>/dev/null; then
    DC="docker-compose"
else
    echo "Docker Compose not found!"; exit 1
fi

log() {
    echo "[$(date +'%Y-%m-%d %H:%M:%S')] $1" | tee -a $LOG_FILE
}

# Health check results
HEALTH_STATUS=0

# Check if containers are running
check_containers() {
    log "Checking Docker containers..."
    
    CONTAINERS=("smm-app" "smm-web" "smm-db" "smm-redis" "smm-scheduler" "smm-worker")
    
    for container in "${CONTAINERS[@]}"; do
        if docker ps --format '{{.Names}}' | grep -q "^${container}$"; then
            echo -e "${GREEN}✅ $container is running${NC}"
        else
            echo -e "${RED}❌ $container is NOT running${NC}"
            HEALTH_STATUS=1
        fi
    done
}

# Check web server
check_web() {
    log "Checking web server..."
    
    if curl -f http://localhost:8945 > /dev/null 2>&1; then
        echo -e "${GREEN}✅ Web server is responding${NC}"
    else
        echo -e "${RED}❌ Web server is NOT responding${NC}"
        HEALTH_STATUS=1
    fi
}

# Check database
check_database() {
    log "Checking database..."
    
    DB_USER=$(grep ^DB_USERNAME= .env | cut -d= -f2)
    DB_PASS=$(grep ^DB_PASSWORD= .env | cut -d= -f2)
    
    if $DC -f $COMPOSE_FILE exec -T db mysqladmin ping -h localhost -u "$DB_USER" -p"$DB_PASS" 2>&1 | grep -q "mysqld is alive"; then
        echo -e "${GREEN}✅ Database is alive${NC}"
    else
        echo -e "${RED}❌ Database is NOT responding${NC}"
        HEALTH_STATUS=1
    fi
}

# Check Redis
check_redis() {
    log "Checking Redis..."
    
    if $DC -f $COMPOSE_FILE exec -T redis redis-cli ping 2>&1 | grep -q "PONG"; then
        echo -e "${GREEN}✅ Redis is responding${NC}"
    else
        echo -e "${RED}❌ Redis is NOT responding${NC}"
        HEALTH_STATUS=1
    fi
}

# Check disk space
check_disk() {
    log "Checking disk space..."
    
    DISK_USAGE=$(df -h / | awk 'NR==2 {print $5}' | sed 's/%//')
    
    if [ "$DISK_USAGE" -lt 80 ]; then
        echo -e "${GREEN}✅ Disk usage: ${DISK_USAGE}%${NC}"
    elif [ "$DISK_USAGE" -lt 90 ]; then
        echo -e "${YELLOW}⚠️  Disk usage: ${DISK_USAGE}% (Warning)${NC}"
    else
        echo -e "${RED}❌ Disk usage: ${DISK_USAGE}% (Critical!)${NC}"
        HEALTH_STATUS=1
    fi
}

# Check application logs for errors
check_logs() {
    log "Checking for recent errors..."
    
    ERROR_COUNT=$($DC -f $COMPOSE_FILE exec -T app tail -n 100 storage/logs/laravel.log 2>/dev/null | grep -c "ERROR" || echo 0)
    
    if [ "$ERROR_COUNT" -eq 0 ]; then
        echo -e "${GREEN}✅ No recent errors in logs${NC}"
    else
        echo -e "${YELLOW}⚠️  Found $ERROR_COUNT errors in recent logs${NC}"
    fi
}

# Check queue workers
check_queue() {
    log "Checking queue workers..."
    
    if docker ps --format '{{.Names}}' | grep -q "smm-worker"; then
        WORKER_STATUS=$($DC -f $COMPOSE_FILE exec -T worker ps aux | grep "queue:work" || echo "")
        
        if [ -n "$WORKER_STATUS" ]; then
            echo -e "${GREEN}✅ Queue worker is active${NC}"
        else
            echo -e "${RED}❌ Queue worker is NOT active${NC}"
            HEALTH_STATUS=1
        fi
    fi
}

# Check SSL certificate expiry (if SSL is configured)
check_ssl() {
    if [ -d "certbot/conf/live" ]; then
        log "Checking SSL certificate..."
        
        CERT_PATH=$(find certbot/conf/live -name cert.pem | head -1)
        if [ -f "$CERT_PATH" ]; then
            EXPIRY=$(openssl x509 -enddate -noout -in "$CERT_PATH" | cut -d= -f2)
            EXPIRY_EPOCH=$(date -d "$EXPIRY" +%s)
            NOW_EPOCH=$(date +%s)
            DAYS_LEFT=$(( ($EXPIRY_EPOCH - $NOW_EPOCH) / 86400 ))
            
            if [ "$DAYS_LEFT" -gt 30 ]; then
                echo -e "${GREEN}✅ SSL certificate valid for $DAYS_LEFT days${NC}"
            elif [ "$DAYS_LEFT" -gt 7 ]; then
                echo -e "${YELLOW}⚠️  SSL certificate expires in $DAYS_LEFT days${NC}"
            else
                echo -e "${RED}❌ SSL certificate expires in $DAYS_LEFT days! Renew now!${NC}"
                HEALTH_STATUS=1
            fi
        fi
    fi
}

# Main health check
echo "╔════════════════════════════════════════╗"
echo "║   SMM Panel - Health Check Report     ║"
echo "╚════════════════════════════════════════╝"
echo ""

check_containers
check_web
check_database
check_redis
check_disk
check_logs
check_queue
check_ssl

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

if [ $HEALTH_STATUS -eq 0 ]; then
    echo -e "${GREEN}✅ All health checks passed!${NC}"
    log "Health check: PASS"
else
    echo -e "${RED}❌ Some health checks failed!${NC}"
    log "Health check: FAIL"
    
    # Send alert (configure webhook or email)
    # curl -X POST https://hooks.slack.com/your-webhook -d '{"text":"SMM Panel health check failed!"}'
fi

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Full log: $LOG_FILE"
echo ""

exit $HEALTH_STATUS
