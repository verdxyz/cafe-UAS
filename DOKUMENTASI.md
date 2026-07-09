# Backend Cafe API — Dokumentasi

**Mata Kuliah**: Pemrograman Sisi Server | **Dosen**: Ajib Susanto, M.Kom
**Kelompok**: A11.4601, 4617, 46RPL | **Stack**: Laravel 12, PHP 8.2, JWT, Pest

---

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
# Tambahkan JWT_SECRET=... di .env (min. 32 karakter)
php artisan migrate --seed
php artisan serve
```

Base URL: `http://localhost:8000/api`

---

## 1. Desain Database & Model

### ERD Relasi

```
users ──< orders >── menus
users ──< reservations
users ──< reviews >── menus
users ──< refresh_tokens
```

### Schema Tabel

**users**
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT PK | |
| nama | VARCHAR(100) | |
| email | VARCHAR(100) UNIQUE | |
| password | VARCHAR | Bcrypt hashed |
| role | ENUM | `admin` / `pengunjung` (default) |

**menus**
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT PK | |
| nama | VARCHAR(100) | |
| kategori | VARCHAR(50) | Index untuk filter cepat |
| harga | DECIMAL(10,2) | |
| stok | INTEGER | |

**orders**
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT PK | |
| user_id | FK → users | |
| menu_id | FK → menus | |
| jumlah | INTEGER | |
| status | ENUM | `pending` / `selesai` / `dibatalkan` |
| tanggal | TIMESTAMP | Default: waktu sekarang |

**reservations**
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT PK | |
| user_id | FK → users | |
| tanggal | DATE | Harus hari ini atau masa depan |
| jam | TIME | Format HH:MM |
| jumlah_orang | INTEGER | 1–50 |
| status | ENUM | `pending` / `confirmed` / `cancelled` |

**reviews**
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT PK | |
| user_id | FK → users | |
| menu_id | FK → menus | |
| rating | TINYINT | 1–5 |
| komentar | TEXT | Nullable |
| tanggal | TIMESTAMP | |

**refresh_tokens**
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT PK | |
| user_id | FK → users | Cascade delete |
| token | VARCHAR UNIQUE | Random 60 karakter |
| expires_at | TIMESTAMP | Expired dalam 7 hari |

---

## 2. API Endpoints

Header untuk endpoint yang butuh auth:
```
Authorization: Bearer <access_token>
```

### Auth (Publik)
| Method | Endpoint | Keterangan |
|---|---|---|
| POST | `/auth/register` | Daftar user baru (throttle 10/menit) |
| POST | `/auth/login` | Login → dapat JWT + refresh token (throttle 10/menit) |
| POST | `/auth/refresh` | Perbarui access token pakai refresh token |
| POST | `/auth/logout` | Logout, hapus semua refresh token milik user |

### Menu
| Method | Endpoint | Auth | Keterangan |
|---|---|---|---|
| GET | `/menu` | - | List menu (publik, support filter & pagination) |
| GET | `/menu/{id}` | - | Detail menu |
| POST | `/menu` | Admin | Tambah menu |
| PUT | `/menu/{id}` | Admin | Update menu |
| DELETE | `/menu/{id}` | Admin | Hapus menu |

### Order
| Method | Endpoint | Auth | Keterangan |
|---|---|---|---|
| GET | `/orders` | JWT | List order (admin: semua, pengunjung: milik sendiri) |
| GET | `/orders/report` | JWT | Laporan omzet per periode |
| GET | `/orders/{id}` | JWT | Detail order |
| POST | `/orders` | JWT | Buat order (stok otomatis berkurang) |
| PUT | `/orders/{id}` | JWT | Update (pengunjung: hanya pending milik sendiri) |
| DELETE | `/orders/{id}` | Admin | Hapus order (stok dikembalikan) |

### Reservation
| Method | Endpoint | Auth | Keterangan |
|---|---|---|---|
| GET | `/reservations` | JWT | List reservasi |
| GET | `/reservations/{id}` | JWT | Detail reservasi |
| POST | `/reservations` | JWT | Buat reservasi |
| PUT | `/reservations/{id}` | JWT | Update (pengunjung tidak bisa ubah status) |
| DELETE | `/reservations/{id}` | Admin | Hapus reservasi |

