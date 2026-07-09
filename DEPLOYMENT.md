# 🚀 Deployment Guide - Laravel Cafe API

Panduan lengkap deploy aplikasi Laravel Cafe menggunakan Docker Compose di VPS dengan IP Public.

---

## 📋 Prerequisites

- VPS dengan Ubuntu/Debian
- Docker & Docker Compose terinstall
- Access SSH ke server
- IP Public: `203.175.10.112`

---

## 1️⃣ Install Docker di VPS

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Install Docker Compose
sudo apt install docker-compose -y

# Verifikasi instalasi
docker --version
docker-compose --version
```

---

## 2️⃣ Upload Project ke Server

```bash
# Clone dari Git (Recommended)
cd /opt/.izzudin
git clone <repository-url> cafe-UAS
cd cafe-UAS

# Atau upload via SCP dari local
# scp -r D:\pss\UAS\backend-cafe root@203.175.10.112:/opt/.izzudin/cafe-UAS
```

---

## 3️⃣ Konfigurasi Environment

```bash
cd /opt/.izzudin/cafe-UAS

# Copy .env.example ke .env
cp .env.example .env

# Edit .env
nano .env
```

### Konfigurasi `.env`:

```env
APP_NAME=Laravel
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://203.175.10.112

JWT_SECRET=supersecretjwtkey_1234567890_cafe_app

# Database (sesuai docker-compose.yml)
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=cafe_db
DB_USERNAME=cafe_user
DB_PASSWORD=cafe_secret

# Logging
LOG_LEVEL=error
```

---

## 4️⃣ Setup Docker Files

### Dockerfile

```dockerfile
FROM php:8.2-fpm

WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application
COPY . /var/www

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

EXPOSE 9000
CMD ["php-fpm"]
```

### docker-compose.yml

```yaml
services:
  mysql:
    image: mysql:8.0
    container_name: cafe-mysql
    restart: unless-stopped
    ports:
      - "3306:3306"
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: cafe_db
      MYSQL_USER: cafe_user
      MYSQL_PASSWORD: cafe_secret
    volumes:
      - cafe_mysql_data:/var/lib/mysql
    networks:
      - cafe-network
    healthcheck:
      test: ["CMD", "mysqladmin", "ping", "-h", "localhost"]
      interval: 10s
      timeout: 5s
      retries: 5

  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: cafe-app
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - ./:/var/www
      - ./storage:/var/www/storage
      - ./bootstrap/cache:/var/www/bootstrap/cache
    environment:
      - DB_HOST=mysql
      - DB_PORT=3306
      - DB_DATABASE=cafe_db
      - DB_USERNAME=cafe_user
      - DB_PASSWORD=cafe_secret
    depends_on:
      mysql:
        condition: service_healthy
    networks:
      - cafe-network

  nginx:
    image: nginx:alpine
    container_name: cafe-nginx
    restart: unless-stopped
    ports:
      - "80:80"
    volumes:
      - ./:/var/www
      - ./docker/nginx/nginx.conf:/etc/nginx/nginx.conf
      - ./docker/nginx/conf.d:/etc/nginx/conf.d
    depends_on:
      - app
    networks:
      - cafe-network

networks:
  cafe-network:
    driver: bridge

volumes:
  cafe_mysql_data:
```

### Nginx Configuration

```bash
# Buat direktori nginx
mkdir -p docker/nginx/conf.d

