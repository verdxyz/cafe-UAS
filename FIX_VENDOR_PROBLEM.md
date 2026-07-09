# 🔧 Fix: vendor/autoload.php Not Found

## ❌ Problem

```
Fatal error: Failed opening required '/var/www/vendor/autoload.php'
```

Ini terjadi karena **composer dependencies belum terinstall** di dalam container.

---

## ✅ Solusi Cepat (Di Server)

### Metode 1: Gunakan Fix Script (Recommended)

```bash
# Di server VPS
cd /opt/.izzudin/cafe-UAS

# Jalankan fix script
chmod +x fix-vendor.sh
./fix-vendor.sh
```

Script ini akan otomatis:
- Install composer dependencies
- Set permissions
- Generate APP_KEY
- Run migrations
- Cache configuration

---

### Metode 2: Manual Commands

```bash
# 1. Install composer dependencies
docker-compose exec app composer install --no-dev --optimize-autoloader

# 2. Set permissions
docker-compose exec app chown -R www-data:www-data /var/www
docker-compose exec app chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# 3. Generate APP_KEY
docker-compose exec app php artisan key:generate --force

# 4. Run migrations
docker-compose exec app php artisan migrate --force

# 5. Cache configuration
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
```

---

## 🧪 Verifikasi

```bash
# Test Laravel version
docker-compose exec app php artisan --version

# Output yang benar:
# Laravel Framework 12.x.x

# Test API
curl http://203.175.10.112:81/api/menu
```

---

## 📝 Catatan Port

Saya lihat di output Anda nginx berjalan di **port 81**, bukan 80:
```
0.0.0.0:81->80/tcp
```

Artinya akses aplikasi menggunakan:
- **Homepage**: `http://203.175.10.112:81`
- **API**: `http://203.175.10.112:81/api`

Jika ingin menggunakan port 80 (tanpa :81), edit `docker-compose.yml`:
```yaml
nginx:
  ports:
    - "80:80"  # Ubah dari 81:80 menjadi 80:80
    - "443:443"  # Ubah dari 446:443 menjadi 443:443
```

Kemudian restart:
```bash
docker-compose down
docker-compose up -d
```

---

## 🔄 Untuk Deployment Selanjutnya

File sudah diperbaiki:
- ✅ `Dockerfile` - Disederhanakan, tidak copy files
- ✅ `deploy.sh` - Tambah step install composer dependencies
- ✅ `fix-vendor.sh` - Script untuk fix masalah vendor

Upload file-file yang sudah diperbaiki ke server dan deployment berikutnya akan berjalan lancar.

---

## 📤 Upload File yang Sudah Diperbaiki

```bash
# Dari local machine
scp -r /home/lbi/Documents/joki/cafe-UAS/* root@203.175.10.112:/opt/.izzudin/cafe-UAS/

# Atau file specific
scp /home/lbi/Documents/joki/cafe-UAS/Dockerfile root@203.175.10.112:/opt/.izzudin/cafe-UAS/
scp /home/lbi/Documents/joki/cafe-UAS/deploy.sh root@203.175.10.112:/opt/.izzudin/cafe-UAS/
scp /home/lbi/Documents/joki/cafe-UAS/fix-vendor.sh root@203.175.10.112:/opt/.izzudin/cafe-UAS/
```

---

## 🎯 Next Steps di Server

```bash
# 1. Jalankan fix
./fix-vendor.sh

# 2. Buat admin user
./create-admin.sh

# 3. Test akses (sesuaikan port!)
curl http://203.175.10.112:81
curl http://203.175.10.112:81/api/menu

# 4. Test login
curl -X POST http://203.175.10.112:81/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@cafe.com",
    "password": "admin123"
  }'
```

---

**Problem solved! 🎉**
