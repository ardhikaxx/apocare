# Apocare - Integrated Pharmacy Management System

![Laravel](https://img.shields.io/badge/Laravel-12.x-blue.svg)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4.svg)
![License](https://img.shields.io/badge/License-MIT-green.svg)

**Apocare** adalah aplikasi Sistem Informasi Apotek (Integrated Pharmacy Management System) yang dibangun menggunakan Laravel 12. Aplikasi ini mengelola seluruh aspek operasional apotek mulai dari penjualan, pembelian, pengelolaan stok, hingga laporan keuangan.

---

## Fitur Utama

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

---

## User Roles (Peran)

Pembagian akses fitur saat ini:

- **Admin**: semua fitur
- **Apoteker**: master, persediaan, penjualan, retur penjualan, resep, dokter, laporan terkait
- **Kasir**: penjualan, retur penjualan, pelanggan, resep, laporan penjualan/pelanggan
- **Gudang**: master, persediaan, pembelian, retur pembelian, laporan pembelian/persediaan

### Detail Hak Akses per Modul

#### Dashboard
- `dashboard.view` - Lihat Dashboard

#### Master Data
- **Pemasok**: view, create, update, delete, export
- **Kategori**: view, create, update, delete, export
- **Satuan**: view, create, update, delete, export
- **Produk**: view, create, update, delete, export

#### Pelanggan & Dokter & Karyawan
- **Pelanggan**: view, create, update, delete, export
- **Dokter**: view, create, update, delete
- **Karyawan**: view, create, update, delete

#### Persediaan
- **Stok**: view
- **Penyesuaian**: view, create, delete
- **Opname**: view, create, delete

#### Transaksi
- **Penjualan**: view, create, delete
- **Pembelian**: view, create, delete
- **Retur**: view, create, delete
- **Resep**: view, create, delete

#### Laporan
- **Penjualan**: view, export
- **Pembelian**: view, export
- **Persediaan**: view, export
- **Keuangan**: view, export

#### Pengguna
- **Pengguna**: view, create, update, delete
- **Peran**: Kelola peran
- **Hak Akses**: Kelola hak akses
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

---

## Default Users (After Seeding)

| Email | Password | Role |
|-------|----------|------|
| admin@apocare.com | password | Admin |
| apoteker@apocare.com | password | Apoteker |
| kasir@apocare.com | password | Kasir |
| gudang@apocare.com | password | Gudang |

---

## Project Structure

```
apocare/
â”œâ”€â”€ app/
â”‚   â”œâ”€â”€ Exports/           # Excel Export Classes
â”‚   â”œâ”€â”€ Http/
â”‚   â”‚   â””â”€â”€ Controllers/   # Controllers
â”‚   â””â”€â”€ Models/            # Eloquent Models
â”œâ”€â”€ database/
â”‚   â”œâ”€â”€ migrations/        # Database Migrations
â”‚   â””â”€â”€ seeders/          # Database Seeders
â”œâ”€â”€ resources/
â”‚   â””â”€â”€ views/            # Blade Templates
â”‚       â”œâ”€â”€ auth/         # Authentication Views
â”‚       â”œâ”€â”€ layouts/      # Layout Templates
â”‚       â”œâ”€â”€ pages/        # Page Views
â”‚       â”œâ”€â”€ partials/     # Partial Components
â”‚       â””â”€â”€ print/        # Print Templates
â”œâ”€â”€ routes/
â”‚   â””â”€â”€ web.php           # Web Routes
â””â”€â”€ public/
    â””â”€â”€ assets/           # CSS, JS, Images
```

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

**Apocare** - Integrated Pharmacy Management System