# File: docker/nginx/conf.d/default.conf
cat > docker/nginx/conf.d/default.conf << 'EOF'
server {
    listen 80;
    server_name 203.175.10.112;
    root /var/www/public;
    index index.php index.html;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

# File: docker/nginx/nginx.conf
cat > docker/nginx/nginx.conf << 'EOF'
user nginx;
worker_processes auto;
error_log /var/log/nginx/error.log warn;
pid /var/run/nginx.pid;

events {
    worker_connections 1024;
}

http {
    include /etc/nginx/mime.types;
    default_type application/octet-stream;

    log_format main '$remote_addr - $remote_user [$time_local] "$request" '
                    '$status $body_bytes_sent "$http_referer" '
                    '"$http_user_agent" "$http_x_forwarded_for"';

    access_log /var/log/nginx/access.log main;

    sendfile on;
    tcp_nopush on;
    keepalive_timeout 65;
    gzip on;

    include /etc/nginx/conf.d/*.conf;
}
EOF
```

---

## 5️⃣ Build & Run Docker Compose

```bash
# Set permissions
chmod -R 775 storage bootstrap/cache

# Build dan jalankan container
docker-compose up -d --build

# Monitor logs
docker-compose logs -f
```

---

## 6️⃣ Setup Laravel Application

```bash
# Masuk ke container app
docker exec -it cafe-app bash

# Generate APP_KEY
php artisan key:generate

# Run migrations
php artisan migrate --force

# Seed database (optional)
php artisan db:seed --force

# Cache untuk production
php artisan config:cache
php artisan route:cache

# Keluar dari container
exit
```

---

## 7️⃣ Buat User Admin

```bash
# Buat admin user
docker exec -it cafe-app php artisan tinker --execute "
\$user = App\Models\User::create([
    'nama' => 'Admin Cafe',
    'email' => 'admin@cafe.com',
    'password' => bcrypt('admin123'),
    'role' => 'admin'
]);
echo 'Admin created: ' . \$user->email;
"
```

**Kredensial Admin**:
- Email: `admin@cafe.com`
- Password: `admin123`

---

## 8️⃣ Firewall Configuration (Optional)

```bash
# Izinkan port 80 dan 443
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw reload
```

---

## ✅ Verifikasi Deployment

```bash
# Cek status container
docker-compose ps

# Test akses
curl http://203.175.10.112

# Test API endpoint
curl http://203.175.10.112/api/menu
```

### URL Akses:
- **Homepage**: `http://203.175.10.112`
- **API Base**: `http://203.175.10.112/api`
- **Login**: `http://203.175.10.112/login`
- **Dashboard Admin**: `http://203.175.10.112/dashboard`
- **Reservations**: `http://203.175.10.112/reservations`

---

## 🔧 Troubleshooting

### Error: vendor/autoload.php not found

```bash
docker exec -it cafe-app composer install --no-dev --optimize-autoloader
docker-compose restart app
```

### Error 500: Table not found

```bash
docker exec -it cafe-app php artisan migrate --force
```

### Permission Denied

```bash
docker exec -it cafe-app chmod -R 775 /var/www/storage /var/www/bootstrap/cache
docker exec -it cafe-app chown -R www-data:www-data /var/www
```

### Port Already in Use

```bash
# Cek port yang digunakan
sudo netstat -tulpn | grep :80

# Stop service yang menggunakan port 80
sudo systemctl stop apache2

# Atau ubah port di docker-compose.yml
# ports:
#   - "81:80"  # Gunakan port 81 instead
```

---

## 📌 Useful Commands

```bash
# Stop semua container
docker-compose down

# Restart container
docker-compose restart

# Rebuild setelah update code
docker-compose up -d --build

# Lihat logs real-time
docker-compose logs -f app

# Akses shell container
docker exec -it cafe-app bash

# Clear cache Laravel
docker exec -it cafe-app php artisan cache:clear
docker exec -it cafe-app php artisan config:clear
docker exec -it cafe-app php artisan route:clear

# Backup database
docker exec cafe-mysql mysqldump -u cafe_user -pcafe_secret cafe_db > backup.sql

# Restore database
docker exec -i cafe-mysql mysql -u cafe_user -pcafe_secret cafe_db < backup.sql
```

---

## 🔒 Security Recommendations

1. **Ganti Password Default**:
```bash
docker exec -it cafe-app php artisan tinker --execute "
\$admin = App\Models\User::where('email', 'admin@cafe.com')->first();
\$admin->password = bcrypt('NewStrongPassword123!');
\$admin->save();
"
```

2. **Update JWT Secret**:
```bash
# Generate strong JWT secret
php -r "echo bin2hex(random_bytes(32));"

# Update di .env
JWT_SECRET=<generated-secret>
```

3. **Enable HTTPS** (Production):
- Install Certbot
- Generate SSL certificate
- Update nginx configuration

4. **Database Backup Schedule**:
```bash
# Add to crontab
0 2 * * * docker exec cafe-mysql mysqldump -u cafe_user -pcafe_secret cafe_db > /backup/cafe_$(date +\%Y\%m\%d).sql
```

---

## 📊 Monitoring

```bash
# Check container resource usage
docker stats

# Check logs
docker-compose logs --tail 100 app
docker-compose logs --tail 100 nginx
docker-compose logs --tail 100 mysql
```

---

## 🎉 Deployment Complete!

Aplikasi Laravel Cafe API sudah berhasil di-deploy di:
- **URL**: http://203.175.10.112
- **API**: http://203.175.10.112/api
- **Admin Panel**: http://203.175.10.112/dashboard

Login dengan kredensial admin untuk mulai mengelola aplikasi!
