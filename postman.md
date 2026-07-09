# Tutorial API Testing: Little Latte Cafe menggunakan Postman

Panduan ini akan membantu Anda menguji backend API Little Latte Cafe menggunakan **Postman**. 

## ⚙️ Persiapan Awal

1. **Jalankan Server Lokal**:
   Pastikan backend Laravel Anda sedang berjalan. Buka terminal dan jalankan:
   ```bash
   php artisan serve
   ```
   *Base URL* API Anda sekarang adalah: `http://localhost:8000/api`

2. **Buka Postman**:
   Buat *Workspace* atau *Collection* baru di aplikasi Postman dengan nama "Little Latte Cafe API".

---

## Langkah 1: Registrasi Pengguna Baru

Mari kita buat akun pelanggan (pengunjung) baru terlebih dahulu.

- **Method**: `POST`
- **URL**: `http://localhost:8000/api/auth/register`
- **Headers**: 
  - `Accept`: `application/json`
- **Body** (Pilih mode `raw` -> `JSON`):
  ```json
  {
      "nama": "Budi Pengunjung",
      "email": "budi@example.com",
      "password": "password123",
      "role": "pengunjung"
  }
  ```
- **Klik Send**. Anda akan menerima balasan berupa status `201 Created` dan data pengguna.

---

## Langkah 2: Login & Mendapatkan Token (PENTING)

Sistem ini menggunakan **JWT (JSON Web Token)** untuk mengamankan data. Anda wajib login untuk mendapatkan token yang akan menjadi "Kunci Masuk" untuk API lainnya.

- **Method**: `POST`
- **URL**: `http://localhost:8000/api/auth/login`
- **Headers**: 
  - `Accept`: `application/json`
- **Body** (Pilih mode `raw` -> `JSON`):
  ```json
  {
      "email": "budi@example.com",
      "password": "password123"
  }
  ```
- **Klik Send**. 
- **Hasil**: Anda akan menerima balasan berisi `token` (String teks yang panjang). 
> 💡 **TIPS**: *Copy* token tersebut karena Anda akan menggunakannya di langkah-langkah berikutnya!

---

## Langkah 3: Menggunakan Kunci Token di Postman

Untuk mengakses API yang dilindungi (seperti membuat pesanan), Anda harus memasukkan Token dari Langkah 2.
1. Di tab **Authorization** (tepat di bawah URL Postman).
2. Ubah tipe **Type** menjadi **Bearer Token**.
3. *Paste* token Anda di kolom **Token**.

*(Postman akan secara otomatis menambahkan token ini di belakang layar untuk request tersebut).*

---

## Langkah 4: Melihat Daftar Menu (Tanpa Token)

Endpoint ini bersifat publik, siapa saja bisa melihatnya. Tersedia juga fitur *filtering* dan *pagination*.

- **Method**: `GET`
- **URL Dasar**: `http://localhost:8000/api/menu`
- **URL dengan Filter & Pagination**: `http://localhost:8000/api/menu?category=makanan&limit=5&search=goreng`
- **Headers**:
  - `Accept`: `application/json`
- **Klik Send**. Anda akan melihat daftar menu yang tersedia lengkap dengan sisa stoknya. **Catat salah satu `id` menu untuk dicoba pada pemesanan di Langkah 5.**

---

## Langkah 5: Membuat Pesanan (Wajib Menggunakan Token Pelanggan)

Mari kita coba membuat pesanan kopi.

- **Method**: `POST`
- **URL**: `http://localhost:8000/api/orders`
- **Authorization**: Set **Bearer Token** (Lihat Langkah 3).
- **Headers**: 
  - `Accept`: `application/json`
- **Body** (Pilih mode `raw` -> `JSON`):
  ```json
  {
      "menu_id": 1,
      "jumlah": 2
  }
  ```
  *(Ubah `menu_id` dengan ID asli yang Anda dapatkan di Langkah 4. Pastikan `jumlah` pesanan tidak melebihi stok yang ada, atau API akan menolak pesanan Anda!)*
- **Klik Send**. Anda akan menerima pesan berhasil dan stok di menu akan otomatis terpotong.

---

## Langkah 6: Mengakses Dashboard Admin (Wajib Menggunakan Token Admin)

Beberapa fitur (seperti melihat pendapatan atau menambah menu baru) diblokir untuk pengunjung biasa. Untuk mengujinya:

1. Anda harus login ulang di `http://localhost:8000/api/auth/login` menggunakan email yang memiliki _role_ `admin` (misalnya akun superadmin yang Anda miliki).
2. Dapatkan **Token baru** dan masukkan ke Bearer Token Postman Anda.

Sekarang cobalah cek laporan pendapatan:
- **Method**: `GET`
- **URL**: `http://localhost:8000/api/orders/report?period=monthly`
- **Authorization**: Bearer Token (Token milik Admin).
- **Klik Send**. Anda akan dapat melihat total pesanan dan pemasukan (*income*) bulan ini. Jika Anda menggunakan Token Budi (Pengunjung biasa), Anda akan mendapatkan pesan error `403 Forbidden` (Akses Ditolak).
