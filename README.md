<div align="center">

# Pengembangan Modul Presensi Mahasiswa

Aplikasi web yang dikembangkan untuk Laporan Kerja Praktik dengan studi kasus pada sistem berita acara akademik di Universitas Kristen Maranatha.

</div>

## Penulis
* **Kevin Owen** ([@kvinown](https://github.com/kvinown))

---

## 🛠️ Komponen Teknologi
* **Framework Laravel:** v10.x
* **PHP:** v8.1+
* **Database:** MySQL
* **Frontend:** Blade, Bootstrap 5, CSS, JavaScript

---

## 🎯 Fungsionalitas Utama Pengguna (Dosen)

Sistem ini dirancang untuk digunakan oleh Dosen dengan kapabilitas sebagai berikut:

- **Mencatat Presensi**: Mengisi kehadiran mahasiswa secara individual per pertemuan.
- **Melihat Detail Presensi**: Memeriksa rekap kehadiran dan detail pertemuan yang telah berlangsung.
- **Memperbarui Status Kehadiran**: Mengubah status mahasiswa jika ada kesalahan input.
- **Melihat Visualisasi Data**: Memantau tren kehadiran mahasiswa melalui grafik.

---

## ⚙️ Instalasi dan Pengaturan

### Prasyarat
- [PHP](https://www.php.net/) (versi 8.1 atau lebih baru)
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) & [NPM](https://www.npmjs.com/) (Opsional, untuk manajemen aset frontend)
- [MySQL](https://www.mysql.com/)

### Langkah-langkah Instalasi

1.  **Clone repository ini**
    ```bash
    git clone https://github.com/kvinown/SIMBA-APP
    ```

2.  **Pindah ke direktori proyek**
    ```bash
    cd SIMBA-APP
    ```

3.  **Install dependencies via Composer**
    ```bash
    composer install
    ```

4.  **Buat file environment**
    Salin file `.env.example` menjadi `.env`.
    ```bash
    cp .env.example .env
    ```
    Kemudian, buka file `.env` dan sesuaikan konfigurasi database Anda.
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=db_bap
    DB_USERNAME=username_db_anda
    DB_PASSWORD=password_db_anda
    ```

5.  **Generate application key**
    ```bash
    php artisan key:generate
    ```

6.  **Jalankan migrasi database**
    Perintah ini akan membuat semua tabel yang dibutuhkan oleh aplikasi. Pastikan Anda sudah membuat database kosong di MySQL sesuai nama di file `.env`.
    ```bash
    php artisan migrate
    ```

7.  **(Opsional) Jalankan database seeder**
    Jika Anda memiliki data awal (contoh: data admin, data master), jalankan seeder.
    ```bash
    php artisan db:seed
    ```

8.  **Jalankan server pengembangan**
    ```bash
    php artisan serve
    ```
    Aplikasi sekarang dapat diakses melalui `http://127.0.0.1:8000`.

---
