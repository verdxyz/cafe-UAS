# 🚀 Complete Setup Guide - Laravel Cafe

Panduan lengkap setup aplikasi dari awal hingga selesai dengan data sample.

---

## 📋 Checklist Setup

- [ ] Upload project ke server
- [ ] Fix .env configuration (MySQL)
- [ ] Fix permissions (storage & cache)
- [ ] Run migrations
- [ ] Run seeders (populate data)
- [ ] Test API endpoints
- [ ] Verify login works

---

## 1️⃣ Upload Project ke Server

```bash
# Dari local machine
scp -r /home/lbi/Documents/joki/cafe-UAS root@203.175.10.112:/opt/.izzudin/

# Atau jika sudah ada, upload file penting:
scp /home/lbi/Documents/joki/cafe-UAS/database/seeders/DatabaseSeeder.php \
  root@203.175.10.112:/opt/.izzudin/cafe-UAS/database/seeders/

scp /home/lbi/Documents/joki/cafe-UAS/*.sh \
  root@203.175.10.112:/opt/.izzudin/cafe-UAS/
```

---

## 2️⃣ Login ke Server & Setup

```bash
ssh root@203.175.10.112
cd /opt/.izzudin/cafe-UAS
```

---

## 3️⃣ Fix .env Configuration

**PENTING**: Pastikan menggunakan MySQL, bukan SQLite!

```bash
# Edit .env
nano .env
```

**Ubah konfigurasi database:**

```env
# Ubah dari:
DB_CONNECTION=sqlite

# Menjadi:
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=cafe_db
DB_USERNAME=cafe_user
DB_PASSWORD=cafe_secret
```

Simpan: `Ctrl+X`, `Y`, `Enter`

**Clear cache:**
```bash
docker-compose exec app php artisan config:clear
docker-compose restart app
```

---

## 4️⃣ Fix Permissions

```bash
docker-compose exec app chown -R www-data:www-data /var/www/storage
docker-compose exec app chown -R www-data:www-data /var/www/bootstrap/cache
docker-compose exec app chmod -R 775 /var/www/storage
docker-compose exec app chmod -R 775 /var/www/bootstrap/cache
```

---

## 5️⃣ Run Migrations

```bash
# Fresh migration (akan drop semua tables)
docker-compose exec app php artisan migrate:fresh --force

# Atau jika ingin migrate saja (tanpa drop):
docker-compose exec app php artisan migrate --force
```

---

## 6️⃣ Run Seeders

### Opsi A: Menggunakan Script (Recommended)

```bash
chmod +x run-seeder.sh
./run-seeder.sh
```

### Opsi B: Manual Command

```bash
docker-compose exec app php artisan db:seed --force
```

### Opsi C: Fresh + Seed Sekaligus

```bash
docker-compose exec app php artisan migrate:fresh --seed --force
```

---

## 7️⃣ Verifikasi Data

```bash
# Cek jumlah data
docker-compose exec app php artisan tinker --execute "
echo '========================================' . PHP_EOL;
echo 'DATABASE STATISTICS' . PHP_EOL;
echo '========================================' . PHP_EOL;
echo 'Users: ' . App\Models\User::count() . PHP_EOL;
echo '  - Admin: ' . App\Models\User::where('role', 'admin')->count() . PHP_EOL;
echo '  - Pengunjung: ' . App\Models\User::where('role', 'pengunjung')->count() . PHP_EOL;
echo 'Menu: ' . App\Models\Menu::count() . PHP_EOL;
echo '  - Makanan: ' . App\Models\Menu::where('kategori', 'Makanan')->count() . PHP_EOL;
echo '  - Minuman: ' . App\Models\Menu::where('kategori', 'Minuman')->count() . PHP_EOL;
echo '  - Snack: ' . App\Models\Menu::where('kategori', 'Snack')->count() . PHP_EOL;
echo 'Orders: ' . App\Models\Order::count() . PHP_EOL;
echo 'Reservations: ' . App\Models\Reservation::count() . PHP_EOL;
echo 'Reviews: ' . App\Models\Review::count() . PHP_EOL;
echo '========================================' . PHP_EOL;
"
```

---

## 8️⃣ Test API Endpoints

### Test Homepage

```bash
curl http://203.175.10.112:81
```

### Test Login Admin

```bash
curl -X POST http://203.175.10.112:81/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@cafe.com",
    "password": "admin123"
  }' | jq .
```

### Test Get Menu

```bash
curl http://203.175.10.112:81/api/menu | jq .
```

### Test Get Menu by Category

```bash
# Makanan
curl http://203.175.10.112:81/api/menu?kategori=Makanan | jq .

# Minuman
curl http://203.175.10.112:81/api/menu?kategori=Minuman | jq .

# Snack
curl http://203.175.10.112:81/api/menu?kategori=Snack | jq .
```

### Test Authenticated Endpoint (Orders)

```bash
# 1. Login dulu untuk get token
TOKEN=$(curl -s -X POST http://203.175.10.112:81/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@cafe.com","password":"admin123"}' | jq -r '.data.access_token')

# 2. Get orders dengan token
curl http://203.175.10.112:81/api/orders \
  -H "Authorization: Bearer $TOKEN" | jq .

# 3. Get reservations
curl http://203.175.10.112:81/api/reservations \
  -H "Authorization: Bearer $TOKEN" | jq .
```

---

## 🔐 Akun yang Tersedia

### Admin Account
- **Email**: admin@cafe.com
- **Password**: admin123
- **Role**: admin