### Review
| Method | Endpoint | Auth | Keterangan |
|---|---|---|---|
| GET | `/reviews` | - | List review (publik) |
| GET | `/reviews/{id}` | - | Detail review |
| POST | `/reviews` | JWT | Buat review |
| PUT | `/reviews/{id}` | Owner | Update review milik sendiri |
| DELETE | `/reviews/{id}` | Admin | Hapus review |

---

## 3. Autentikasi (JWT)

### Alur Login
1. Client POST `/auth/login` → Server kembalikan `token` (1 jam) + `refresh_token` (7 hari)
2. Client kirim `Authorization: Bearer <token>` di setiap request
3. Token expired? POST `/auth/refresh` dengan `refresh_token` → dapat token baru
4. Logout: POST `/auth/logout` → semua refresh token user dihapus dari DB

### Contoh Login
```json
// Request
POST /api/auth/login
{ "email": "budi@example.com", "password": "rahasia123" }

// Response
{
  "token": "eyJ0eXAiOiJKV1Q...",
  "refresh_token": "abc123xyz...",
  "user": { "id": 1, "nama": "Budi", "role": "pengunjung" }
}
```

### Middleware
- `jwt.auth` — Decode JWT, bind user ke request. Tolak jika token tidak ada/invalid/expired.
- `role:admin` — Cek `user->role === 'admin'`. Kembalikan 403 jika bukan admin.

```php
// Contoh penerapan di routes/api.php
Route::middleware(['jwt.auth', 'role:admin'])->group(function () {
    Route::post('/menu', [MenuController::class, 'store']);
});
```

---

## 4. Throttling, Pagination & Filtering

### Throttling
Dikonfigurasi di `AppServiceProvider.php`:

| Limiter | Batas | Berlaku Untuk |
|---|---|---|
| `api` | 100 req / jam | Semua endpoint (by user ID atau IP) |
| `auth` | 10 req / menit | POST `/auth/login` dan `/auth/register` |

Response saat limit tercapai (HTTP 429):
```json
{ "message": "Too many requests, please try again later." }
```

### Pagination
Semua endpoint list mendukung query `?page=1&limit=10`.

```json
// Format response
{
  "data": [ ... ],
  "filters": { "category": "coffee" },
  "pagination": { "page": 1, "limit": 10, "total": 43, "totalPages": 5 }
}
```

### Filtering
| Endpoint | Parameter tersedia |
|---|---|
| `GET /menu` | `?category=coffee` `?search=kopi` |
| `GET /orders` | `?status=pending` `?date=2026-07-07` |
| `GET /reservations` | `?status=confirmed` `?date=2026-07-15` |
| `GET /reviews` | `?menu_id=1` `?rating=5` `?user_id=3` |

---

## 5. Unit Testing

### Menjalankan Test
```bash
php artisan test --compact             # semua test
php artisan test --compact --filter="registers a user"  # test tertentu
```

### Setup Test
`tests/Pest.php` — konfigurasi global:
```php
// Semua Feature test pakai RefreshDatabase (DB reset tiap test)
pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature');

// Helper: kirim request dengan JWT token
function actAsJwt(\App\Models\User $user) {
    $token = \Firebase\JWT\JWT::encode([
        'id' => $user->id, 'email' => $user->email, 'role' => $user->role,
        'iat' => time(), 'exp' => time() + 3600,
    ], env('JWT_SECRET', 'test-secret'), 'HS256');

    return test()->withToken($token);
}
```

### Contoh Test

**Auth:**
```php
it('registers a user successfully', function () {
    postJson('/api/auth/register', [
        'nama' => 'Budi', 'email' => 'budi@example.com', 'password' => '123456',
    ])->assertCreated();
});

it('fails login with wrong password', function () {
    User::factory()->create(['email' => 'a@b.com', 'password' => bcrypt('correct')]);
    postJson('/api/auth/login', ['email' => 'a@b.com', 'password' => 'wrong'])
        ->assertStatus(401);
});
```

