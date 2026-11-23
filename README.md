# Web HIMA NJ

Sistem Informasi Manajemen Sumber Daya Manusia (HRIS) berbasis web untuk Himpunan Mahasiswa Alumni Nurul Jihad (HIMA NJ), dikembangkan menggunakan framework Laravel.

## Teknologi yang Digunakan

- Laravel
- PHP
- MySQL
- HTML
- CSS
- JavaScript
- Tailwind CSS

## Fitur Aplikasi

- Manajemen data anggota.
- Manajemen data pengurus.
- Manajemen struktur organisasi.
- Pengelolaan dokumen organisasi.
- Dashboard administrator.
- Sistem autentikasi pengguna.

## Persyaratan Sistem

- PHP
- Composer
- MySQL
- Node.js dan NPM (jika diperlukan untuk pengelolaan aset frontend).

## Instalasi

1. Clone repository:

   ```bash
   git clone https://github.com/yadi090403/web-hima-project-secripsi.git
   ```

2. Masuk ke folder project:

   ```bash
   cd web-hima-project-secripsi
   ```

3. Install dependency:

   ```bash
   composer install
   ```

4. Salin file konfigurasi:

   ```bash
   cp .env.example .env
   ```

5. Generate application key:

   ```bash
   php artisan key:generate
   ```

6. Atur konfigurasi database pada file `.env`.

7. Jalankan migrasi database:

   ```bash
   php artisan migrate
   ```

8. Jalankan aplikasi:

   ```bash
   php artisan serve
   ```

## Pengembang

Project ini dikembangkan sebagai tugas akhir dengan studi kasus Himpunan Mahasiswa Alumni Nurul Jihad (HIMA NJ).
