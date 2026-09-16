# 🏭 Sistem Informasi Pengendalian Material Produksi Radiator - PT Batara Sura Mulia

Sistem informasi berbasis web yang dirancang untuk melakukan pengelolaan material produksi radiator di **PT Batara Sura Mulia** secara *real-time*. Aplikasi ini membantu menyederhanakan alur kerja industri dan mencegah miskomunikasi sebelum melakukan proses produksi radiator. 

## ✨ Fitur Utama

Sistem ini dilengkapi dengan beberapa modul fungsional:
- **Daftar Material:** Pusat data terintegrasi yang berfungsi sebagai katalogisasi seluruh bahan baku produksi.
- **Stock Material:** Pemantauan ketersediaan stok secara *live* lengkap dengan sistem *alert* (peringatan) dini saat stok menipis.
- **Proses Produksi:** Fitur untuk melakukan estimasi kebutuhan bahan baku secara presisi sebelum produksi dimulai.
- **Laporan Stok:** Modul analisis untuk melihat performa inventaris melalui laporan berkala.

## 🛠️ Teknologi yang Digunakan (Tech Stack)

Proyek ini dibangun menggunakan teknologi tanpa *framework* (Native) untuk performa yang ringan dan fleksibel:
- **Frontend:** HTML5, CSS3, JavaScript (JS Native)
- **Backend:** PHP Native
- **Database:** MySQL
- **Server Environment:** Apache (XAMPP / Laragon)

## 🚀 Panduan Instalasi Lokal

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di komputer lokal Anda menggunakan **XAMPP**:

### 1. Prasyarat
* Sudah menginstal **XAMPP** (disarankan versi PHP 7.4 atau PHP 8.x).
* Sudah menginstal **Git** di komputer Anda.

### 2. Kloning Repositori
Masuk ke folder `htdocs` XAMPP Anda via Terminal/Git Bash, lalu jalankan perintah:
```bash
cd C:\xampp\htdocs
git clone https://github.com materialku-bsm
```
*(Ubah `username` dan `nama-repositori` sesuai dengan akun GitHub Anda)*

### 3. Impor Database MySQL
1. Buka browser dan akses `http://localhost/phpmyadmin/`.
2. Buat database baru dengan nama `db_material_bsm` (atau sesuaikan dengan nama database Anda).
3. Pilih tab **Import**, lalu pilih file SQL proyek Anda (misalnya: `database.sql` atau `db_material_bsm.sql`).
4. Klik **Go** / **Import** dan tunggu sampai selesai.

### 4. Konfigurasi Koneksi Database
Buka file konfigurasi database di teks editor Anda (biasanya bernama `koneksi.php`, `config.php`, atau sejenisnya), lalu sesuaikan kredensialnya:
```php
<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_material_bsm"; // Sesuaikan nama database Anda

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
```

### 5. Jalankan Aplikasi
Buka browser Anda dan akses tautan berikut:
```text
http://localhost/materialku-bsm
```

## 📄 Lisensi

© 2026 PT Batara Sura Mulia. All rights reserved.
