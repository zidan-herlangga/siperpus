# 📚 ELibrary SMK Karya Guna 2

Aplikasi **Perpustakaan Digital** untuk SMK Karya Guna 2. Dibangun dengan **Laravel 12**, **Livewire**, dan **Filament 4 Admin Panel**. Menyediakan katalog buku online, peminjaman digital, riwayat peminjaman, testimoni, serta panel manajemen untuk Admin, Staff, dan Kepala Sekolah.

---

## ✨ Fitur

### Sisi Siswa (Frontend)
- Landing page dengan statistik kunjungan & buku populer.
- Katalog buku interaktif (pencarian, filter kategori, Livewire, responsive grid 2–4 kolom).
- Detail buku: sinopsis, stok real-time, daftar komentar.
- **Peminjaman online** — mengajukan permintaan peminjaman; disetujui admin.
- **Halaman riwayat** peminjaman dengan filter status (Pending / Dipinjam / Dikembalikan / Batal) dan denda keterlambatan.
- Registrasi + **verifikasi email**, login via **email atau NIS**.
- Edit profil (foto, kontak, ganti password) dengan password strength meter.
- Testimoni siswa.
- **PWA-ready** (manifest + service worker, cache-first untuk aset build).

### Sisi Admin (Filament Panel)
- **Tiga panel terpisah** dengan kontrol akses role:
  - `/admin` — Admin (kelola penuh).
  - `/staff` — Staff (kelola, tanpa hapus).
  - `/kepsek` — Kepala Sekolah (hanya lihat / monitoring).
- Manajemen **Buku, Kategori, Siswa, Peminjaman, Komentar, Testimoni, Admin**.
- Konfirmasi/penolakan peminjaman, penanda kembalian & denda.
- Statistik pengunjung harian & bulanan (widget: *Pengunjung Hari Ini*, *Grafik Pengunjung*).
- Notifikasi in-app dan **email reminder** (pengingat jatuh tempo & keterlambatan).

---

## 🧰 Tech Stack

| Layer      | Teknologi                                                        |
|------------|------------------------------------------------------------------|
| Backend    | Laravel 12, PHP 8.2+                                             |
| Frontend   | Blade, Tailwind CSS 3, Vite, Livewire 3                          |
| Admin Panel| Filament 4 (3 panel role-based)                                  |
| Database   | MySQL / MariaDB                                                  |
| Auth       | Session, email verification, reset password, rate limiting       |
| Export     | Filament Export (Excel / PDF via Dompdf)                         |
| Lainnya    | PWA (custom service worker), queue (email), Laravel Sanctum (API)|

---

## ⚙️ Kebutuhan Sistem

- PHP **8.2+** (disarankan **8.3**)
- Composer 2.x
- Node.js **18+** (disarankan 20 LTS) + npm
- MySQL 5.7+/MariaDB 10.4+
- Ekstensi PHP: `pdo_mysql`, `gd`, `fileinfo`, `mbstring`, `openssl`

---

## 🚀 Cara Instalasi

```bash
# 1. Install dependency PHP
composer install

# 2. Install dependency frontend
npm install

# 3. Buat file environment
cp .env.example .env
php artisan key:generate

# 4. Atur koneksi database di .env
#    DB_DATABASE=siperpus
#    DB_USERNAME=root
#    DB_PASSWORD=

# 5. Buat database & jalankan migrasi + seeder
php artisan migrate --seed

# 6. Link untuk penyimpanan file foto/cover
php artisan storage:link

# 7. Build asset frontend (production) ATAU mode development
npm run build        # production: public/build/...
npm run dev          # development (hot reload)

# 8. Jalankan aplikasi
php artisan serve
```

Buka aplikasi di `http://localhost:8000`.

> **Catatan**: Email verifikasi & reset password memerlukan pengaturan `MAIL_*` di `.env` (SMTP).

---

## 🔑 Akun Default (hasil `seed`)

> ⚠️ **Ganti segera** semua password default sebelum dipakai di lingkungan nyata.

### Panel Admin
| Panel           | URL            | Email                     | Password             |
|-----------------|----------------|---------------------------|----------------------|
| Admin           | `/admin`       | `admin@smkkg2.sch.id`     | `AdminPerpustakaan`  |
| Staff           | `/staff`       | `staff@smkkg2.sch.id`     | `StaffPerpustakaan`  |
| Kepala Sekolah  | `/kepsek`      | `kepsek@smkkg2.sch.id`    | `KepsekPerpustakaan` |

