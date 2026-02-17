# Apocare - Integrated Pharmacy Management System

![Laravel](https://img.shields.io/badge/Laravel-12.x-blue.svg)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4.svg)
![License](https://img.shields.io/badge/License-MIT-green.svg)

## Deskripsi Sistem

**APOCARE - Integrated Pharmacy Management System** merupakan aplikasi berbasis web yang digunakan untuk mengelola data, proses bisnis, dan operasional pada sebuah apotek secara menyeluruh dan terintegrasi. Aplikasi ini dirancang untuk membantu pemilik apotek, apoteker, dan staf dalam mengelola berbagai aktivitas operasional sehari-hari secara efisien, akurat, dan terstruktur.

Dalam sistem APOCARE, produk menjadi pusat penghubung antara berbagai entitas penting yaitu kategori produk, supplier atau pemasok, batch untuk tracking kadaluarsa, dan satuan produk untuk konversi unit. Transaksi penjualan dan pembelian terhubung dengan pelanggan dan pemasok masing-masing, sehingga setiap pergerakan barang dapat dilacak dengan akurat mulai dari masuknya barang dari supplier hingga penjualan kepada pelanggan akhir.

### Fitur Utama

### 1. Authentication & Authorization
- Login & Register
- Forgot Password & Reset Password
- Role-Based Access Control (RBAC)
- Manajemen Hak Akses per modul

### 2. Master Data
- **Produk** - Kelola data obat & produk farmasi
- **Kategori** - Kategori produk (obat bebas, obat keras, dll)
- **Satuan** - Satuan produk (tablet, kapsul, ml, dll)
- **Pemasok** - Kelola data supplier
- **Pelanggan** - Kelola data pelanggan
- **Dokter** - Kelola data dokter
- **Karyawan** - Kelola data karyawan/apoteker

### 3. Transaksi
- **Penjualan** - Transaksi penjualan obat (POS)
- **Pembelian** - Transaksi pembelian dari pemasok
- **Resep** - Kelola resep dokter
- **Antrian Resep** - Alur kerja bertahap (DITERIMA -> DIRACIK -> DIVERIFIKASI -> DISERAHKAN) lengkap dengan board kanban dan update tahap real-time
- **Mode Offline POS** - Transaksi tetap tersimpan saat tidak ada jaringan internet dan otomatis sinkron saat online kembali
### 4. Persediaan (Inventory)
- **Stok Produk** - Monitoring stok per produk & batch
- **Stok Opname** - Pencocokan stok fisik dengan sistem
- **Penyesuaian Stok** - Koreksi stok (rusak, expired, dll)

### 5. Retur
- **Retur Penjualan** - Retur dari pelanggan
- **Retur Pembelian** - Retur ke pemasok

### 6. Laporan & Export
- Laporan Penjualan
- Laporan Pembelian
- Laporan Persediaan/Stok
- Laporan Keuangan
- Laporan Pelanggan
- **Export ke Excel** menggunakan Maatwebsite Excel
- **Export ke PDF** menggunakan DomPDF

### 7. Backup Database
- **Backup Otomatis** - Dijadwalkan setiap hari pukul 01:00 WIB
- **Backup Manual** - Bisa buat backup kapan saja via UI
- **Manajemen Backup** - Download dan hapus file backup
- **Auto Cleanup** - Sistem otomatis hapus backup lama (maksimal 30 file)

### 8. Auto Nonaktif Expired
- **Auto Nonaktif** - Produk expired otomatis dinonaktifkan
- **Scheduled Task** - Berjalan setiap pukul 00:00
- **Tracking Expired** - Monitor produk mendekati kadaluarsa

### 9. Tracking Obat Keras
- **Golongan Produk** - Umum, Keras, Psikotropika, Narcotic
- **No. IJin Edar** - Input nomor BPOM
- **Visual Badge** - Indicator warna di tabel produk
- **Expired Warning** - Warning 30 hari sebelum expired

### 10. Session Management
- **Monitor Aktif** - Lihat user yang sedang online
- **Force Logout** - Admin bisa putuskan sesi user
- **Riwayat Login** - Track history login user
- **IP Tracking** - Catat IP saat login

---

### Struktur Peran (Roles)

Sistem terdiri dari 4 peran utama dengan pembagian tugas yang jelas:

| Peran | Deskripsi |
|-------|-----------|
| **Admin** | Mengelola seluruh aspek sistem termasuk pengguna, hak akses, dan semua modul |
| **Apoteker** | Bertanggung jawab atas pengelolaan obat, penjualan dengan resep, dan persediaan |
| **Kasir** | Melakukan transaksi penjualan, melayani pelanggan, dan mengelola data pelanggan |
| **Gudang** | Mengelola stok barang, pembelian, dan retur pembelian |

### Pembagian Akses per Modul

#### Admin
- **Akses Penuh**: Semua fitur dan modul
- **Khusus**: Manajemen pengguna, peran (roles), dan hak akses (permissions)

#### Apoteker
- Master Data: Produk, Kategori, Satuan, Pemasok, Pelanggan, Dokter, Karyawan
- Persediaan: Monitoring stok, Penyesuaian stok, Stok opname
- Transaksi: Penjualan (termasuk resep), Pembelian, Retur penjualan
- Laporan: Semua jenis laporan (penjualan, pembelian, persediaan, keuangan)
- Resep: Kelola resep dokter

#### Kasir
- Transaksi: Penjualan (POS), Retur penjualan
- Pelanggan: Tambah, edit, lihat data pelanggan
- Resep: Kelola resep dokter
- Laporan: Laporan penjualan, laporan pelanggan

#### Gudang
- Master Data: Produk, Kategori, Satuan, Pemasok
- Persediaan: Monitoring stok, Penyesuaian stok, Stok opname
- Transaksi: Pembelian, Retur pembelian
- Laporan: Laporan pembelian, laporan persediaan

---

## Tech Stack

- **Backend**: Laravel 12.x (PHP 8.2+)
- **Frontend**: Bootstrap 5, Vanilla JS
- **Database**: MySQL/MariaDB
- **Export**: Maatwebsite Excel, DomPDF
- **Authentication**: Laravel Breeze / Custom Auth

---

## Installation

### Prerequisites
- PHP 8.2+
- Composer
- MySQL/MariaDB
- Node.js & NPM

### Steps

1. **Clone Repository**
```bash
git clone https://github.com/ardhikaxx/apocare.git
cd apocare
```

2. **Install Dependencies**
```bash
composer install
npm install
```

3. **Setup Environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure Database**
Edit file `.env` dengan konfigurasi database:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=apocare
DB_USERNAME=root
DB_PASSWORD=
```

5. **Run Migration & Seeder**
```bash
php artisan migrate --seed
```

6. **Build Assets**
```bash
npm run build
```

7. **Run Server**
```bash
php artisan serve
```

Akses aplikasi di `http://localhost:8000`

8. **Setup Scheduler** (Untuk auto backup)
```bash
# Windows Task Scheduler - Run command below:
php artisan schedule:run
```
Atau tambahkan cron job:
```
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## Default Users (After Seeding)

| Email | Password | Role |
|-------|----------|------|
| admin@apocare.com | password | Admin |
| apoteker@apocare.com | password | Apoteker |
| kasir@apocare.com | password | Kasir |
| gudang@apocare.com | password | Gudang |

---

## License

This project is licensed under the MIT License.

---

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

---

## Author

- **GitHub**: [ardhikaxx/apocare](https://github.com/ardhikaxx/apocare)

---

**Apocare** - Integrated Pharmacy Management System - 2026
