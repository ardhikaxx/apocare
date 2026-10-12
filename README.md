# 💊 Website Apocare - Integrated Pharmacy Management System

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Tailwind](https://img.shields.io/badge/Tailwind-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-Proprietary-red?style=for-the-badge)](./LICENSE)
[![Author](https://img.shields.io/badge/Author-Yanuar%20Ardhika%20Rahmadhani%20Ubaidillah-181717?style=for-the-badge&logo=github&logoColor=white)](https://github.com/ardhikaxx)

Aplikasi **Apocare** adalah sistem manajemen apotek terintegrasi: master obat, stok masuk/keluar, e-resep, kasir/POS, supplier, hingga laporan & audit log. Dirancang untuk operasional apotek kecil–menengah yang praktis dan akuntabel.

---

## ✨ Fitur Utama

### 1. 🔑 Autentikasi & RBAC
- Role Apoteker, Kasir, dan Admin dengan middleware, Policy & Gate.

### 2. 💊 Master Obat
- Kode, kategori, satuan, stok, stok minimum, harga beli/jual, batch & kedaluwarsa.

### 3. 📦 Stok Masuk & Opname
- Pencatatan stok dari supplier (atomik), riwayat pergerakan, dan stok opname berselisih.

### 4. 🧾 E-Resep & Kasir/POS
- Resep terhubung stok real-time; kasir multi-metode bayar dengan struk cetak.

### 5. 🏭 Supplier
- Master supplier untuk riwayat pengadaan.

### 6. 📊 Laporan & Audit Log
- Pendapatan, obat terlaris, stok menipis; audit login/CRUD/transaksi.

---

## 👥 Hak Akses Pengguna (Role-Based Access Control)

| Role | Akses & Wewenang |
| :--- | :--- |
| **Admin** | Pengguna, master, laporan, audit log. |
| **Apoteker** | Obat, stok, resep, supplier. |
| **Kasir** | POS, pembayaran, struk. |

> Otorisasi ditegakkan di backend (middleware/Policy) — bukan sekadar menyembunyikan menu.

---

## 🛠️ Tech Stack & Dependensi

* **Backend**: Laravel 12.x (PHP 8.2+)
* **Database**: MySQL 8.0+
* **Frontend**: Blade, {css}, SweetAlert2, vanilla JS/Chart.js
* **Testing**: PHPUnit / Pest
* **Code Style**: Laravel Pint (PSR-12)

---

## ⚙️ Panduan Instalasi & Menjalankan Aplikasi

### 1. Kloning Repositori
```bash
git clone https://github.com/ardhikaxx/apocare
cd apocare
```

### 2. Install Dependensi PHP
```bash
composer install
```

### 3. Konfigurasi Environment (`.env`)
```bash
cp .env.example .env
php artisan key:generate
```
Sesuaikan koneksi database MySQL di file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=apocare
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Migrasi & Seeding
```bash
php artisan migrate --seed
```
Development (database boleh dikosongkan):
```bash
php artisan migrate:fresh --seed
```

### 5. Jalankan Server Lokal
```bash
php artisan serve
```
Buka: `http://127.0.0.1:8000`

---

## 📚 Dokumentasi Sistem

* [⚖️ **LICENSE**](./LICENSE) — Lisensi resmi **Proprietary Software License (All Rights Reserved)**.
* [🛡️ **SECURITY.md**](./SECURITY.md) — Kebijakan keamanan & pelaporan celah (*Responsible Disclosure*).
* [🤝 **CONTRIBUTING.md**](./CONTRIBUTING.md) — Pedoman kontribusi kode & standar.
* [📜 **CODE_OF_CONDUCT.md**](./CODE_OF_CONDUCT.md) — Pedoman perilaku komunitas pengembang.
* [💬 **SUPPORT.md**](./SUPPORT.md) — Kanal bantuan teknis & komunikasi resmi pengembang.
* [📋 **CHANGELOG.md**](./CHANGELOG.md) — Riwayat rilis fitur terstruktur (*Keep a Changelog* & SemVer).
* [📚 **CITATION.md**](./CITATION.md) — Format sitasi karya untuk keperluan akademik.

---

## 💖 Dukungan & Donasi

Jika proyek **Website Apocare - Integrated Pharmacy Management System** ini bermanfaat bagi Anda dan telah menghemat banyak jam kerja Anda, Anda dapat menunjukkan apresiasi dengan memberikan traktiran kopi (donasi) melalui pemindaian kode QRIS di bawah ini:

<p align="center">
  <img src="./qris.png" alt="QRIS Donasi" width="300"/>
</p>

> Donasi sepenuhnya bersifat sukarela dan tidak mengikat. Aplikasi tetap dapat digunakan secara utuh tanpa donasi.

---

## ⚠️ Ketentuan Penggunaan

Proyek ini dilindungi lisensi **Proprietary Software License (All Rights Reserved)**. Anda **dilarang** menyalin, memodifikasi, mendistribusikan ulang, atau menjual kode ini tanpa izin tertulis dari pemilik hak cipta. Lihat [LICENSE](./LICENSE) untuk detail lengkap.

---

## 👨‍💻 Pengembang & Hak Cipta

Dirancang dan dikembangkan dengan penuh dedikasi oleh:
**[Yanuar Ardhika Rahmadhani Ubaidillah (@ardhikaxx)](https://github.com/ardhikaxx)**
*Lead Software Architect & Maintainer*

> **Copyright (c) 2024 - 2026 Yanuar Ardhika Rahmadhani Ubaidillah (@ardhikaxx). All Rights Reserved.**
