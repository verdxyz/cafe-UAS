# Backend Website Café

Aplikasi backend open source untuk manajemen café (menu, order, reservasi, review) dengan fitur autentikasi JWT, role-based access, throttling, pagination, filtering, unit testing, dan dokumentasi Swagger.

## 🚀 Fitur Utama
- **Auth**: Register/login menggunakan JSON Web Token (JWT) + Refresh Token.
- **CRUD Entities**: Menu, Order, Reservation, dan Review.
- **Role-based Access Control (RBAC)**: Otorisasi khusus untuk `admin` dan `pengunjung`.
- **API Optimizations**: Throttling (Rate Limiting), Pagination, dan Filtering bawaan di setiap endpoint data.
- **Unit Testing**: Diuji secara komprehensif menggunakan Pest PHP dengan *100% Pass Rate*.
- **API Documentation**: Dokumentasi interaktif dengan Swagger/OpenAPI.

## 📂 Struktur Folder
```
\ 
├── app/                  # Controller, Model, Request, Resource, Middleware, Trait
├── bootstrap/            # Konfigurasi app (termasuk pendaftaran middleware & throttle)
├── config/               # File konfigurasi aplikasi
├── database/             # Migrasi, Seeder, dan Factory
├── public/docs/          # Lokasi file api-docs.yaml untuk Swagger
├── resources/views/      # Tampilan Swagger UI (docs.blade.php)
├── routes/               # Routes API dan Web
├── tests/                # Test cases menggunakan Pest PHP
├── docker-compose.yml    # Konfigurasi Docker (MySQL 8.0)
└── README.md             # Dokumentasi ini
```

## 🛠️ Petunjuk Instalasi & Konfigurasi

### Persyaratan:
- PHP 8.2+
- Composer
- Database: MySQL 8.0 (Bisa via Docker)
- Docker (opsional, untuk database)

### Langkah Instalasi:

1. Clone repositori dan masuk ke direktori proyek:
```bash
git clone https://github.com/verdxyz/UAS-Pemograman-Sisi-Server.git
cd UAS-Pemograman-Sisi-Server
```

2. Install dependensi PHP dengan Composer:
```bash
composer install
```

3. Konfigurasi Environment:
```bash
cp .env.example .env
```
Sesuaikan `.env` dengan koneksi database dan setup rahasia JWT:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cafe_db
DB_USERNAME=root
DB_PASSWORD=password

JWT_SECRET=your_super_secret_jwt_key
```

4. Generate Application Key:
```bash
php artisan key:generate
```

## 💻 Cara Menjalankan

### Development Lokal:
Pastikan database sudah menyala, lalu jalankan migrasi, seeder, dan server.
```bash
# Migrasi dan insert data dummy
php artisan migrate --seed

# Jalankan server
php artisan serve
```

### Menjalankan via Docker (Database):
Bila kamu tidak menginstal MySQL lokal, kamu bisa memanfaatkan konfigurasi Docker bawaan:
```bash
docker-compose up -d
php artisan migrate --seed
php artisan serve
```

## 📖 Dokumentasi API (Swagger)

Akses dokumentasi lengkap secara interaktif (Swagger UI) dengan menjalankan server lalu buka di browser:

[http://localhost:8000/docs](http://localhost:8000/docs)

*(File spesifikasi OpenAPI ada di `public/api-docs.yaml`)*

## 🤝 Kontribusi

Pull requests dipersilahkan. Untuk perubahan besar, harap buka "Issue" terlebih dahulu untuk mendiskusikan apa yang ingin kamu ubah. Pastikan kamu selalu memperbarui tes saat berkontribusi.

Jalankan testing sebelum submit PR:
```bash
php artisan test
```

## 📜 Lisensi
Proyek ini di bawah lisensi [MIT License](https://opensource.org/licenses/MIT) (open source).
