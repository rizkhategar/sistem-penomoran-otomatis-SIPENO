# SIPENO - Sistem Informasi Pengajuan Nomor Surat

Aplikasi manajemen penomoran surat otomatis untuk Disdukcapil.

## Fitur

- Penomoran surat otomatis per bidang/bulan/tahun
- Multi bidang (Umum, Perencanaan, dll)
- Kuota bulanan & sisipan harian
- Surat Keputusan (SK) berlaku mundur
- Report / laporan bulanan
- Manajemen user & jenis surat

## Tech Stack

| Komponen | Teknologi | Versi |
|----------|-----------|-------|
| Framework | Laravel | 10.x |
| PHP | PHP | ^8.1 |
| Database | MySQL | 5.7+ / 8.0 |
| Frontend | Blade + Tailwind CSS | 3.x |
| JavaScript | Alpine.js | 3.4.x |
| Build Tool | Vite | 5.x |
| Auth | Laravel Breeze | 1.x |

## Persyaratan Sistem

- PHP >= 8.1
- Composer
- MySQL >= 5.7
- Node.js >= 18
- NPM atau Yarn
- Ekstensi PHP: BCMath, Ctype, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, GD

## Instalasi

### 1. Clone Repository

```bash
git clone <repository-url> pengajuan-surat
cd pengajuan-surat
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env
```

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pengajuan_surat
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Key

```bash
php artisan key:generate
```

### 5. Buat Database

Buat database MySQL dengan nama `pengajuan_surat`:

```sql
CREATE DATABASE pengajuan_surat CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. Migrasi Database

```bash
php artisan migrate
```

### 7. Storage Link

```bash
php artisan storage:link
```

### 8. Build Asset

```bash
npm run build
```

### 9. Jalankan Aplikasi

```bash
php artisan serve
```

Akses aplikasi di: http://localhost:8000

## Penggunaan

1. Buka http://localhost:8000
2. Register akun baru atau login dengan akun yang sudah ada
3. Admin dapat mengelola user dan jenis surat
4. User dapat membuat surat dengan nomor otomatis
5. Lihat report bulanan di menu Report
6. Panduan lengkap ada di menu Manual (setelah login)
