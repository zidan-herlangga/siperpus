# 📚 Sistem Informasi Perpustakaan Sekolah

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel)
![Filament](https://img.shields.io/badge/Filament-4-F59E0B?style=for-the-badge&logo=php)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php)

Aplikasi **Sistem Informasi Perpustakaan Sekolah** dibangun menggunakan **Laravel 11** dan **Filament Admin Panel 4**.  
Proyek ini bertujuan membantu sekolah dalam mengelola data buku, siswa, dan peminjaman secara efisien dan modern.

---

## 🚀 Fitur Utama

### 🎛️ Panel Admin (Filament)

-   **Dashboard Interaktif:** Statistik total buku, siswa, peminjaman, dan keterlambatan.
-   **Manajemen Buku:** CRUD data buku (judul, penulis, penerbit, kategori, stok, dll).
-   **Manajemen Siswa:** CRUD data siswa.
-   **Manajemen Peminjaman:** Form dinamis dengan perhitungan otomatis denda dan status.
-   **Tampilan Modern:** Menggunakan komponen Filament dengan struktur kode berbasis Schema Class (FormSchema, TableSchema).

### 👩‍💻 Halaman Pengguna (Siswa)

-   **Beranda Dinamis:** Statistik perpustakaan langsung dari database.
-   **Katalog Buku:** Menampilkan seluruh koleksi buku dengan fitur pencarian dan filter kategori.
-   **Detail Buku SEO-Friendly:** URL seperti `/books/atomic-habits`.
-   **Registrasi & Verifikasi Email:** Siswa mendaftar dan menerima email konfirmasi.
-   **Login Aman:** Otentikasi menggunakan email & password.
-   **Dashboard Siswa:** Menampilkan buku yang sedang dipinjam dan riwayatnya.
-   **Fitur Pinjam Buku:** Pinjam langsung dari halaman detail buku.

### ⚙️ Fitur Otomatis & Latar Belakang

-   **Notifikasi Email Otomatis:**
    -   Pengingat H-1 sebelum tanggal jatuh tempo.
    -   Notifikasi keterlambatan beserta denda (Rp5.000/hari) setiap pukul 08.00 pagi.
-   **Jam Operasional Situs:**
    -   Akses publik ditutup otomatis di hari Sabtu & Minggu via Middleware.
-   **Struktur Modular:**
    -   Resource, Model, dan Notification terpisah untuk maintainability tinggi.

---

## 🧩 Teknologi yang Digunakan

| Komponen        | Versi | Keterangan         |
| --------------- | ----- | ------------------ |
| Laravel         | 11.x  | Framework utama    |
| Filament        | 4.x   | Admin Panel        |
| PHP             | 8.2+  | Bahasa backend     |
| MySQL / MariaDB | -     | Database           |
| Composer        | -     | Dependency Manager |
| SMTP Gmail      | -     | Email Notifikasi   |

---

## ⚙️ Panduan Instalasi (Tanpa NPM)

### 1️⃣ Clone Repository

```bash
git clone https://github.com/zidan-herlangga/siperpus.git
cd siperpus
```

### 2️⃣ Install Dependensi

```bash
composer install
```

### 3️⃣ Salin File Environment

```bash
cp .env.example .env
```

### 4️⃣ Generate Application Key

```bash
php artisan key:generate
```

### 5️⃣ Konfigurasi .env

Pastikan file .env berisi konfigurasi berikut:

```bash
APP_NAME=Siperpus
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=Asia/Jakarta
APP_URL=http://localhost:8000
APP_LOCALE=id
APP_FALLBACK_LOCALE=en

ASSET_URL=
DEBUGBAR_ENABLED=false # default

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=siperpus
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_CONNECTION=log
CACHE_STORE=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

# Google Authentication
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME="${APP_NAME}"

VITE_APP_NAME="${APP_NAME}"
```

💡 Tips:

-   Gunakan App Password Gmail, bukan password akun utama.
-   Pastikan database siperpus sudah dibuat sebelum migrasi.

### 6️⃣ Migrasi & Seeder

```bash
php artisan migrate --seed
```

### 7️⃣ Buat Akun Admin Pertama

```bash
php artisan make:filament-user
```

## ScreenShot

| Tampilan                                                     | Deskripsi                                                                                                             |
| ------------------------------------------------------------ | --------------------------------------------------------------------------------------------------------------------- |
| ![Dashboard Siswa](public/screenshots/dashboard-admin.png)   | **Dashboard Admin (Filament):** Menampilkan statistik total buku, siswa, dan transaksi peminjaman secara _real-time_. |
| ![Daftar Buku](public/screenshots/catalogpage.png)           | **Manajemen Buku:** CRUD data buku lengkap dengan filter dan pencarian cepat.                                         |
| ![Form Peminjaman](public/screenshots/modal-pinjam-buku.png) | **Form Peminjaman Buku:** Memilih siswa dan buku secara otomatis, dengan perhitungan tanggal kembali dan denda.       |
| ![Katalog Buku Siswa](public/screenshots/detail-book.png)    | **Katalog Buku (Siswa):** Tampilan publik daftar buku dengan desain modern dan SEO-friendly.                          |
| ![Dashboard Siswa](public/screenshots/dashboard-student.png) | **Dashboard Siswa:** Menampilkan buku yang sedang dipinjam dan riwayat peminjaman sebelumnya.                         |
