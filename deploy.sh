#!/bin/bash

# 🚀 Laravel Cafe Deployment Script
# Script untuk deploy aplikasi ke VPS menggunakan Docker Compose

set -e

echo "================================================"
echo "🚀 Laravel Cafe Deployment Script"
echo "================================================"
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function untuk print colored message
print_info() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warn() {
    echo -e "${YELLOW}[WARN]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if running on server
print_info "Checking environment..."

# Cek apakah Docker terinstall
if ! command -v docker &> /dev/null; then
    print_error "Docker tidak terinstall!"
    print_info "Install Docker dengan: curl -fsSL https://get.docker.com | sh"
    exit 1
fi

# Cek apakah Docker Compose terinstall
if ! command -v docker-compose &> /dev/null; then
    print_error "Docker Compose tidak terinstall!"
    print_info "Install dengan: sudo apt install docker-compose -y"
    exit 1
fi

print_info "Docker dan Docker Compose sudah terinstall ✓"
echo ""

# Setup .env file
print_info "Setup environment file..."
if [ ! -f .env ]; then
    if [ -f .env.production ]; then
        cp .env.production .env
        print_info "File .env dibuat dari .env.production ✓"
    else
        cp .env.example .env
        print_warn "File .env dibuat dari .env.example"
        print_warn "Harap edit .env dan sesuaikan konfigurasi!"
    fi
else
    print_info "File .env sudah ada ✓"
fi
echo ""

# Set permissions
print_info "Setting permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
print_info "Permissions set ✓"
echo ""

# Stop existing containers
print_info "Stopping existing containers..."
docker-compose down 2>/dev/null || true
print_info "Containers stopped ✓"
echo ""

# Build and start containers
print_info "Building and starting Docker containers..."
docker-compose up -d --build

# Wait for containers to be ready
print_info "Waiting for containers to be ready..."
sleep 10

# Fix ownership inside container
print_info "Fixing ownership inside container..."
docker-compose exec -T app chown -R www-data:www-data /var/www/storage || true
docker-compose exec -T app chown -R www-data:www-data /var/www/bootstrap/cache || true
docker-compose exec -T app chmod -R 775 /var/www/storage || true
docker-compose exec -T app chmod -R 775 /var/www/bootstrap/cache || true

# Check if containers are running
print_info "Checking container status..."
docker-compose ps
echo ""

# Setup Laravel application
print_info "Setting up Laravel application..."

# Install composer dependencies first
print_info "Installing composer dependencies..."
docker-compose exec -T app composer install --no-dev --optimize-autoloader --no-interaction

# Generate APP_KEY
print_info "Generating APP_KEY..."
docker-compose exec -T app php artisan key:generate --force

# Run migrations
print_info "Running database migrations..."
docker-compose exec -T app php artisan migrate --force

# Optional: Seed database
read -p "Jalankan database seeder? (y/n): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    print_info "Running database seeders..."
    docker-compose exec -T app php artisan db:seed --force
fi

# Cache configuration for production
print_info "Caching configuration..."
docker-compose exec -T app php artisan config:cache
docker-compose exec -T app php artisan route:cache

echo ""
print_info "Deployment completed! ✓"
echo ""

# Display access information
echo "================================================"
echo "✅ Deployment Berhasil!"
echo "================================================"
echo ""
echo "📍 Akses Aplikasi:"
echo "   Homepage: http://203.175.10.112"
echo "   API Base: http://203.175.10.112/api"
echo ""
echo "🔧 Useful Commands:"
echo "   Lihat logs: docker-compose logs -f"
echo "   Restart: docker-compose restart"
echo "   Stop: docker-compose down"
echo "   Shell: docker-compose exec app bash"
echo ""
echo "================================================"
