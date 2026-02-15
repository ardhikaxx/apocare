# Apocare - Integrated Pharmacy Management System

![Laravel](https://img.shields.io/badge/Laravel-12.x-blue.svg)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4.svg)
![License](https://img.shields.io/badge/License-MIT-green.svg)

## Deskripsi Sistem

**APOCARE - Integrated Pharmacy Management System** merupakan aplikasi berbasis web yang digunakan untuk mengelola data, proses bisnis, dan operasional pada sebuah apotek secara menyeluruh dan terintegrasi. Aplikasi ini dirancang untuk membantu pemilik apotek, apoteker, dan staf dalam mengelola berbagai aktivitas operasional sehari-hari secara efisien, akurat, dan terstruktur.

Sistem APOCARE memiliki empat jenis pengguna utama dengan peran dan tanggung jawab yang berbeda-beda, yaitu Admin, Apoteker, Kasir, dan Gudang. Masing-masing pengguna memiliki akses terhadap fitur-fitur yang sesuai dengan tugas dan tanggung jawabnya dalam ekosistem apotek.

**Admin** memiliki tugas dan tanggung jawab untuk mengelola seluruh data dan konfigurasi sistem. Admin dapat mengakses semua fitur tanpa batasan, termasuk manajemen pengguna dan hak akses, master data produk dan supplier, pengelolaan laporan keuangan, serta pengaturan sistem secara keseluruhan. Admin juga bertanggung jawab dalam membuat dan mengelola akun pengguna lainnya serta mengatur peran dan permission untuk setiap pengguna.

**Apoteker** bertanggung jawab atas pengelolaan obat-obatan dan produk farmasi yang ada di apotek. Apoteker dapat mengelola data produk, kategori, dan satuan obat, melakukan monitoring stok persediaan, memproses resep dokter, melakukan penyesuaian stok akibat kerusakan atau kadaluarsa, serta melihat laporan-laporan terkait penjualan, pembelian, dan persediaan. Apoteker juga memiliki peran penting dalam memverifikasi dan memproses resep yang diajukan oleh pelanggan.

**Kasir** merupakan pengguna yang melayani langsung transaksi penjualan kepada pelanggan. Kasir dapat melakukan transaksi point-of-sale (POS), mengelola data pelanggan, memproses retur penjualan, serta melihat laporan penjualan dan laporan pelanggan. Kasir juga dapat mengakses modul resep untuk melayani pelanggan yang membawa resep dokter.

**Gudang** bertanggung jawab atas pengelolaan stok barang di gudang dan proses pembelian. Pengguna dengan peran Gudang dapat mengelola master data produk, pemasok, dan kategori, melakukan purchase order atau pembelian ke supplier, menerima barang yang datang, mengelola retur pembelian ke supplier, serta melihat laporan pembelian dan laporan persediaan.

Dalam sistem APOCARE, produk menjadi pusat penghubung antara berbagai entitas penting yaitu kategori produk, supplier atau pemasok, batch untuk tracking kadaluarsa, dan satuan produk untuk konversi unit. Transaksi penjualan dan pembelian terhubung dengan pelanggan dan pemasok masing-masing, sehingga setiap pergerakan barang dapat dilacak dengan akurat mulai dari masuknya barang dari supplier hingga penjualan kepada pelanggan akhir.

Dengan adanya APOCARE, seluruh proses operasional apotek dapat dilakukan secara digital, terstruktur, dan mudah diakses sehingga membantu meningkatkan efektivitas pengelolaan kegiatan operasional apotek. Sistem ini juga memastikan akurasi data stok, perhitungan keuangan yang tepat, dan pelaporan yang akurat untuk mendukung pengambilan keputusan strategis dalam menjalankan bisnis apotek.

### Tujuan Pembangunan Sistem

1. **Efisiensi Operasional** - Mengotomatisasi proses-proses manual yang sebelumnya memakan waktu lama, seperti pencatatan stok, pembuatan laporan, dan pencarian data produk.
2. **Akurasi Data** - Mengurangi risiko kesalahan manusia dalam perhitungan stok, harga, dan transaksi keuangan.
3. **Pengambilan Keputusan** - Menyediakan laporan-laporan yang akurat dan real-time untuk mendukung pengambilan keputusan strategis.
4. **Kepatuhan Regulasi** - Membantu apotek dalam memenuhi persyaratan regulasi yang berlaku, seperti pencatatan resep dokter dan pelaporan pajak.
5. **Pelayanan Pelanggan** - Mempercepat proses pelayanan kepada pelanggan dengan sistem POS yang cepat dan akurat.

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

## User Roles (Peran) & Hak Akses

Sistem Apocare menggunakan sistem Role-Based Access Control (RBAC) untuk mengelola akses pengguna. Setiap pengguna memiliki peran (role) tertentu yang menentukan fitur dan menu apa saja yang dapat diakses.

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

### Detail Hak Akes per Modul

Setiap modul memiliki kombinasi hak akses berikut:

| Hak Akses | Deskripsi |
|-----------|-----------|
| **view** | Melihat data/list |
| **create** | Menambah data baru |
| **update** | Mengubah data yang ada |
| **delete** | Menghapus data |
| **export** | Mengekspor data (PDF/Excel) |

#### Dashboard
- `dashboard.view` - Melihat halaman dashboard dan statistik

#### Master Data
- **Pemasok**: view, create, update, delete, export
- **Kategori**: view, create, update, delete, export
- **Satuan**: view, create, update, delete, export
- **Produk**: view, create, update, delete, export

#### Pelanggan, Dokter & Karyawan
- **Pelanggan**: view, create, update, delete, export
- **Dokter**: view, create, update, delete
- **Karyawan**: view, create, update, delete

#### Persediaan
- **Stok**: view - Melihat monitoring stok per produk dan batch
- **Penyesuaian**: view, create, delete - Mengoreksi jumlah stok (barang rusak, expired, dll)
- **Opname**: view, create, delete - Melakukan pencocokan stok fisik dengan sistem

#### Transaksi
- **Penjualan**: view, create, delete - Transaksi POS dan penjualan dengan resep
- **Pembelian**: view, create, delete - Purchase order ke pemasok
- **Retur**: view, create, delete - Retur penjualan dan pembelian
- **Resep**: view, create, delete - Kelola resep dokter

#### Laporan
- **Penjualan**: view, export - Laporan penjualan harian, mingguan, bulanan
- **Pembelian**: view, export - Laporan pembelian dan supplier
- **Persediaan**: view, export - Laporan stok barang
- **Keuangan**: view, export - Laporan keuangan (pendapatan, biaya, profit)

#### Pengguna & Sistem
- **Pengguna**: view, create, update, delete - Mengelola akun pengguna
- **Peran**: view, create, update, delete - Mengelola peran pengguna
- **Hak Akses**: view, create, update, delete - Mengelola permission per peran
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

## Struktur Project

Struktur folder project Apocare mengikuti standar Laravel dengan beberapa penyesuaian untuk kebutuhan aplikasi:

```
apocare/
├── app/
│   ├── Exports/              # Kelas untuk export Excel (Maatwebsite)
│   ├── Http/
│   │   ├── Controllers/      # Controller untuk lógica bisnis
│   │   ├── Middleware/       # Middleware (auth, role, dll)
│   │   └── Requests/        # Form request validation
│   ├── Models/               # Eloquent models
│   ├── Providers/            # Service providers
│   └── Traits/              # Reusable traits
│
├── bootstrap/                # Bootstrap file aplikasi
│   └── app.php              # Konfigurasi aplikasi
│
├── config/                  # Konfigurasi Laravel
│
├── database/
│   ├── migrations/          # Schema database
│   ├── seeders/            # Data dummy/awal
│   └── factories/          # Factory untuk testing
│
├── public/                  # File publik (entry point)
│   ├── assets/
│   │   ├── brand/          # Logo brand
│   │   ├── css/            # Custom CSS
│   │   ├── images/         # Gambar/favicon
│   │   └── js/             # Custom JavaScript
│   └── index.php           # Entry point
│
├── resources/
│   └── views/
│       ├── auth/            # View login, register, forgot password
│       ├── layouts/        # Template utama (app, auth, print)
│       ├── pages/          # Halaman utama aplikasi
│       │   ├── dashboard/  # Dashboard
│       │   ├── laporan/    # Laporan (penjualan, pembelian, dll)
│       │   ├── master/     # Master data (produk, kategori, dll)
│       │   ├── pengguna/   # Manajemen用户
│       │   └── ...
│       ├── partials/       # Komponen parsial (sidebar, navbar, dll)
│       └── print/          # Template print/export PDF
│           └── partials/   # Komponen print (kop, dll)
│
├── routes/
│   ├── api.php             # API routes
│   ├── console.php         # Console commands
│   └── web.php             # Web routes
│
├── storage/                 # File storage (logs, cache, dll)
│   ├── app/
│   ├── framework/
│   └── logs/
│
├── tests/                   # Unit & feature tests
│
├── vendor/                  # Dependencies (Composer)
│
├── .env                    # Environment variables
├── .env.example            # Contoh environment variables
├── artisan                 # CLI commands
├── composer.json           # Composer dependencies
├── package.json           # NPM dependencies
└── phpunit.xml           # PHPUnit configuration
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
