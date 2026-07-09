#!/bin/bash

# 🔧 Fix Storage & Cache Permissions

set -e

echo "================================================"
echo "🔧 Fixing Storage & Cache Permissions"
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
if ! docker-compose ps | grep -q "cafe-app.*Up"; then
    print_warn "Container tidak berjalan, memulai containers..."
    docker-compose up -d
    sleep 5
fi

# Fix ownership - semua file milik www-data
print_info "Fixing ownership (www-data)..."
docker-compose exec -T app chown -R www-data:www-data /var/www/storage
docker-compose exec -T app chown -R www-data:www-data /var/www/bootstrap/cache

# Fix permissions
print_info "Setting permissions (775)..."
docker-compose exec -T app chmod -R 775 /var/www/storage
docker-compose exec -T app chmod -R 775 /var/www/bootstrap/cache

# Clear all cache
print_info "Clearing all cache..."
docker-compose exec -T app php artisan cache:clear 2>/dev/null || true
docker-compose exec -T app php artisan view:clear 2>/dev/null || true
docker-compose exec -T app php artisan config:clear 2>/dev/null || true
docker-compose exec -T app php artisan route:clear 2>/dev/null || true

# Verify permissions
print_info "Verifying permissions..."
echo ""
echo "Storage directory:"
docker-compose exec -T app ls -la /var/www/storage | head -n 5

echo ""
echo "Bootstrap/cache directory:"
docker-compose exec -T app ls -la /var/www/bootstrap/cache | head -n 5

echo ""
print_info "Restarting app container..."
docker-compose restart app

# Wait for restart
sleep 3

echo ""
echo "================================================"
echo "✅ Permissions Fixed Successfully!"
echo "================================================"
echo ""
echo "Storage dan cache directories sekarang:"
echo "  - Owner: www-data:www-data"
echo "  - Permission: 775 (rwxrwxr-x)"
echo ""
echo "Test aplikasi:"
echo "  curl http://203.175.10.112:81"
echo ""
