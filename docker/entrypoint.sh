#!/bin/bash
set -e

echo "========================================="
echo "  SMM Panel - Container Entrypoint"
echo "========================================="

# Wait for MySQL to be ready
wait_for_db() {
    echo "⏳ Waiting for database..."
    local max_attempts=30
    local attempt=0
    while [ $attempt -lt $max_attempts ]; do
        if php artisan db:monitor --databases=mysql 2>/dev/null | grep -q "OK"; then
            echo "✅ Database is ready"
            return 0
        fi
        # Fallback: try a simple connection
        if php -r "try { new PDO('mysql:host=${DB_HOST:-db};port=${DB_PORT:-3306}', '${DB_USERNAME:-smm_user}', '${DB_PASSWORD:-secret}'); echo 'ok'; } catch(Exception \$e) { exit(1); }" 2>/dev/null; then
            echo "✅ Database is ready"
            return 0
        fi
        attempt=$((attempt + 1))
        echo "  Attempt $attempt/$max_attempts - waiting..."
        sleep 2
    done
    echo "❌ Database not ready after $max_attempts attempts"
    return 1
}

# Wait for Redis to be ready
wait_for_redis() {
    echo "⏳ Waiting for Redis..."
    local max_attempts=15
    local attempt=0
    while [ $attempt -lt $max_attempts ]; do
        if php -r "try { \$r = new Redis(); \$r->connect('${REDIS_HOST:-redis}', ${REDIS_PORT:-6379}); echo 'ok'; } catch(Exception \$e) { exit(1); }" 2>/dev/null; then
            echo "✅ Redis is ready"
            return 0
        fi
        attempt=$((attempt + 1))
        sleep 1
    done
    echo "⚠️  Redis not ready, continuing anyway..."
    return 0
}

# Sync built public assets to the shared volume (handles updates across deploys)
if [ -d /var/www/public-build ]; then
    echo "📦 Syncing public assets..."
    cp -ru /var/www/public-build/* /var/www/public/ 2>/dev/null || true
fi

# Only run full setup (migrations, key gen, optimize) for the main app process
if [ "$1" = "php-fpm" ]; then
    # Generate APP_KEY if not set
    if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
        echo "🔑 Generating application key..."
        php artisan key:generate --force --no-interaction
    fi

    # Wait for services
    wait_for_db
    wait_for_redis

    # Run migrations
    echo "🗃️  Running database migrations..."
    php artisan migrate --force --no-interaction

    # Create storage symlink
    echo "🔗 Creating storage link..."
    php artisan storage:link --force 2>/dev/null || true

    # Optimize for production
    if [ "$APP_ENV" = "production" ]; then
        echo "⚡ Optimizing for production..."
        php artisan optimize
    fi
else
    # For scheduler/worker: just wait for DB + Redis
    wait_for_db
    wait_for_redis
fi

# Fix permissions
echo "🔒 Setting permissions..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/storage /var/www/bootstrap/cache 2>/dev/null || true

echo "========================================="
echo "  ✅ Application ready!"
echo "========================================="

# Execute the main command (php-fpm, scheduler, worker, etc.)
exec "$@"
