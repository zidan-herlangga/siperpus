# 📚 Sistem Informasi Perpustakaan Sekolah (SiPerpus)

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel)
![Filament](https://img.shields.io/badge/Filament-4.x-F59E0B?style=for-the-badge&logo=php)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?style=for-the-badge&logo=mysql)
![Sanctum](https://img.shields.io/badge/Sanctum-4.x-4A5568?style=for-the-badge&logo=laravel)

> Aplikasi **Sistem Informasi Perpustakaan Sekolah (SiPerpus)** adalah platform berbasis **Laravel 11 + Filament 4** yang dirancang untuk membantu sekolah dalam mengelola koleksi buku, data siswa, serta transaksi peminjaman dan pengembalian secara efisien dan modern.

---

## 🚀 Fitur Utama

### 🎛️ Multi Panel Berbasis Role (Filament 4)
- 👑 **Panel Admin** (`/admin`): Akses penuh — Books, Borrowings, Students, Categories, Reminders, Testimonials
- 👔 **Panel Staff** (`/staff`): Operasional — Books, Borrowings, Students, Categories (read/write, no delete)
- 🏫 **Panel Kepsek** (`/kepsek`): Read-only — Dashboard statistik, Books, Borrowings, Students, Categories
- 📊 **Dashboard Interaktif**: Statistik total buku, siswa, peminjaman pending/dipinjam/terlambat, dan pengunjung harian dalam satu tampilan.
- 📚 **Manajemen Buku**: CRUD data buku (judul, penulis, penerbit, kategori, stok, ISBN, kondisi, dll) dengan upload gambar, RichEditor sinopsis, dan **ekspor ke Excel/CSV**.
- 🧑‍🎓 **Manajemen Siswa**: CRUD data siswa lengkap dengan NIS, kelas, status aktif/nonaktif, dan badge verifikasi email.
- 🔁 **Manajemen Peminjaman**: Form dinamis dengan otomatis kalkulasi tanggal kembali (+7 hari), workflow status (Pending → Dipinjam → Dikembalikan/Batal), kalkulasi denda otomatis, deteksi keterlambatan.
- 💰 **Fine Rate Konfigurable**: Denda per hari dapat diatur via `.env` (`LIBRARY_FINE_PER_DAY`).
- 🔔 **Notifikasi Database**: Notifikasi real-time saat siswa baru daftar atau peminjaman baru diajukan.
- 📈 **Laporan & Export**: Laporan semua aktivitas peminjaman dengan export Excel/CSV.
- 👁️ **Statistik Pengunjung**: Pantau jumlah pengunjung hari ini, kemarin, total, dan rata-rata 7 hari dengan chart.

### 👩‍💻 Halaman Siswa (Frontend - Livewire + Tailwind)
- 🏠 **Beranda Dinamis**: Hero section, fitur unggulan, statistik real-time (dengan animasi counter), dan testimonials.
- 📔 **Katalog Buku**: Livewire-powered reactive search, filter kategori, sort (terbaru/terlama/A-Z/populer), pagination.
- 🔍 **Detail Buku SEO-Friendly**: URL otomatis `/books/{slug}`, cek stok real-time via API, komentar pembaca, buku terkait, modal download tiket peminjaman.
- ✉️ **Registrasi & Verifikasi Email**: Wajib verifikasi email sebelum dapat mengakses dashboard.
- 🔐 **Login Aman**: Login via email atau NIS, dengan "remember me".
- 📅 **Dashboard Siswa**: Buku sedang dipinjam (dengan warning keterlambatan), pengajuan pending, riwayat, total denda, ringkasan profil.
- 🪄 **Pinjam Langsung (AJAX)**: Validasi jam operasional (Senin-Jumat 7-16 WIB), cek stok, cek duplikat peminjaman.
- 📜 **Riwayat Peminjaman**: Pagination, **search judul buku**, **filter status**, info denda.
- 🔐 **Lupa Password**: Reset password via email menggunakan broker khusus 'students'.
- ⭐ **Testimoni & Rating**: Siswa dapat memberikan ulasan dengan rating 1-5, menunggu persetujuan admin.

### ⚙️ Fitur Otomatis & Latar Belakang
- 📧 **Email Reminder Otomatis** (via cron scheduler setiap pukul 07.00 WIB)
  - H-1 sebelum jatuh tempo (`pre_due`)
  - Notifikasi keterlambatan + jumlah denda (`overdue`)
- 🕒 **Jam Operasional**: Peminjaman hanya diizinkan Senin–Jumat pukul 07.00–16.00 WIB.
- 👤 **Visitor Tracking Otomatis**: Setiap kunjungan tercatat (1x per IP per hari).
- 📱 **PWA Support**: Manifest, service worker, install prompt, offline page.
- 💬 **Chatbot Pintar**: Integrasi OpenAI/OpenRouter untuk eksplorasi buku.

---

## 🧰 Teknologi yang Digunakan

| Komponen            | Versi    | Deskripsi                         |
|---------------------|----------|-----------------------------------|
| **Laravel**         | 11.x     | Framework utama                   |
| **Filament**        | 4.x      | Admin panel TALL stack            |
| **Livewire**        | 3.6.4    | Reactive frontend components      |
| **PHP**             | 8.2+     | Bahasa backend                    |
| **MySQL**           | 8.x      | Database utama                    |
| **Laravel Sanctum** | 4.x      | API token authentication          |
| **Tailwind CSS**    | 3.4      | Utility CSS framework             |
| **Vite**            | 6.x      | Asset bundler / build tool        |
| **Alpine.js**       | via LW   | Frontend interactivity            |
| **Font Awesome**    | 6.4      | Icons                             |
| **SMTP Gmail**      | -        | Pengiriman notifikasi email       |
| **OpenAI API**      | -        | Chatbot khusus buku               |

---

## ⚙️ Panduan Instalasi

> Prasyarat: **PHP 8.2+**, **Composer**, **MySQL 8.x**, dan **Node.js 20+**.

### 1. Clone Repository

```bash
git clone https://github.com/zidan-herlangga/siperpus.git
cd siperpus
```

### 2. Install Dependensi

```bash
composer install
```

### 3. Install NPM

```bash
npm install
```

### 4. Salin File Environment

```bash
cp .env.example .env
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Konfigurasi .env

Sesuaikan konfigurasi database, email, dan lainnya di file **.env**:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=siperpus
DB_USERNAME=root
DB_PASSWORD=

# SMTP Gmail (gunakan App Password)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=email@anda.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="email@anda.com"
MAIL_FROM_NAME="${APP_NAME}"

# Library Configuration
LIBRARY_FINE_PER_DAY=1000
LIBRARY_BORROW_DURATION_DAYS=7
```

💡 **Tips**: Gunakan [App Password Gmail](https://support.google.com/accounts/answer/185833), bukan password akun utama. Pastikan database sudah dibuat sebelum migrasi.

### 7. Migrasi & Seed

```bash
php artisan migrate
php artisan db:seed
```

### 8. Build Assets

```bash
npm run build
```

### 9. Jalankan Development Server

```bash
php artisan serve
```

### 10. Akun Default

Setelah `db:seed`, tersedia 3 akun dengan role berbeda:

| Role | Email | Password | Panel URL |
|------|-------|----------|-----------|
| **Admin** | `admin@smkkg2.sch.id` | `AdminPerpustakaan` | `/admin` |
| **Staff** | `staff@smkkg2.sch.id` | `StaffPerpustakaan` | `/staff` |
| **Kepsek** | `kepsek@smkkg2.sch.id` | `KepsekPerpustakaan` | `/kepsek` |

Login siswa dilakukan via halaman `/login-student`.

### 11. Setup Cron (untuk Email Reminder)

Tambahkan cron job berikut di server:

```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

Atau jalankan secara manual:

```bash
php artisan app:send-reminder
```

---

## 🔐 Keamanan

- ✅ **API protected**: Endpoint write pada REST API dilindungi dengan Laravel Sanctum.
- ✅ **CSRF Protection**: Semua form menggunakan CSRF token.
- ✅ **SQL Injection Prevention**: Query menggunakan Eloquent ORM dengan parameter binding.
- ✅ **XSS Protection**: Output escaping dengan Blade syntax `{{ }}`. Synopsis menggunakan RichEditor dengan sanitasi.
- ✅ **File Upload Validation**: Hanya tipe gambar tertentu (jpeg, png, webp, gif) dengan batas ukuran.
- ✅ **Email Verification**: Wajib verifikasi sebelum akses dashboard.
- ✅ **Password Hashing**: Otomatis di-hash menggunakan `bcrypt` via cast `hashed`.
- ✅ **Rate Limiting**: Route verifikasi email dibatasi 6 request per menit.
- ✅ **Multi Panel Role-Based**: Tiga panel terpisah (Admin, Staff, Kepsek) dengan autentikasi & otorisasi per role.
- ✅ **Dual Auth Guards**: Pemisahan guard `admin` dan `student`.

---

## 🐛 Perbaikan & Refactoring Terbaru

Projek ini telah melalui proses refactoring, multi-panel, dan security hardening:

### Bug Fixes
- **Reminder model**: Typo type hint `Student $student` → `Reminder $reminder`
- **BookForm**: Field name `category_id` → `category` (sesuai kolom database)
- **DashboardController**: Menghapus external HTTP call (`api.ipify.org`) yang tidak stabil
- **Slug buku**: Ditambahkan logika unique agar tidak crash saat judul buku duplikat
- **PWA button**: HTML broken (style attribute tidak tertutup)
- **BooksTable filter**: Filter kategori menggunakan relationship yang tidak ada, diperbaiki dengan distinct values
- **Flysystem error**: Avatar null menyebabkan `Storage::exists(null)` — diperbaiki dengan accessor + default kolom NOT NULL
- **Missing scopes**: `needReminder()`, `isOverdue()`, `isDueSoon()` ditambahkan ke Borrowing model
- **HomeController 500**: `count('category')` crash karena kolom dihapus — diganti `Category::count()`

### Security
- **API routes**: Semua write endpoint (POST/PUT/DELETE) dilindungi dengan `auth:sanctum`
- **SMTP credentials**: Dihapus dari repository, diganti placeholder
- **APP_DEBUG**: Diubah ke `false`
- **File upload**: Ditambahkan validasi MIME type pada cover image dan avatar
- **Explicit auth guard**: Semua `Auth::user()` diganti `Auth::guard('student')->user()`
- **Book comment validation**: Validasi keberadaan buku sebelum menambahkan komentar

### New Features
- **Multi Panel (Admin/Staff/Kepsek)**: Tiga panel Filament terpisah dengan akses sesuai role.
- **Manajemen Kategori**: CRUD kategori buku dengan Filament resource.
- **Kondisi Buku**: Tracking kondisi buku (Baik/Rusak Ringan/Rusak Berat/Hilang) via enum.
- **Role Management**: Role admin/staff/kepsek dengan `canAccessPanel()` dan trait `HasRoleBasedAccess`.
- **Fine rate configurable**: Denda per hari dapat diatur via `.env` (tidak hardcoded)
- **Durasi peminjaman configurable**: Durasi pinjam dapat diatur via `.env`
- **Search & Filter riwayat**: Pencarian judul buku dan filter status di halaman history
- **Export buku**: Ekspor data buku ke Excel/CSV dari admin panel
- **ISBN validation**: Validasi format ISBN 10/13 digit
- **Laravel Sanctum**: Ditambahkan untuk API authentication

---

## 📸 Screenshot

| Tampilan                                                       | Deskripsi                                                                               |
| -------------------------------------------------------------- | --------------------------------------------------------------------------------------- |
| ![Dashboard Admin](public/screenshots/dashboard-admin.png)     | **Dashboard Admin (Filament):** Statistik total buku, siswa, dan transaksi peminjaman. |
| ![Daftar Buku](public/screenshots/catalogpage.png)             | **Manajemen Buku:** CRUD data buku dengan filter, pencarian, dan export Excel.         |
| ![Form Peminjaman](public/screenshots/modal-pinjam-buku.png)   | **Form Peminjaman:** Pilih siswa & buku dengan kalkulasi tanggal kembali otomatis.     |
| ![Katalog Buku Siswa](public/screenshots/detail-book.png)      | **Detail Buku:** Tampilan publik dengan info lengkap, komentar, dan tombol pinjam.     |
| ![Dashboard Siswa](public/screenshots/dashboard-student.png)   | **Dashboard Siswa:** Buku dipinjam, riwayat, total denda, dan profil.                  |

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah **MIT License** - lihat file [LICENSE](LICENSE) untuk detail lebih lanjut.

Dibuat dengan ❤️ oleh [Zidan Herlangga](https://github.com/zidan-herlangga) dan kontributor.
