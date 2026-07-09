#!/bin/bash

# 🌱 Run Database Seeder

set -e

echo "================================================"
echo "🌱 Running Database Seeder"
echo "================================================"
echo ""

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

print_info() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warn() {
    echo -e "${YELLOW}[WARN]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if containers are running
print_info "Checking container status..."
if ! docker-compose ps | grep -q "cafe-app.*Up"; then
    print_error "Container cafe-app tidak berjalan!"
    print_info "Jalankan: docker-compose up -d"
    exit 1
fi

# Check database connection
print_info "Testing database connection..."
if ! docker-compose exec -T app php artisan tinker --execute "DB::connection()->getPdo();" 2>/dev/null; then
    print_error "Database connection failed!"
    print_info "Pastikan .env sudah dikonfigurasi dengan benar:"
    print_info "  DB_CONNECTION=mysql"
    print_info "  DB_HOST=mysql"
    print_info "  DB_DATABASE=cafe_db"
    print_info "  DB_USERNAME=cafe_user"
    print_info "  DB_PASSWORD=cafe_secret"
    exit 1
fi
print_info "Database connection OK ✓"
echo ""

# Confirm before seeding
read -p "⚠️  Ini akan menghapus semua data dan membuat data baru. Lanjutkan? (y/n): " -n 1 -r
echo ""
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    print_warn "Seeding dibatalkan."
    exit 0
fi

echo ""
print_info "Refreshing database (migrate:fresh)..."
docker-compose exec -T app php artisan migrate:fresh --force

echo ""
print_info "Running seeders..."
docker-compose exec -T app php artisan db:seed --force

echo ""
echo "================================================"
echo "✅ Seeding Completed Successfully!"
echo "================================================"
echo ""
echo "🔐 Admin Credentials:"
echo "   Email: admin@cafe.com"
echo "   Password: admin123"
echo ""
echo "👥 Sample Users (Password: password):"
echo "   - budi@example.com"
echo "   - siti@example.com"
echo "   - andi@example.com"
echo "   - dewi@example.com"
echo "   - rudi@example.com"
echo ""
echo "📊 Database now contains:"
echo "   - 1 Admin user"
echo "   - 15+ Regular users"
echo "   - 30 Menu items (Makanan, Minuman, Snack)"
echo "   - Sample Orders"
echo "   - Sample Reservations"
echo "   - Sample Reviews"
echo ""
echo "🧪 Test Login:"
echo "   curl -X POST http://203.175.10.112:81/api/auth/login \\"
echo "     -H 'Content-Type: application/json' \\"
echo "     -d '{\"email\":\"admin@cafe.com\",\"password\":\"admin123\"}'"
echo ""
