#!/bin/bash

# Script untuk membuat user admin

echo "==================================="
echo "🔐 Create Admin User"
echo "==================================="
echo ""

# Default values
DEFAULT_NAME="Admin Cafe"
DEFAULT_EMAIL="admin@cafe.com"
DEFAULT_PASSWORD="admin123"

# Get input
read -p "Nama Admin [$DEFAULT_NAME]: " ADMIN_NAME
ADMIN_NAME=${ADMIN_NAME:-$DEFAULT_NAME}

read -p "Email Admin [$DEFAULT_EMAIL]: " ADMIN_EMAIL
ADMIN_EMAIL=${ADMIN_EMAIL:-$DEFAULT_EMAIL}

read -sp "Password [$DEFAULT_PASSWORD]: " ADMIN_PASSWORD
echo ""
ADMIN_PASSWORD=${ADMIN_PASSWORD:-$DEFAULT_PASSWORD}

echo ""
echo "Creating admin user..."

docker-compose exec app php artisan tinker --execute "
\$user = App\Models\User::updateOrCreate(
    ['email' => '$ADMIN_EMAIL'],
    [
        'nama' => '$ADMIN_NAME',
        'password' => bcrypt('$ADMIN_PASSWORD'),
        'role' => 'admin'
    ]
);
echo '✓ Admin user created/updated: ' . \$user->email . PHP_EOL;
echo '  Name: ' . \$user->nama . PHP_EOL;
echo '  Role: ' . \$user->role . PHP_EOL;
"

echo ""
echo "==================================="
echo "✅ Admin User Credentials:"
echo "   Email: $ADMIN_EMAIL"
echo "   Password: $ADMIN_PASSWORD"
echo "==================================="
