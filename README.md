# 💍 Wedding Gallery Laravel

Aplikasi Web Galeri Pernikahan interaktif dan modern berbasis **Laravel 12**, **Tailwind CSS**, dan integrasi **Google Drive API**. Proyek ini memungkinkan pengantin dan tamu undangan melihat foto-foto momen pernikahan secara terorganisir per album/subfolder, live preview tema, memilih foto favorit, hingga download resolusi tinggi.

---

## 📋 Daftar Isi
1. [Prasyarat Sistem](#-prasyarat-sistem)
2. [Langkah-Langkah Instalasi (Dari Nol)](#-langkah-langkah-instalasi-dari-nol)
3. [Konfigurasi Google Drive API](#-konfigurasi-google-drive-api)
4. [Akun Default (Seeder)](#-akun-default-seeder)
5. [Menjalankan Aplikasi](#-menjalankan-aplikasi)
6. [Fitur-Fitur Utama](#-fitur-fitur-utama)
7. [Troubleshooting & Tips](#-troubleshooting--tips)

---

## 💻 Prasyarat Sistem

Sebelum memulai instalasi, pastikan sistem/komputer Anda telah terinstall:
- **PHP** minimal versi **8.2** atau lebih tinggi
  - Ekstensi PHP wajib: `OpenSSL`, `PDO`, `Mbstring`, `Tokenizer`, `XML`, `Ctype`, `JSON`, `BCMath`, `cURL`, `Fileinfo`, `GD`
- **Composer** (v2.x)
- **Node.js** (v18.x / v20.x ke atas) & **NPM**
- **Database Server**: MySQL / MariaDB (disarankan via Laragon atau XAMPP)
- **Web Server Local**: Laragon / Apache / Nginx, atau gunakan `php artisan serve`

---

## 🚀 Langkah-Langkah Instalasi (Dari Nol)

### 1. Buka Direktori Proyek
Letakkan proyek ini di direktori web server Anda (contoh Laragon: `C:\laragon\www\wedding-gallery-laravel-main`). Buka terminal / command prompt / PowerShell di folder tersebut:
```bash
cd C:\laragon\www\wedding-gallery-laravel-main
```

### 2. Install Dependensi PHP (Composer)
Jalankan perintah berikut untuk mengunduh semua library PHP:
```bash
composer install
```

### 3. Buat File Konfigurasi `.env`
Salin template konfigurasi dari `.env.example` menjadi `.env`:

**Windows (PowerShell / Command Prompt):**
```bash
copy .env.example .env
```
*Atau di Bash / Git Bash:*
```bash
cp .env.example .env
```

### 4. Generate Application Key
Buat encryption key unik untuk aplikasi Laravel Anda:
```bash
php artisan key:generate
```

### 5. Konfigurasi Database di `.env`
Buka file `.env` dengan text editor dan sesuaikan kredensial database Anda (contoh menggunakan MySQL di Laragon / XAMPP):

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wedding_gallery
DB_USERNAME=root
DB_PASSWORD=
```
> **Catatan:** Buat database baru bernama `wedding_gallery` di MySQL (bisa melalui HeidiSQL, phpMyAdmin, atau MySQL CLI):
> ```sql
> CREATE DATABASE wedding_gallery CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
> ```

### 6. Jalankan Migrasi & Database Seeder
Jalankan migrasi untuk membuat seluruh tabel database sekaligus mengisi data awal (akun admin, data pengantin contoh, album, dsb):
```bash
php artisan migrate:fresh --seed
```

### 7. Buat Symbolic Link Storage
Agar file media dan asset local dapat diakses secara publik oleh browser:
```bash
php artisan storage:link
```

### 8. Install & Build Asset Frontend (Vite & Tailwind CSS)
Install dependensi JavaScript/CSS dan lakukan build:
```bash
npm install
npm run build
```

---

## ☁️ Konfigurasi Google Drive API

Aplikasi ini memiliki fitur sinkronisasi galeri langsung dari Google Drive. Terdapat 2 opsi:

### Cara 1: Menggunakan Google API Key (Paling Mudah - Disarankan)
1. Buka [Google Cloud Console](https://console.cloud.google.com/).
2. Buat project baru dan aktifkan **Google Drive API**.
3. Buka menu **Credentials** -> Klik **Create Credentials** -> **API Key**.
4. Salin API Key yang didapatkan dan masukkan ke file `.env`:
   ```env
   GOOGLE_API_KEY=AIzaSyDxxxxxxxxxxxxxxxxxxxxxxxxx
   ```
5. **Penting pada Folder Google Drive:**
   - Buka folder foto Anda di Google Drive.
   - Klik **Bagikan (Share)** -> Ubah **Akses umum** menjadi **"Siapa saja yang memiliki link"** (*Anyone with the link*) dengan peran **Pelihat (Viewer)**.
   - Masukkan link atau ID folder tersebut saat mendaftarkan wedding baru di aplikasi. Sistem akan otomatis mendeteksi subfolder sebagai album!

### Cara 2: Menggunakan Google Service Account (Opsional)
Jika menggunakan Service Account:
1. Unduh kredensial JSON dari Google Cloud Console.
2. Buat folder `google` di `storage/app/` jika belum ada.
3. Simpan file JSON kredensial tersebut dengan nama:
   ```
   storage/app/google/service-account.json
   ```
4. Bagikan (*Share*) folder Google Drive ke alamat email service account tersebut (`client_email` yang tertera di file JSON).

---

## 👤 Akun Default (Seeder)

Setelah menjalankan `php artisan migrate:fresh --seed`, gunakan kredensial berikut untuk masuk ke dashboard:

| Role | Email | Password | Akses |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin@weddinggallery.com` | `password123` | Dashboard Admin, Kelola Wedding, User, Album, Log |
| **Klien (User)** | `rian.aulia@gmail.com` | `password123` | Akses Klien Wedding Rian & Aulia |
| **Klien (User)** | `dimas.sarah@gmail.com` | `password123` | Akses Klien Wedding Dimas & Sarah |

---

## 🏃 Menjalankan Aplikasi

### Opsi A: Menggunakan Laragon (Virtual Host)
Jika Anda menggunakan Laragon dan meletakkan folder di `laragon/www/wedding-gallery-laravel-main`:
- Cukup aktifkan **Start All** di Laragon.
- Buka browser dan akses: `http://wedding-gallery-laravel-main.test`

### Opsi B: Menggunakan Built-in Artisan Server
Jalankan server PHP di terminal:
```bash
php artisan serve
```
Lalu buka di browser: `http://127.0.0.1:8000`

### Menjalankan Mode Development Frontend (Hot Reload)
Jika Anda sedang melakukan perubahan tampilan/Blade/CSS:
```bash
npm run dev
```

---

## ✨ Fitur-Fitur Utama

- 👰 **Pengelolaan Wedding & Client**: Input data calon pengantin, tanggal, lokasi, hingga slug link unik.
- 📁 **Sinkronisasi Otomatis Google Drive**: Folder utama dan subfolder (seperti *Akad*, *Resepsi*, dll.) otomatis terbaca dan diubah menjadi filter album.
- 🎨 **Live Preview Tema & Kustomisasi**: Pengaturan tema warna dinamis dengan pratinjau langsung yang responsif.
- 🖼️ **Galeri Foto Interaktif**: Lightbox resolusi tinggi, filtering per folder/momen, serta status all foto.
- 📥 **Sistem Download Foto**: Tamu dapat mengunduh foto individual, atau memilih beberapa foto (multi-select) untuk diunduh sekaligus dalam format `.ZIP`.
- 🗑️ **Riwayat Terhapus (Soft Delete)**: Jika foto dihapus di Google Drive, foto aman tersimpan di riwayat hapus sebelum dibersihkan permanen.
- 🔔 **Activity Log**: Pencatatan riwayat upload, edit, hapus, dan riwayat download tamu.
- 👥 **Manajemen Pengguna**: Tambah, edit, dan hapus user administrator maupun user pengantin.
- 💾 **Monitoring Kapasitas Storage**: Pantau penggunaan kuota Google Drive dan ukuran total dari tiap wedding.

---

## 🛠️ Troubleshooting & Tips

- **Folder Permission (Penyimpanan/Log Error):**
  Pastikan folder `storage` dan `bootstrap/cache` memiliki izin tulis (writable).
- **Vite Manifest Not Found:**
  Jalankan `npm run build` jika muncul error manifest saat memuat halaman web.
- **Cache Laravel Perlu Direset:**
  Jika Anda mengubah file `.env` atau routes, jalankan:
  ```bash
  php artisan optimize:clear
  ```
- **Google Drive 403 / Folder Tidak Terbaca:**
  - Pastikan Akses Umum folder Google Drive sudah disetel ke **"Siapa saja yang memiliki link"**.
  - Pastikan `GOOGLE_API_KEY` terisi dengan benar di file `.env`.
