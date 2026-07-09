# 🌱 Database Seeder Information

## 📦 Data yang Akan Dibuat

### 👤 Users
- **1 Admin User**
  - Nama: Admin Cafe
  - Email: `admin@cafe.com`
  - Password: `admin123`
  - Role: admin

- **5 Sample Users** (Password semua: `password`)
  - budi@example.com - Budi Santoso
  - siti@example.com - Siti Nurhaliza
  - andi@example.com - Andi Wijaya
  - dewi@example.com - Dewi Kusuma
  - rudi@example.com - Rudi Hartono

- **10 Random Users** (Password semua: `password`)
  - Random names dan emails

### 🍽️ Menu Items (30 items)

#### Makanan (10 items)
1. Nasi Goreng Spesial - Rp 25,000
2. Mie Goreng - Rp 20,000
3. Ayam Bakar Madu - Rp 35,000
4. Sate Ayam (10 tusuk) - Rp 30,000
5. Rendang Daging - Rp 40,000
6. Gado-Gado - Rp 18,000
7. Soto Ayam - Rp 22,000
8. Bakso Spesial - Rp 20,000
9. Nasi Uduk Komplit - Rp 25,000
10. Capcay - Rp 23,000

#### Minuman (10 items)
1. Es Teh Manis - Rp 5,000
2. Kopi Hitam - Rp 8,000
3. Cappuccino - Rp 18,000
4. Cafe Latte - Rp 20,000
5. Jus Jeruk - Rp 12,000
6. Jus Alpukat - Rp 15,000
7. Es Campur - Rp 18,000
8. Teh Tarik - Rp 10,000
9. Matcha Latte - Rp 22,000
10. Americano - Rp 15,000

#### Snack (10 items)
1. Pisang Goreng - Rp 10,000
2. Tahu Crispy - Rp 12,000
3. Kentang Goreng - Rp 15,000
4. Roti Bakar Coklat - Rp 12,000
5. Croissant - Rp 18,000
6. Donat Coklat - Rp 10,000
7. Brownies - Rp 15,000
8. Cheesecake - Rp 25,000
9. Pancake - Rp 20,000
10. Waffle - Rp 22,000

### 📦 Orders
- Random orders dari users
- Status: pending, selesai, atau dibatalkan
- Tanggal: 30 hari terakhir

### 📅 Reservations
- Random reservations dari users
- Tanggal & waktu bervariasi
- Jumlah orang: 2-10 orang

### ⭐ Reviews
- Random reviews dari users untuk menu items
- Rating: 1-5 stars
- Komentar sample

---

## 🚀 Cara Menjalankan Seeder

### Metode 1: Menggunakan Script (Recommended)

```bash
# Di server
cd /opt/.izzudin/cafe-UAS

# Jalankan seeder script
./run-seeder.sh
```

Script akan:
1. Check container status
2. Test database connection
3. Konfirmasi sebelum seeding
4. Run `migrate:fresh` (drop all tables & recreate)
5. Run seeders
6. Tampilkan summary

### Metode 2: Manual Commands

```bash
# Fresh migration (WARNING: Hapus semua data!)
docker-compose exec app php artisan migrate:fresh --force

# Run seeder
docker-compose exec app php artisan db:seed --force
```

### Metode 3: Seed tanpa Hapus Data

```bash
# Hanya run seeder (tanpa hapus data existing)
docker-compose exec app php artisan db:seed --force
```

⚠️ **PERHATIAN**: Jika run seeder tanpa `migrate:fresh`, bisa terjadi duplicate data!

---

## 🧪 Testing Setelah Seeding

### 1. Test Login Admin

```bash
curl -X POST http://203.175.10.112:81/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@cafe.com",
    "password": "admin123"
  }'
```

### 2. Test Login User Biasa

```bash
curl -X POST http://203.175.10.112:81/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "budi@example.com",
    "password": "password"
  }'
```

### 3. Test Get Menu

```bash
curl http://203.175.10.112:81/api/menu
```

### 4. Test Get Orders (dengan token)

```bash
# Simpan token dari response login
TOKEN="your_access_token_here"

curl http://203.175.10.112:81/api/orders \
  -H "Authorization: Bearer $TOKEN"
```

---

## 📊 Verifikasi Data

```bash
# Cek jumlah users
docker-compose exec app php artisan tinker --execute "
echo 'Total Users: ' . App\Models\User::count() . PHP_EOL;
echo 'Admin Users: ' . App\Models\User::where('role', 'admin')->count() . PHP_EOL;
echo 'Regular Users: ' . App\Models\User::where('role', 'pengunjung')->count() . PHP_EOL;
"

# Cek jumlah menu
docker-compose exec app php artisan tinker --execute "
echo 'Total Menu: ' . App\Models\Menu::count() . PHP_EOL;
echo 'Makanan: ' . App\Models\Menu::where('kategori', 'Makanan')->count() . PHP_EOL;
echo 'Minuman: ' . App\Models\Menu::where('kategori', 'Minuman')->count() . PHP_EOL;
echo 'Snack: ' . App\Models\Menu::where('kategori', 'Snack')->count() . PHP_EOL;
"

# Cek jumlah orders
docker-compose exec app php artisan tinker --execute "
echo 'Total Orders: ' . App\Models\Order::count() . PHP_EOL;
"

# Cek jumlah reservations
docker-compose exec app php artisan tinker --execute "
echo 'Total Reservations: ' . App\Models\Reservation::count() . PHP_EOL;
"

# Cek jumlah reviews
docker-compose exec app php artisan tinker --execute "
echo 'Total Reviews: ' . App\Models\Review::count() . PHP_EOL;
"
```

---

## 🔐 Daftar Akun

### Admin Account
| Email | Password | Role |
|-------|----------|------|
| admin@cafe.com | admin123 | admin |

### Sample User Accounts (Password: password)
| Email | Nama | Role |
|-------|------|------|
| budi@example.com | Budi Santoso | pengunjung |
| siti@example.com | Siti Nurhaliza | pengunjung |
| andi@example.com | Andi Wijaya | pengunjung |
| dewi@example.com | Dewi Kusuma | pengunjung |
| rudi@example.com | Rudi Hartono | pengunjung |

Plus 10 random users dengan password: `password`

---

## 🔄 Re-seed (Reset Database)

Jika ingin reset database dan seed ulang:

```bash
# Metode 1: Menggunakan script
./run-seeder.sh

# Metode 2: Manual
docker-compose exec app php artisan migrate:fresh --seed --force
```

---

## ⚠️ IMPORTANT NOTES

1. **Backup Data**: Sebelum run seeder, pastikan backup data existing jika ada
2. **Production**: Jangan run `migrate:fresh` di production! Akan hapus semua data!
3. **Development**: Seeder ini cocok untuk development dan testing
4. **Password Default**: Ganti password admin dan users di production

---

## 📤 Upload File ke Server

Jika belum upload seeder yang baru:

```bash
# Dari local machine
scp /home/lbi/Documents/joki/cafe-UAS/database/seeders/DatabaseSeeder.php \
  root@203.175.10.112:/opt/.izzudin/cafe-UAS/database/seeders/

scp /home/lbi/Documents/joki/cafe-UAS/run-seeder.sh \
  root@203.175.10.112:/opt/.izzudin/cafe-UAS/

# Di server, beri permission
chmod +x /opt/.izzudin/cafe-UAS/run-seeder.sh
```

---

**Happy Seeding! 🌱**