**Menu (pagination & filter):**
```php
it('reads menu list with pagination', function () {
    Menu::factory()->count(15)->create();
    $response = getJson('/api/menu?limit=5');
    expect($response->json('data'))->toHaveCount(5)
        ->and($response->json('pagination.total'))->toBe(15);
});
```

**Order (business logic):**
```php
it('decrements stock when order is created', function () {
    $user = User::factory()->create();
    $menu = Menu::factory()->create(['stok' => 10]);

    actAsJwt($user)->postJson('/api/orders', ['menu_id' => $menu->id, 'jumlah' => 2])
        ->assertCreated();

    expect($menu->fresh()->stok)->toBe(8);
});

it('returns 403 when updating another user order', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $order = Order::factory()->create(['user_id' => $user2->id, 'status' => 'pending']);

    actAsJwt($user1)->putJson("/api/orders/{$order->id}", ['jumlah' => 2])
        ->assertForbidden();
});
```

**Reservation (validasi tanggal):**
```php
it('fails with past date', function () {
    $user = User::factory()->create();
    actAsJwt($user)->postJson('/api/reservations', [
        'tanggal' => now()->subDay()->format('Y-m-d'), 'jam' => '19:00', 'jumlah_orang' => 4,
    ])->assertStatus(422);
});
```

---

## 6. Screenshot Aplikasi

### CRUD Management (Admin Dashboard)

Aplikasi ini dilengkapi dengan dashboard admin untuk mengelola data cafe secara visual.

