#!/bin/bash

# 🔧 Quick Fix untuk Vendor Dependencies Issue

set -e

echo "================================================"
echo "🔧 Fixing Vendor Dependencies"
echo "================================================"
echo ""

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

print_info() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warn() {
    echo -e "${YELLOW}[WARN]${NC} $1"
}

# Check if containers are running
print_info "Checking container status..."
if ! docker-compose ps | grep -q "cafe-app"; then
    print_warn "Container tidak berjalan, memulai containers..."
    docker-compose up -d
    sleep 5
fi

# Install composer dependencies
print_info "Installing composer dependencies..."
docker-compose exec -T app composer install --no-dev --optimize-autoloader --no-interaction

# Set proper permissions
print_info "Setting permissions..."
docker-compose exec -T app chown -R www-data:www-data /var/www/storage
docker-compose exec -T app chown -R www-data:www-data /var/www/bootstrap/cache
docker-compose exec -T app chmod -R 775 /var/www/storage
docker-compose exec -T app chmod -R 775 /var/www/bootstrap/cache

# Clear all cache
print_info "Clearing cache..."
docker-compose exec -T app php artisan cache:clear 2>/dev/null || true
docker-compose exec -T app php artisan view:clear 2>/dev/null || true
docker-compose exec -T app php artisan config:clear 2>/dev/null || true

# Generate APP_KEY if not exists
print_info "Checking APP_KEY..."
if ! docker-compose exec -T app php artisan config:show app.key 2>/dev/null | grep -q "base64:"; then
    print_info "Generating APP_KEY..."
    docker-compose exec -T app php artisan key:generate --force
else
    print_info "APP_KEY already exists ✓"
fi

# Run migrations
print_info "Running migrations..."
docker-compose exec -T app php artisan migrate --force

# Cache configuration
print_info "Caching configuration..."
docker-compose exec -T app php artisan config:cache
docker-compose exec -T app php artisan route:cache

echo ""
print_info "Fix completed! ✓"
echo ""

# Test
print_info "Testing application..."
docker-compose exec -T app php artisan --version

echo ""
echo "================================================"
echo "✅ Dependencies Fixed Successfully!"
echo "================================================"
echo ""
echo "You can now:"
echo "  1. Create admin: ./create-admin.sh"
echo "  2. Test API: curl http://203.175.10.112:81/api/menu"
echo "  3. Check logs: docker-compose logs -f app"
echo ""