### Siswa
| Nama          | NIS   | Email                   | Password  |
|---------------|-------|-------------------------|-----------|
| Zidan Herlangga | 1001 | `zidanherlangga24@gmail.com` | `password` |
| Gustiar Ilham | 1002 | `kachishiro78@gmail.com`| `password` |
| Rival Rivaldy  | 1003 | `zaky.hart17@gmail.com` | `password` |
| Naufal Rafly S | 1004 | `naufalraflybaru@gmail.com` | `password` |
| Andre Budi Setiyawan | 1005 | `alsyacallysta15@gmail.com` | `password` |

Siswa login menggunakan **email atau NIS** + password. Sebagian besar akun seeder sudah ter-verifikasi email; NIS `1003` sengaja dibiarkan belum verifikasi untuk pengujian alur verifikasi.

---

## ⚙️ Konfigurasi Tambahan (.env)

| Variabel | Default | Keterangan |
|----------|---------|------------|
| `LIBRARY_FINE_PER_DAY` | `1000` | Denda keterlambatan per hari (Rupiah). |
| `LIBRARY_BORROW_DURATION_DAYS` | `7` | Lama peminjaman normal (hari). |
| `LIBRARY_MAX_BORROW_PER_STUDENT` | `3` | Batas maksimal pinjaman aktif per siswa. |

Lainnya: `CACHE_STORE`, `SESSION_DRIVER`, `QUEUE_CONNECTION` (untuk email reminder & notifikasi).

---

## 🧪 Pengujian

```bash
php artisan test
```

Suite mencakup: model attributes (avatar/is_active), halaman frontend (home, katalog, login, panel), serta kontrol akses panel & resource secara role-based (admin/staff/kepsek).

---

## 📁 Struktur Proyek (Ringkas)

```
app/
├── Console/Commands/        # Perintah terjadwal (reminder email)
├── Filament/                # Resources, Widgets, Panel Access (role-based)
├── Http/
│   ├── Controllers/         # Auth, Home, Book, Borrowing, History, dll.
│   ├── Controllers/Api/     # API katalog buku (Sanctum)
│   └── Middleware/          # SecurityHeaders, CachePublicResponse, CheckAdminRole
├── Jobs/ & Mail/            # Pekerjaan & email reminder
├── Livewire/                # Komponen Livewire (katalog buku, dll.)
├── Models/                  # Student, Book, Borrowing, Testimonial, dll.
├── Notifications/           # Email verifikasi & notifikasi peminjaman
└── Providers/
    ├── AppServiceProvider   # Visitor tracking, rate limiters
    └── Filament/            # AdminPerpus, Staff, Kepsek panels
```

---

## 🔒 Aspek Keamanan

- **CSRF** aktif di semua form; halaman publik di-cache **tanpa token CSRF** (mencegah error 419 lintas sesi).
- **Rate limiting**: login, katalog publik, peminjaman, verifikasi email.
- Hash password **bcrypt** (default Laravel 12); validasi email `email:rfc`.
- Authorisasi **role-based** (admin/staff/kepsek) di tiap resource Filament.
- **Security headers** di semua respons (web, API, panel): `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`, `Permissions-Policy`, CSP `frame-ancestors`, dan HSTS (production+HTTPS); `X-Powered-By` disembunyikan.
- Audit dependency: `composer audit` → **0 advisories**.
- Cache respons publik dibatasi TTL & mengecualikan halaman ber-`_token`.

---

## 🛠️ Maintenance

- `npm run build` setiap perubahan Blade/Tailwind baru → jalankan sekali **Ctrl+F5** (service worker menyajikan aset baru).
- Service worker versi dirilis sebagai `elib-v2`; dokumen di-fetch ke network agar selalu segar.
- `php artisan optimize:clear` setelah perubahan konfigurasi besar.

---

## 📄 Lisensi

Proyek ini dikembangkan untuk kebutuhan internal **SMK Karya Guna 2**. Tidak ada lisensi khusus yang dipublikasikan untuk penggunaan komersial di luar institusi terkait tanpa izin.