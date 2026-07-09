# 🔧 Fix: fake() Function Error

## ❌ Problem

```
Call to undefined function Database\Factories\fake()
```

Error ini terjadi karena **helper function `fake()` tidak tersedia** di environment Anda.

---

## ✅ Solusi yang Sudah Diterapkan

Semua file Factory sudah diperbaiki untuk menggunakan `$this->faker` instead of `fake()`:

### Files yang Diperbaiki:
- ✅ `database/seeders/DatabaseSeeder.php`
- ✅ `database/factories/UserFactory.php`
- ✅ `database/factories/MenuFactory.php`
- ✅ `database/factories/OrderFactory.php`
- ✅ `database/factories/ReservationFactory.php`
- ✅ `database/factories/ReviewFactory.php`

### Perubahan:
```php
// ❌ Before (Error)
'nama' => fake()->name()

// ✅ After (Fixed)
'nama' => $this->faker->name()
```

---

## 📤 Upload File yang Sudah Diperbaiki

### Metode 1: Menggunakan Script Upload

```bash
# Dari local machine
cd /home/lbi/Documents/joki/cafe-UAS
chmod +x upload-fixed-files.sh
./upload-fixed-files.sh
```

### Metode 2: Manual SCP

```bash
# Upload DatabaseSeeder
scp database/seeders/DatabaseSeeder.php \
  root@203.175.10.112:/opt/.izzudin/cafe-UAS/database/seeders/

# Upload Factory files
scp database/factories/*.php \
  root@203.175.10.112:/opt/.izzudin/cafe-UAS/database/factories/
```

---

## 🚀 Run Seeder di Server (Setelah Upload)

```bash
# Login ke server
ssh root@203.175.10.112
cd /opt/.izzudin/cafe-UAS

# Run seeder
docker-compose exec app php artisan migrate:fresh --seed --force
```

---

## 🧪 Verifikasi Setelah Seeding

```bash
# Test data
docker-compose exec app php artisan tinker --execute "
echo 'Users: ' . App\Models\User::count() . PHP_EOL;
echo 'Menu: ' . App\Models\Menu::count() . PHP_EOL;
echo 'Orders: ' . App\Models\Order::count() . PHP_EOL;
"

# Test login
curl -X POST http://203.175.10.112:81/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@cafe.com","password":"admin123"}'
```

---

## 📝 Detail Perubahan

### UserFactory.php
```php
// Before
fake()->name()
fake()->unique()->safeEmail()

// After
$this->faker->name()
$this->faker->unique()->safeEmail()
```

### MenuFactory.php
```php
// Before
fake()->randomElement(array_keys($menuItems))
fake()->randomFloat(2, 5000, 150000)

// After
$this->faker->randomElement(array_keys($menuItems))
$this->faker->randomFloat(2, 5000, 150000)
```

### OrderFactory.php
```php
// Before
fake()->numberBetween(1, 10)
fake()->randomElement(['pending', 'selesai', 'dibatalkan'])

// After
$this->faker->numberBetween(1, 10)
$this->faker->randomElement(['pending', 'selesai', 'dibatalkan'])
```

### ReservationFactory.php
```php
// Before
fake()->dateTimeBetween('now', '+30 days')
fake()->numberBetween(1, 20)

// After
$this->faker->dateTimeBetween('now', '+30 days')
$this->faker->numberBetween(1, 20)
```

### ReviewFactory.php
```php
// Before
fake()->numberBetween(1, 5)
fake()->sentence()

// After
$this->faker->numberBetween(1, 5)
$this->faker->sentence()
```

### DatabaseSeeder.php
```php
// Before
fake()->numberBetween(1, 4)

// After
rand(1, 4)
```

---

## ℹ️ Penjelasan

- **`fake()`** adalah helper function yang hanya tersedia di Laravel 9+
- **`$this->faker`** adalah property yang tersedia di semua versi Laravel Factory
- Menggunakan `$this->faker` lebih kompatibel dengan berbagai versi Laravel

---

## ✅ Expected Result

Setelah upload dan run seeder, database akan terisi dengan:

- 1 Admin user (admin@cafe.com / admin123)
- 15 Regular users (password: password)
- 30 Menu items (Makanan, Minuman, Snack)
- Sample Orders
- Sample Reservations
- Sample Reviews

---

**Problem Fixed! 🎉**

Upload file yang sudah diperbaiki ke server dan run seeder lagi.