### Sample User Accounts (Password: password)
- budi@example.com - Budi Santoso
- siti@example.com - Siti Nurhaliza
- andi@example.com - Andi Wijaya
- dewi@example.com - Dewi Kusuma
- rudi@example.com - Rudi Hartono

### Plus 10 Random Users
- Password semua: `password`

---

## 📊 Data yang Tersedia

### Menu (30 items total)

#### Makanan (10 items)
- Nasi Goreng Spesial - Rp 25,000
- Mie Goreng - Rp 20,000
- Ayam Bakar Madu - Rp 35,000
- Sate Ayam - Rp 30,000
- Rendang Daging - Rp 40,000
- Gado-Gado - Rp 18,000
- Soto Ayam - Rp 22,000
- Bakso Spesial - Rp 20,000
- Nasi Uduk Komplit - Rp 25,000
- Capcay - Rp 23,000

#### Minuman (10 items)
- Es Teh Manis - Rp 5,000
- Kopi Hitam - Rp 8,000
- Cappuccino - Rp 18,000
- Cafe Latte - Rp 20,000
- Jus Jeruk - Rp 12,000
- Jus Alpukat - Rp 15,000
- Es Campur - Rp 18,000
- Teh Tarik - Rp 10,000
- Matcha Latte - Rp 22,000
- Americano - Rp 15,000

#### Snack (10 items)
- Pisang Goreng - Rp 10,000
- Tahu Crispy - Rp 12,000
- Kentang Goreng - Rp 15,000
- Roti Bakar Coklat - Rp 12,000
- Croissant - Rp 18,000
- Donat Coklat - Rp 10,000
- Brownies - Rp 15,000
- Cheesecake - Rp 25,000
- Pancake - Rp 20,000
- Waffle - Rp 22,000

### Orders
- Sample orders dari berbagai users
- Status: pending, selesai, dibatalkan

### Reservations
- Sample reservations dengan berbagai tanggal & waktu

### Reviews
- Sample reviews dengan rating 1-5 stars

---

## 🎯 API Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | /api/auth/login | No | Login user |
| POST | /api/auth/register | No | Register new user |
| POST | /api/auth/logout | Yes | Logout user |
| POST | /api/auth/refresh | Yes | Refresh token |
| GET | /api/menu | No | Get all menu |
| GET | /api/menu/{id} | No | Get menu detail |
| POST | /api/menu | Admin | Create menu |
| PUT | /api/menu/{id} | Admin | Update menu |
| DELETE | /api/menu/{id} | Admin | Delete menu |
| GET | /api/orders | Yes | Get user orders |
| POST | /api/orders | Yes | Create order |
| GET | /api/orders/{id} | Yes | Get order detail |
| PUT | /api/orders/{id} | Yes | Update order |
| DELETE | /api/orders/{id} | Yes | Cancel order |
| GET | /api/reservations | Yes | Get reservations |
| POST | /api/reservations | Yes | Create reservation |
| GET | /api/reservations/{id} | Yes | Get reservation detail |
| PUT | /api/reservations/{id} | Yes | Update reservation |
| DELETE | /api/reservations/{id} | Yes | Cancel reservation |
| GET | /api/reviews | No | Get all reviews |
| POST | /api/reviews | Yes | Create review |
| GET | /api/reviews/{id} | No | Get review detail |
| PUT | /api/reviews/{id} | Yes | Update review |
| DELETE | /api/reviews/{id} | Yes/Admin | Delete review |

---

## 🛠️ Troubleshooting

### Problem: SQLite readonly database error
**Solution:**
```bash
# Edit .env, ubah DB_CONNECTION=sqlite ke mysql
nano .env
# Clear cache
docker-compose exec app php artisan config:clear
docker-compose restart app
```

### Problem: Permission denied on storage
**Solution:**
```bash
./fix-permissions.sh
# Or manual:
docker-compose exec app chown -R www-data:www-data /var/www/storage
docker-compose exec app chmod -R 775 /var/www/storage
```

### Problem: vendor/autoload.php not found
**Solution:**
```bash
docker-compose exec app composer install --no-dev --optimize-autoloader
```

### Problem: Database connection failed
**Solution:**
```bash
# Check .env
docker-compose exec app cat .env | grep DB_
# Test connection
docker-compose exec app php artisan tinker --execute "DB::connection()->getPdo();"
```

---

## 🔄 Reset Database

Jika ingin reset semua dan mulai dari awal:

```bash
# Fresh + Seed
docker-compose exec app php artisan migrate:fresh --seed --force

# Atau menggunakan script
./run-seeder.sh
```

---

## 📚 Documentation Files

- `DEPLOY_README.md` - Quick start deployment
- `DEPLOYMENT.md` - Full deployment guide
- `DEPLOYMENT_SUMMARY.md` - Troubleshooting & tips
- `VISUAL_GUIDE.md` - Architecture diagrams
- `SEEDER_INFO.md` - Seeder details
- `SEED_COMMANDS.txt` - Quick commands for seeding
- `COMPLETE_SETUP.md` - This file!

---

## ✅ Final Checklist

- [ ] .env configured with MySQL
- [ ] Permissions fixed (storage & cache)
- [ ] Migrations completed
- [ ] Seeders run successfully
- [ ] Admin login works
- [ ] User login works
- [ ] Menu API returns data
- [ ] Orders API works (with auth)
- [ ] Reservations API works (with auth)

---

**Setup Complete! 🎉**

Aplikasi Laravel Cafe siap digunakan dengan data sample lengkap!

**Akses**: http://203.175.10.112:81
**Admin**: admin@cafe.com / admin123