#### 1. Menu Management
![Menu CRUD](https://i.imgur.com/menu-crud.png)

Halaman **Menu Management** memungkinkan admin untuk:
- Melihat daftar lengkap menu cafe dengan pagination
- Menambah menu baru dengan tombol **+ ADD MENU**
- Mengedit informasi menu (nama, kategori, harga, stok)
- Menghapus menu yang tidak tersedia
- Filter dan pencarian menu berdasarkan kategori dan nama

**Fitur Throttling**: Endpoint ini memiliki limit 100 request per jam untuk mencegah spam.

**Fitur Pagination**: Data ditampilkan dengan pagination (default 10 item per halaman), dapat diatur dengan query `?page=1&limit=20`.

**Fitur Filtering**: Mendukung filter berdasarkan kategori (`?category=makanan`) dan pencarian nama (`?search=kopi`).

---

#### 2. Orders Management
![Orders CRUD](https://i.imgur.com/orders-crud.png)

Halaman **Orders Management** untuk mengelola pesanan customer:
- Melihat semua order dengan informasi customer, menu, jumlah, dan total harga
- Update status order: `PENDING`, `SELESAI`, `DIBATALKAN`
- Filter berdasarkan status dan tanggal pesanan
- Admin dapat mengelola semua order, pengunjung hanya bisa melihat order milik sendiri

**Business Logic**: Saat order dibuat, stok menu otomatis berkurang. Saat order dibatalkan/dihapus, stok dikembalikan.

**Query Optimization**: Menggunakan eager loading `with(['user', 'menu'])` untuk mengurangi N+1 query problem.

---

#### 3. Reservations Management
![Reservations CRUD](https://i.imgur.com/reservations-crud.png)

Halaman **Reservations Management** untuk mengelola booking meja:
- Lihat daftar reservasi dengan nama customer, tanggal, waktu, jumlah tamu
- Kelola status: `PENDING`, `CONFIRMED`, `CANCELLED`
- Validasi otomatis: tanggal reservasi tidak boleh di masa lalu
- Filter berdasarkan status dan tanggal

**Validasi**: 
- Tanggal minimal hari ini (`after_or_equal:today`)
- Jumlah orang: 1-50 orang
- Format jam: HH:MM (contoh: 19:00)

---

### User Interface (Customer Portal)

#### 4. Login Page
![Login Page](https://i.imgur.com/login-page.png)

Halaman login dengan desain minimalis dan modern:
- Login menggunakan email dan password
- **JWT Authentication**: Setelah login berhasil, user mendapat `access_token` (expired 1 jam) dan `refresh_token` (expired 7 hari)
- **Throttling Protection**: Maksimal 10 percobaan login per menit untuk mencegah brute force attack
- Link registrasi untuk user baru

**Security**: Password di-hash menggunakan bcrypt dengan 12 rounds. Token JWT ditandatangani dengan secret key dari environment.

---

#### 5. Menu Catalog (Customer View)
![Menu Catalog](https://i.imgur.com/menu-catalog.png)

Halaman katalog menu untuk customer dengan fitur lengkap:
- Tampilan grid yang menarik dengan gambar produk
- **Filter by Category**: Button filter `All`, `Makanan`, `Minuman`, `Snack`, `Coffee`
- **Real-time Stock Display**: Menampilkan harga dan ketersediaan stok
- Tombol **ORDER** untuk setiap item
- Design responsif dengan Tailwind CSS

**API Endpoint**: `GET /api/menu?category=coffee&limit=10`

**Fitur Pagination**: Support infinite scroll atau numbered pagination dengan parameter `?page=2`.

**Fitur Filtering**: 
- Filter kategori: `?category=minuman`
- Pencarian: `?search=latte`
- Kombinasi: `?category=coffee&search=espresso&limit=20`

**Response Format**:
```json
{
  "data": [
    {
      "id": 1,
      "nama": "ropag",
      "kategori": "Makanan",
      "harga": "10000.00",
      "stok": 9
    }
  ],
  "filters": {
    "category": "coffee",
    "search": null
  },
  "pagination": {
    "page": 1,
    "limit": 10,
    "total": 22,
    "totalPages": 3
  }
}
```

---

#### 6. Reservation Page (Customer Interface)

Halaman **Reservation** untuk customer membuat booking meja dengan fitur lengkap:

**URL**: `/reservations` (memerlukan login)

**Fitur Utama**:
- **Form Reservasi Interaktif**: Input tanggal, waktu, dan jumlah tamu
- **Real-time Validation**: Validasi tanggal tidak boleh di masa lalu, jumlah tamu 1-50 orang
- **Auto-fill User Info**: Nama dan email otomatis terisi dari data user yang login
- **My Reservations Table**: Daftar semua reservasi user dengan status terkini
- **Cancel Reservation**: User dapat membatalkan reservasi yang masih berstatus "pending"
- **Responsive Design**: Mobile-friendly dengan Tailwind CSS

**Form Fields**:
```javascript
{
  "tanggal_reservasi": "2026-07-15 19:00:00", // YYYY-MM-DD HH:MM:SS
  "jumlah_orang": 4                             // 1-50
}
```

**Validasi Frontend**:
- Tanggal minimal: hari ini (tidak bisa pilih tanggal lampau)
- Jumlah tamu: min 1, max 50
- Waktu: format HH:MM
- User harus login terlebih dahulu

**Status Reservasi**:
- `pending` - Menunggu konfirmasi (warna abu-abu)
- `confirmed` - Sudah dikonfirmasi admin (warna hitam tebal)
- `cancelled` - Dibatalkan (warna merah)

**User Actions**:
- User hanya bisa **cancel** reservasi dengan status `pending`
- Reservasi `confirmed` dan `cancelled` tidak bisa diubah
- Refresh list reservasi dengan tombol refresh

**API Integration**:
```javascript
// Create Reservation
POST /api/reservations
Authorization: Bearer {token}
{
  "tanggal_reservasi": "2026-07-15 19:00:00",
  "jumlah_orang": 4
}

// Get My Reservations
GET /api/reservations
Authorization: Bearer {token}

// Cancel Reservation
PUT /api/reservations/{id}
Authorization: Bearer {token}
{
  "status": "cancelled"
}
```

**Information Section**:
- Operating hours
- Location details
- Reservation policies
- Contact information

---

## 7. Referensi

| Topik | Link |
|---|---|
| Laravel 12 Docs | https://laravel.com/docs/12.x |
| Eloquent ORM | https://laravel.com/docs/12.x/eloquent |
| API Resources | https://laravel.com/docs/12.x/eloquent-resources |
| Rate Limiting | https://laravel.com/docs/12.x/routing#rate-limiting |
| Pest Testing | https://pestphp.com/docs |
| firebase/php-jwt | https://github.com/firebase/php-jwt |
