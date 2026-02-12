# RULES & SPECIFICATIONS - SISTEM INFORMASI APOTEK

## 1. TEKNOLOGI & FRAMEWORK

### 1.1 Teknologi Frontend
- **Bootstrap 5.3.x** (via CDN) - Framework CSS untuk styling dan responsive design
- **Font Awesome 6.x** (via CDN) - Library icon untuk semua ikon dalam aplikasi
- **SweetAlert2** (via CDN) - Library untuk semua notifikasi dan alert interaktif
- **Custom CSS** - Styling tambahan yang digabungkan dengan Bootstrap

### 1.2 Backend Framework
- **Laravel 10.x** - Framework PHP untuk backend
- **Blade Templating Engine** - Engine templating bawaan Laravel

### 1.3 Database
- **MySQL/MariaDB** - Relational Database Management System

### 1.4 Library Export Laporan
- **Maatwebsite/Laravel-Excel** - Export ke Excel dan CSV
- **Barryvdh/Laravel-DomPDF** - Export ke PDF

---

## 2. STRUKTUR TEMPLATING BLADE

### 2.1 Layouts (`resources/views/layouts/`)
```
layouts/
├── app.blade.php          # Layout utama untuk halaman authenticated
├── auth.blade.php         # Layout untuk halaman login/register
└── print.blade.php        # Layout khusus untuk halaman print/cetak
```

**Penjelasan:**
- `app.blade.php` - Master layout yang berisi struktur HTML utama, CDN links, sidebar, navbar, footer, dan stack untuk styles & scripts
- `auth.blade.php` - Layout minimalis untuk halaman autentikasi (login, register, forgot password)
- `print.blade.php` - Layout bersih tanpa sidebar/navbar untuk keperluan print

### 2.2 Partials (`resources/views/partials/`)
```
partials/
├── navbar.blade.php       # Top navigation bar
├── sidebar.blade.php      # Side navigation menu
├── footer.blade.php       # Footer section
├── breadcrumb.blade.php   # Breadcrumb navigation
└── loading.blade.php      # Loading spinner component
```

**Penjelasan:**
- Setiap partial adalah komponen reusable yang di-include ke layout utama
- Menggunakan Font Awesome untuk semua icon di menu dan tombol
- Sidebar memiliki multi-level menu dengan collapse functionality

### 2.3 Pages (`resources/views/pages/`)
```
pages/
├── dashboard/
│   └── index.blade.php
├── master/
│   ├── pemasok/
│   ├── kategori/
│   ├── satuan/
│   └── produk/
├── persediaan/
│   ├── stok/
│   ├── penyesuaian/
│   └── opname/
├── transaksi/
│   ├── pembelian/
│   ├── penjualan/
│   └── retur/
├── pelanggan/
├── karyawan/
├── resep/
└── laporan/
```

**Penjelasan:**
- Setiap page menggunakan `@extends('layouts.app')`
- Menggunakan `@push('styles')` untuk CSS khusus halaman
- Menggunakan `@push('scripts')` untuk JavaScript khusus halaman
- Layout menggunakan `@stack('styles')` dan `@stack('scripts')` untuk menampung push dari pages

### 2.4 Auth Pages (`resources/views/auth/`)
```
auth/
├── login.blade.php
├── register.blade.php
├── forgot-password.blade.php
└── reset-password.blade.php
```

---

## 3. STRUKTUR DATABASE & RELASI

### 3.1 Tabel Pengguna & Autentikasi

#### `pengguna` (users)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT PK | Primary key |
| nama | VARCHAR(100) | Nama lengkap pengguna |
| email | VARCHAR(100) UNIQUE | Email pengguna |
| username | VARCHAR(50) UNIQUE | Username untuk login |
| password | VARCHAR(255) | Hashed password |
| role_id | BIGINT FK | Foreign key ke peran |
| telepon | VARCHAR(20) | Nomor telepon |
| alamat | TEXT | Alamat lengkap |
| foto | VARCHAR(255) | Path foto profil |
| status_aktif | BOOLEAN | Status aktif (default: true) |
| login_terakhir | TIMESTAMP | Waktu login terakhir |
| dibuat_oleh | BIGINT FK | Pengguna yang membuat |
| diubah_oleh | BIGINT FK | Pengguna yang update |
| deleted_at | TIMESTAMP | Soft delete |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

#### `peran` (roles)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT PK | Primary key |
| nama | VARCHAR(50) | Nama peran (Admin, Apoteker) |
| keterangan | TEXT | Deskripsi peran |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

#### `hak_akses` (permissions)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT PK | Primary key |
| nama | VARCHAR(100) | Nama hak akses |
| kode | VARCHAR(50) UNIQUE | Kode hak akses (contoh: tambah_produk) |
| modul | VARCHAR(50) | Modul/grup hak akses |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

#### `peran_hak_akses` (role_permissions)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT PK | Primary key |
| role_id | BIGINT FK | Foreign key ke peran |
| permission_id | BIGINT FK | Foreign key ke hak_akses |

**Relasi:**
- `pengguna` belongsTo `peran`
- `peran` belongsToMany `hak_akses` through `peran_hak_akses`

---

### 3.2 Tabel Data Master

#### `pemasok` (suppliers)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT PK | Primary key |
| kode | VARCHAR(20) UNIQUE | Kode pemasok (auto-generate) |
| nama | VARCHAR(100) | Nama pemasok |
| kontak_person | VARCHAR(100) | Nama kontak person |
| telepon | VARCHAR(20) | Nomor telepon |
| email | VARCHAR(100) | Email pemasok |
| alamat | TEXT | Alamat lengkap |
| kota | VARCHAR(50) | Kota |
| provinsi | VARCHAR(50) | Provinsi |
| kode_pos | VARCHAR(10) | Kode pos |
| npwp | VARCHAR(30) | NPWP |
| termin_pembayaran | INT | Termin pembayaran (hari) |
| limit_kredit | DECIMAL(15,2) | Limit kredit |
| status_aktif | BOOLEAN | Status aktif |
| catatan | TEXT | Catatan |
| dibuat_oleh | BIGINT FK | Pengguna yang membuat |
| diubah_oleh | BIGINT FK | Pengguna yang update |
| deleted_at | TIMESTAMP | Soft delete |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

#### `kategori` (categories)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT PK | Primary key |
| kode | VARCHAR(20) UNIQUE | Kode kategori |
| nama | VARCHAR(100) | Nama kategori |
| parent_id | BIGINT FK | Parent category (untuk sub-kategori) |
| keterangan | TEXT | Deskripsi kategori |
| ikon | VARCHAR(50) | Icon Font Awesome |
| status_aktif | BOOLEAN | Status aktif |
| dibuat_oleh | BIGINT FK | Pengguna yang membuat |
| diubah_oleh | BIGINT FK | Pengguna yang update |
| deleted_at | TIMESTAMP | Soft delete |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

#### `satuan` (units)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT PK | Primary key |
| kode | VARCHAR(10) UNIQUE | Kode satuan (PCS, BOX, STRIP, dll) |
| nama | VARCHAR(50) | Nama satuan |
| keterangan | TEXT | Deskripsi |
| status_aktif | BOOLEAN | Status aktif |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

#### `produk` (products)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT PK | Primary key |
| kode | VARCHAR(30) UNIQUE | Kode produk/SKU |
| barcode | VARCHAR(50) UNIQUE | Barcode produk |
| nama | VARCHAR(200) | Nama produk |
| nama_generik | VARCHAR(200) | Nama generik |
| kategori_id | BIGINT FK | Foreign key ke kategori |
| satuan_id | BIGINT FK | Foreign key ke satuan (satuan dasar) |
| produsen | VARCHAR(100) | Nama pabrik/manufaktur |
| keterangan | TEXT | Deskripsi produk |
| jenis_produk | ENUM | Obat, Alkes, Vitamin, Kosmetik, Umum |
| golongan_obat | ENUM | Obat Bebas, Obat Bebas Terbatas, Obat Keras, Narkotika, Psikotropika |
| perlu_resep | BOOLEAN | Perlu resep dokter |
| harga_beli | DECIMAL(15,2) | Harga beli terakhir |
| harga_jual | DECIMAL(15,2) | Harga jual |
| stok_minimum | INT | Stok minimum |
| stok_maksimum | INT | Stok maksimum |
| titik_pesan_ulang | INT | Titik pemesanan ulang |
| lokasi_rak | VARCHAR(20) | Lokasi rak penyimpanan |
| kondisi_penyimpanan | TEXT | Kondisi penyimpanan (suhu, dll) |
| gambar | VARCHAR(255) | Path gambar produk |
| status_aktif | BOOLEAN | Status aktif |
| konsinyasi | BOOLEAN | Apakah konsinyasi |
| persentase_pajak | DECIMAL(5,2) | Persentase pajak (PPN) |
| catatan | TEXT | Catatan |
| dibuat_oleh | BIGINT FK | Pengguna yang membuat |
| diubah_oleh | BIGINT FK | Pengguna yang update |
| deleted_at | TIMESTAMP | Soft delete |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

#### `satuan_produk` (product_units)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT PK | Primary key |
| produk_id | BIGINT FK | Foreign key ke produk |
| satuan_id | BIGINT FK | Foreign key ke satuan |
| faktor_konversi | DECIMAL(10,2) | Faktor konversi ke satuan dasar |
| barcode | VARCHAR(50) | Barcode untuk satuan ini |
| harga_beli | DECIMAL(15,2) | Harga beli untuk satuan ini |
| harga_jual | DECIMAL(15,2) | Harga jual untuk satuan ini |
| default_pembelian | BOOLEAN | Satuan default pembelian |
| default_penjualan | BOOLEAN | Satuan default penjualan |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

**Relasi:**
- `produk` belongsTo `kategori`
- `produk` belongsTo `satuan` (satuan dasar)
- `produk` hasMany `satuan_produk` (satuan konversi)
- `produk` hasMany `stok_produk`
- `produk` hasMany `batch_produk`
- `kategori` hasMany `kategori` (self-relation untuk parent-child)

---

### 3.3 Tabel Manajemen Persediaan

#### `stok_produk` (product_stocks)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT PK | Primary key |
| produk_id | BIGINT FK | Foreign key ke produk |
| jumlah | DECIMAL(10,2) | Jumlah stok saat ini |
| jumlah_reservasi | DECIMAL(10,2) | Stok yang di-reserved |
| jumlah_tersedia | DECIMAL(10,2) | Stok tersedia (jumlah - reservasi) |
| harga_beli_terakhir | DECIMAL(15,2) | Harga beli terakhir |
| harga_beli_rata | DECIMAL(15,2) | Harga beli rata-rata |
| terakhir_diubah | TIMESTAMP | Terakhir update stok |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

#### `batch_produk` (product_batches)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT PK | Primary key |
| produk_id | BIGINT FK | Foreign key ke produk |
| nomor_batch | VARCHAR(50) | Nomor batch |
| tanggal_produksi | DATE | Tanggal produksi |
| tanggal_kadaluarsa | DATE | Tanggal kadaluarsa |
| jumlah | DECIMAL(10,2) | Jumlah stok batch ini |
| harga_beli | DECIMAL(15,2) | Harga beli batch ini |
| pemasok_id | BIGINT FK | Foreign key ke pemasok |
| pembelian_id | BIGINT FK | Foreign key ke pembelian |
| sudah_kadaluarsa | BOOLEAN | Status kadaluarsa |
| catatan | TEXT | Catatan |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

#### `pergerakan_stok` (stock_movements)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT PK | Primary key |
| produk_id | BIGINT FK | Foreign key ke produk |
| batch_id | BIGINT FK | Foreign key ke batch_produk |
| jenis_pergerakan | ENUM | MASUK, KELUAR, PENYESUAIAN, RETUR, KADALUARSA, RUSAK |
| tipe_referensi | VARCHAR(50) | Model referensi (Pembelian, Penjualan, Penyesuaian, dll) |
| id_referensi | BIGINT | ID dari referensi |
| jumlah | DECIMAL(10,2) | Jumlah pergerakan (+/-) |
| jumlah_sebelum | DECIMAL(10,2) | Stok sebelum |
| jumlah_sesudah | DECIMAL(10,2) | Stok sesudah |
| harga_satuan | DECIMAL(15,2) | Harga satuan |
| catatan | TEXT | Catatan |
| dibuat_oleh | BIGINT FK | Pengguna yang membuat |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

#### `penyesuaian_stok` (stock_adjustments)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT PK | Primary key |
| nomor_penyesuaian | VARCHAR(30) UNIQUE | Nomor penyesuaian |
| tanggal_penyesuaian | DATE | Tanggal penyesuaian |
| jenis_penyesuaian | ENUM | PENAMBAHAN, PENGURANGAN, RUSAK, KADALUARSA, KOREKSI |
| status | ENUM | DRAFT, DISETUJUI, DITOLAK |
| total_item | INT | Total item |
| catatan | TEXT | Catatan/alasan |
| disetujui_oleh | BIGINT FK | Pengguna yang menyetujui |
| waktu_persetujuan | TIMESTAMP | Waktu approval |
| dibuat_oleh | BIGINT FK | Pengguna yang membuat |
| diubah_oleh | BIGINT FK | Pengguna yang update |
| deleted_at | TIMESTAMP | Soft delete |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

#### `detail_penyesuaian_stok` (stock_adjustment_details)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT PK | Primary key |
| penyesuaian_id | BIGINT FK | Foreign key ke penyesuaian_stok |
| produk_id | BIGINT FK | Foreign key ke produk |
| batch_id | BIGINT FK | Foreign key ke batch_produk |
| jumlah_sistem | DECIMAL(10,2) | Stok menurut sistem |
| jumlah_aktual | DECIMAL(10,2) | Stok aktual/fisik |
| selisih | DECIMAL(10,2) | Selisih |
| harga_satuan | DECIMAL(15,2) | Harga satuan |
| total_nilai | DECIMAL(15,2) | Total nilai selisih |
| catatan | TEXT | Catatan |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

#### `stok_opname` (stock_opnames)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT PK | Primary key |
| nomor_opname | VARCHAR(30) UNIQUE | Nomor stock opname |
| tanggal_opname | DATE | Tanggal stock opname |
| status | ENUM | DRAFT, PROSES, SELESAI, DISETUJUI |
| kategori_id | BIGINT FK | Kategori yang di-opname (optional) |
| total_item_dihitung | INT | Total item yang dihitung |
| total_item_cocok | INT | Total item yang cocok |
| total_item_selisih | INT | Total item ada selisih |
| total_nilai_selisih | DECIMAL(15,2) | Total nilai selisih |
| catatan | TEXT | Catatan |
| disetujui_oleh | BIGINT FK | Pengguna yang menyetujui |
| waktu_persetujuan | TIMESTAMP | Waktu approval |
| dibuat_oleh | BIGINT FK | Pengguna yang membuat |
| diubah_oleh | BIGINT FK | Pengguna yang update |
| deleted_at | TIMESTAMP | Soft delete |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

#### `detail_stok_opname` (stock_opname_details)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT PK | Primary key |
| opname_id | BIGINT FK | Foreign key ke stok_opname |
| produk_id | BIGINT FK | Foreign key ke produk |
| batch_id | BIGINT FK | Foreign key ke batch_produk |
| jumlah_sistem | DECIMAL(10,2) | Stok menurut sistem |
| jumlah_hitung | DECIMAL(10,2) | Stok hasil hitung fisik |
| selisih | DECIMAL(10,2) | Selisih |
| harga_satuan | DECIMAL(15,2) | Harga satuan |
| total_nilai_selisih | DECIMAL(15,2) | Total nilai selisih |
| status | ENUM | COCOK, LEBIH, KURANG |
| dihitung_oleh | BIGINT FK | Pengguna yang menghitung |
| waktu_hitung | TIMESTAMP | Waktu hitung |
| catatan | TEXT | Catatan |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

**Relasi:**
- `stok_produk` belongsTo `produk`
- `batch_produk` belongsTo `produk`
- `batch_produk` belongsTo `pemasok`
- `pergerakan_stok` belongsTo `produk`
- `pergerakan_stok` belongsTo `batch_produk`
- `penyesuaian_stok` hasMany `detail_penyesuaian_stok`
- `stok_opname` hasMany `detail_stok_opname`

---

### 3.4 Tabel Transaksi Pembelian

#### `pembelian` (purchases)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT PK | Primary key |
| nomor_pembelian | VARCHAR(30) UNIQUE | Nomor pembelian (auto-generate) |
| nomor_po | VARCHAR(30) | Nomor PO (optional) |
| pemasok_id | BIGINT FK | Foreign key ke pemasok |
| tanggal_pembelian | DATE | Tanggal pembelian |
| tanggal_jatuh_tempo | DATE | Tanggal jatuh tempo |
| status | ENUM | DRAFT, DIPESAN, SEBAGIAN, DITERIMA, SELESAI, BATAL |
| status_pembayaran | ENUM | BELUM_BAYAR, SEBAGIAN, LUNAS |
| metode_pembayaran | ENUM | TUNAI, TRANSFER, KREDIT, GIRO |
| subtotal | DECIMAL(15,2) | Subtotal sebelum pajak & diskon |
| jenis_diskon | ENUM | PERSENTASE, NOMINAL |
| nilai_diskon | DECIMAL(15,2) | Nilai diskon |
| jumlah_diskon | DECIMAL(15,2) | Jumlah diskon |
| jumlah_pajak | DECIMAL(15,2) | Jumlah pajak (PPN) |
| biaya_kirim | DECIMAL(15,2) | Biaya pengiriman |
| biaya_lain | DECIMAL(15,2) | Biaya lain-lain |
| total_akhir | DECIMAL(15,2) | Total akhir |
| jumlah_bayar | DECIMAL(15,2) | Jumlah yang sudah dibayar |
| sisa_bayar | DECIMAL(15,2) | Sisa yang harus dibayar |
| nomor_faktur | VARCHAR(50) | Nomor faktur pemasok |
| tanggal_faktur | DATE | Tanggal faktur |
| nomor_faktur_pajak | VARCHAR(50) | Nomor faktur pajak |
| catatan | TEXT | Catatan |
| disetujui_oleh | BIGINT FK | Pengguna yang menyetujui |
| waktu_persetujuan | TIMESTAMP | Waktu approval |
| diterima_oleh | BIGINT FK | Pengguna yang menerima barang |
| waktu_penerimaan | TIMESTAMP | Waktu penerimaan |
| dibuat_oleh | BIGINT FK | Pengguna yang membuat |
| diubah_oleh | BIGINT FK | Pengguna yang update |
| deleted_at | TIMESTAMP | Soft delete |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

#### `detail_pembelian` (purchase_details)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT PK | Primary key |
| pembelian_id | BIGINT FK | Foreign key ke pembelian |
| produk_id | BIGINT FK | Foreign key ke produk |
| satuan_produk_id | BIGINT FK | Foreign key ke satuan_produk |
| nomor_batch | VARCHAR(50) | Nomor batch |
| tanggal_produksi | DATE | Tanggal produksi |
| tanggal_kadaluarsa | DATE | Tanggal kadaluarsa |
| jumlah_pesan | DECIMAL(10,2) | Jumlah yang dipesan |
| jumlah_terima | DECIMAL(10,2) | Jumlah yang diterima |
| harga_satuan | DECIMAL(15,2) | Harga satuan |
| persentase_diskon | DECIMAL(5,2) | Diskon per item (%) |
| jumlah_diskon | DECIMAL(15,2) | Jumlah diskon per item |
| persentase_pajak | DECIMAL(5,2) | Pajak per item (%) |
| jumlah_pajak | DECIMAL(15,2) | Jumlah pajak per item |
| subtotal | DECIMAL(15,2) | Subtotal item |
| total | DECIMAL(15,2) | Total item setelah diskon & pajak |
| catatan | TEXT | Catatan |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

#### `pembayaran_pembelian` (purchase_payments)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT PK | Primary key |
| pembelian_id | BIGINT FK | Foreign key ke pembelian |
| nomor_pembayaran | VARCHAR(30) UNIQUE | Nomor pembayaran |
| tanggal_bayar | DATE | Tanggal pembayaran |
| metode_pembayaran | ENUM | TUNAI, TRANSFER, KREDIT, GIRO |
| jumlah | DECIMAL(15,2) | Jumlah pembayaran |
| nama_bank | VARCHAR(100) | Nama bank (jika transfer) |
| nomor_rekening | VARCHAR(50) | Nomor rekening |
| nomor_giro | VARCHAR(50) | Nomor giro |
| tanggal_giro | DATE | Tanggal giro |
| nomor_referensi | VARCHAR(50) | Nomor referensi |
| catatan | TEXT | Catatan |
| dibuat_oleh | BIGINT FK | Pengguna yang membuat |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

#### `retur_pembelian` (purchase_returns)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT PK | Primary key |
| nomor_retur | VARCHAR(30) UNIQUE | Nomor retur pembelian |
| pembelian_id | BIGINT FK | Foreign key ke pembelian |
| pemasok_id | BIGINT FK | Foreign key ke pemasok |
| tanggal_retur | DATE | Tanggal retur |
| alasan | TEXT | Alasan retur |
| status | ENUM | PENDING, DISETUJUI, DITOLAK, SELESAI |
| subtotal | DECIMAL(15,2) | Subtotal retur |
| jumlah_pajak | DECIMAL(15,2) | Jumlah pajak retur |
| total | DECIMAL(15,2) | Total retur |
| metode_refund | ENUM | TUNAI, TRANSFER, NOTA_KREDIT |
| jumlah_refund | DECIMAL(15,2) | Jumlah refund |
| catatan | TEXT | Catatan |
| disetujui_oleh | BIGINT FK | Pengguna yang menyetujui |
| waktu_persetujuan | TIMESTAMP | Waktu approval |
| dibuat_oleh | BIGINT FK | Pengguna yang membuat |
| diubah_oleh | BIGINT FK | Pengguna yang update |
| deleted_at | TIMESTAMP | Soft delete |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

#### `detail_retur_pembelian` (purchase_return_details)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT PK | Primary key |
| retur_id | BIGINT FK | Foreign key ke retur_pembelian |
| detail_pembelian_id | BIGINT FK | Foreign key ke detail_pembelian |
| produk_id | BIGINT FK | Foreign key ke produk |
| batch_id | BIGINT FK | Foreign key ke batch_produk |
| jumlah | DECIMAL(10,2) | Jumlah yang diretur |
| harga_satuan | DECIMAL(15,2) | Harga satuan |
| persentase_pajak | DECIMAL(5,2) | Pajak (%) |
| jumlah_pajak | DECIMAL(15,2) | Jumlah pajak |
| subtotal | DECIMAL(15,2) | Subtotal item |
| total | DECIMAL(15,2) | Total item |
| alasan | TEXT | Alasan retur item |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

**Relasi:**
- `pembelian` belongsTo `pemasok`
- `pembelian` hasMany `detail_pembelian`
- `pembelian` hasMany `pembayaran_pembelian`
- `detail_pembelian` belongsTo `produk`
- `detail_pembelian` belongsTo `satuan_produk`
- `retur_pembelian` belongsTo `pembelian`
- `retur_pembelian` belongsTo `pemasok`
- `retur_pembelian` hasMany `detail_retur_pembelian`

---

### 3.5 Tabel Pelanggan

#### `pelanggan` (customers)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT PK | Primary key |
| kode | VARCHAR(20) UNIQUE | Kode pelanggan (auto-generate) |
| nama | VARCHAR(100) | Nama pelanggan |
| jenis_pelanggan | ENUM | REGULAR, RESELLER, KESEHATAN, PERUSAHAAN |
| jenis_identitas | ENUM | KTP, SIM, PASSPORT |
| nomor_identitas | VARCHAR(50) | Nomor identitas |
| jenis_kelamin | ENUM | PRIA, WANITA |
| tanggal_lahir | DATE | Tanggal lahir |
| telepon | VARCHAR(20) | Nomor telepon |
| email | VARCHAR(100) | Email |
| alamat | TEXT | Alamat lengkap |
| kota | VARCHAR(50) | Kota |
| provinsi | VARCHAR(50) | Provinsi |
| kode_pos | VARCHAR(10) | Kode pos |
| persentase_diskon | DECIMAL(5,2) | Diskon default (%) |
| limit_kredit | DECIMAL(15,2) | Limit kredit |
| termin_pembayaran | INT | Termin pembayaran (hari) |
| total_pembelian | DECIMAL(15,2) | Total pembelian |
| tanggal_beli_terakhir | DATE | Tanggal pembelian terakhir |
| status_aktif | BOOLEAN | Status aktif |
| catatan | TEXT | Catatan |
| dibuat_oleh | BIGINT FK | Pengguna yang membuat |
| diubah_oleh | BIGINT FK | Pengguna yang update |
| deleted_at | TIMESTAMP | Soft delete |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

**Relasi:**
- `pelanggan` hasMany `penjualan`
- `pelanggan` hasMany `resep`

---

### 3.6 Tabel Transaksi Penjualan

#### `penjualan` (sales)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT PK | Primary key |
| nomor_penjualan | VARCHAR(30) UNIQUE | Nomor penjualan/struk |
| pelanggan_id | BIGINT FK | Foreign key ke pelanggan (optional) |
| resep_id | BIGINT FK | Foreign key ke resep (optional) |
| tanggal_penjualan | DATETIME | Tanggal & waktu penjualan |
| jenis_penjualan | ENUM | RETAIL, GROSIR, RESEP, ONLINE |
| status_pembayaran | ENUM | BELUM_BAYAR, SEBAGIAN, LUNAS |
| metode_pembayaran | ENUM | TUNAI, DEBIT, KREDIT, TRANSFER, EWALLET, QRIS |
| subtotal | DECIMAL(15,2) | Subtotal sebelum diskon & pajak |
| jenis_diskon | ENUM | PERSENTASE, NOMINAL |
| nilai_diskon | DECIMAL(15,2) | Nilai diskon |
| jumlah_diskon | DECIMAL(15,2) | Jumlah diskon |
| jumlah_pajak | DECIMAL(15,2) | Jumlah pajak (PPN) |
| total_akhir | DECIMAL(15,2) | Total akhir |
| jumlah_bayar | DECIMAL(15,2) | Jumlah yang dibayar |
| jumlah_kembalian | DECIMAL(15,2) | Kembalian |
| nomor_kartu | VARCHAR(50) | Nomor kartu (debit/credit) |
| nama_pemegang_kartu | VARCHAR(100) | Nama pemegang kartu |
| kode_approval | VARCHAR(50) | Kode approval EDC |
| catatan | TEXT | Catatan |
| dilayani_oleh | BIGINT FK | Kasir yang melayani |
| dibuat_oleh | BIGINT FK | Pengguna yang membuat |
| diubah_oleh | BIGINT FK | Pengguna yang update |
| deleted_at | TIMESTAMP | Soft delete |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

#### `detail_penjualan` (sale_details)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT PK | Primary key |
| penjualan_id | BIGINT FK | Foreign key ke penjualan |
| produk_id | BIGINT FK | Foreign key ke produk |
| satuan_produk_id | BIGINT FK | Foreign key ke satuan_produk |
| batch_id | BIGINT FK | Foreign key ke batch_produk |
| jumlah | DECIMAL(10,2) | Jumlah |
| harga_satuan | DECIMAL(15,2) | Harga satuan |
| persentase_diskon | DECIMAL(5,2) | Diskon per item (%) |
| jumlah_diskon | DECIMAL(15,2) | Jumlah diskon |
| persentase_pajak | DECIMAL(5,2) | Pajak per item (%) |
| jumlah_pajak | DECIMAL(15,2) | Jumlah pajak |
| subtotal | DECIMAL(15,2) | Subtotal item |
| total | DECIMAL(15,2) | Total item |
| harga_pokok | DECIMAL(15,2) | Harga pokok (untuk laporan laba) |
| keuntungan | DECIMAL(15,2) | Keuntungan |
| catatan | TEXT | Catatan (untuk resep) |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

#### `retur_penjualan` (sale_returns)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT PK | Primary key |
| nomor_retur | VARCHAR(30) UNIQUE | Nomor retur penjualan |
| penjualan_id | BIGINT FK | Foreign key ke penjualan |
| pelanggan_id | BIGINT FK | Foreign key ke pelanggan |
| tanggal_retur | DATETIME | Tanggal retur |
| alasan | TEXT | Alasan retur |
| status | ENUM | PENDING, DISETUJUI, DITOLAK, SELESAI |
| subtotal | DECIMAL(15,2) | Subtotal retur |
| jumlah_pajak | DECIMAL(15,2) | Jumlah pajak retur |
| total | DECIMAL(15,2) | Total retur |
| metode_refund | ENUM | TUNAI, TRANSFER, NOTA_KREDIT |
| jumlah_refund | DECIMAL(15,2) | Jumlah refund |
| catatan | TEXT | Catatan |
| disetujui_oleh | BIGINT FK | Pengguna yang menyetujui |
| waktu_persetujuan | TIMESTAMP | Waktu approval |
| dibuat_oleh | BIGINT FK | Pengguna yang membuat |
| diubah_oleh | BIGINT FK | Pengguna yang update |
| deleted_at | TIMESTAMP | Soft delete |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

#### `detail_retur_penjualan` (sale_return_details)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT PK | Primary key |
| retur_id | BIGINT FK | Foreign key ke retur_penjualan |
| detail_penjualan_id | BIGINT FK | Foreign key ke detail_penjualan |
| produk_id | BIGINT FK | Foreign key ke produk |
| batch_id | BIGINT FK | Foreign key ke batch_produk |
| jumlah | DECIMAL(10,2) | Jumlah yang diretur |
| harga_satuan | DECIMAL(15,2) | Harga satuan |
| persentase_pajak | DECIMAL(5,2) | Pajak (%) |
| jumlah_pajak | DECIMAL(15,2) | Jumlah pajak |
| subtotal | DECIMAL(15,2) | Subtotal item |
| total | DECIMAL(15,2) | Total item |
| alasan | TEXT | Alasan retur item |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

**Relasi:**
- `penjualan` belongsTo `pelanggan`
- `penjualan` belongsTo `resep`
- `penjualan` belongsTo `pengguna` (dilayani_oleh)
- `penjualan` hasMany `detail_penjualan`
- `detail_penjualan` belongsTo `produk`
- `detail_penjualan` belongsTo `batch_produk`
- `retur_penjualan` belongsTo `penjualan`
- `retur_penjualan` hasMany `detail_retur_penjualan`

---

### 3.7 Tabel Resep Dokter

#### `dokter` (doctors)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT PK | Primary key |
| kode | VARCHAR(20) UNIQUE | Kode dokter |
| nama | VARCHAR(100) | Nama dokter |
| spesialisasi | VARCHAR(100) | Spesialisasi |
| nomor_sip | VARCHAR(50) | Nomor SIP (Surat Izin Praktek) |
| telepon | VARCHAR(20) | Nomor telepon |
| email | VARCHAR(100) | Email |
| rumah_sakit | VARCHAR(100) | Rumah sakit/klinik |
| alamat | TEXT | Alamat |
| status_aktif | BOOLEAN | Status aktif |
| catatan | TEXT | Catatan |
| dibuat_oleh | BIGINT FK | Pengguna yang membuat |
| diubah_oleh | BIGINT FK | Pengguna yang update |
| deleted_at | TIMESTAMP | Soft delete |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

#### `resep` (prescriptions)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT PK | Primary key |
| nomor_resep | VARCHAR(30) UNIQUE | Nomor resep |
| tanggal_resep | DATE | Tanggal resep |
| pelanggan_id | BIGINT FK | Foreign key ke pelanggan (pasien) |
| dokter_id | BIGINT FK | Foreign key ke dokter |
| diagnosa | TEXT | Diagnosa penyakit |
| status | ENUM | PENDING, SEBAGIAN, SELESAI, BATAL |
| total_item | INT | Total item obat |
| total_harga | DECIMAL(15,2) | Total harga |
| catatan | TEXT | Catatan |
| apoteker_id | BIGINT FK | Apoteker yang melayani |
| waktu_verifikasi | TIMESTAMP | Waktu verifikasi |
| dibuat_oleh | BIGINT FK | Pengguna yang membuat |
| diubah_oleh | BIGINT FK | Pengguna yang update |
| deleted_at | TIMESTAMP | Soft delete |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

#### `detail_resep` (prescription_details)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT PK | Primary key |
| resep_id | BIGINT FK | Foreign key ke resep |
| produk_id | BIGINT FK | Foreign key ke produk |
| dosis | VARCHAR(50) | Dosis (contoh: 500mg) |
| frekuensi | VARCHAR(50) | Frekuensi (3x sehari) |
| durasi | VARCHAR(50) | Durasi (7 hari) |
| cara_pakai | VARCHAR(50) | Cara penggunaan (oral, topikal, dll) |
| jumlah_resep | DECIMAL(10,2) | Jumlah yang diresepkan |
| jumlah_diberikan | DECIMAL(10,2) | Jumlah yang diberikan |
| harga_satuan | DECIMAL(15,2) | Harga satuan |
| total | DECIMAL(15,2) | Total harga |
| instruksi | TEXT | Instruksi penggunaan |
| catatan | TEXT | Catatan |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

**Relasi:**
- `resep` belongsTo `pelanggan`
- `resep` belongsTo `dokter`
- `resep` belongsTo `pengguna` (apoteker)
- `resep` hasMany `detail_resep`
- `resep` hasOne `penjualan`

---

### 3.8 Tabel Karyawan

#### `karyawan` (employees)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT PK | Primary key |
| pengguna_id | BIGINT FK | Foreign key ke pengguna |
| nomor_karyawan | VARCHAR(20) UNIQUE | Nomor karyawan |
| nomor_identitas | VARCHAR(50) | NIK/KTP |
| npwp | VARCHAR(30) | NPWP |
| jabatan | VARCHAR(50) | Jabatan |
| departemen | VARCHAR(50) | Departemen |
| status_kepegawaian | ENUM | TETAP, KONTRAK, MAGANG, FREELANCE |
| tanggal_bergabung | DATE | Tanggal bergabung |
| tanggal_resign | DATE | Tanggal resign (jika ada) |
| pendidikan | VARCHAR(50) | Pendidikan terakhir |
| nomor_lisensi | VARCHAR(50) | Nomor STRA/SIPA (untuk apoteker) |
| kadaluarsa_lisensi | DATE | Kadaluarsa lisensi |
| nama_bank | VARCHAR(100) | Nama bank |
| nomor_rekening | VARCHAR(50) | Nomor rekening |
| kontak_darurat_nama | VARCHAR(100) | Nama kontak darurat |
| kontak_darurat_telepon | VARCHAR(20) | No telp kontak darurat |
| kontak_darurat_hubungan | VARCHAR(50) | Hubungan kontak darurat |
| status_aktif | BOOLEAN | Status aktif |
| catatan | TEXT | Catatan |
| dibuat_oleh | BIGINT FK | Pengguna yang membuat |
| diubah_oleh | BIGINT FK | Pengguna yang update |
| deleted_at | TIMESTAMP | Soft delete |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

**Relasi:**
- `karyawan` belongsTo `pengguna`

---

## 4. FITUR-FITUR LENGKAP APLIKASI

### 4.1 Dashboard
- **Ringkasan Statistik**
  - Total penjualan hari ini, minggu ini, bulan ini, tahun ini
  - Total pembelian periode tertentu
  - Total profit/keuntungan
  - Jumlah transaksi
  - Rata-rata nilai transaksi
  - Grafik penjualan (line chart) dengan filter periode
  - Grafik kategori produk terlaris (pie chart)
  - Grafik metode pembayaran (doughnut chart)
  
- **Kartu Statistik Cepat**
  - Total produk
  - Total pelanggan
  - Total pemasok
  - Produk stok menipis (dengan alert badge)
  - Produk kadaluarsa (dalam 30/60/90 hari)
  - Produk best seller top 10
  - Produk slow moving

- **Timeline Aktivitas**
  - Aktivitas terbaru (penjualan, pembelian, stok, dll)
  - Notifikasi real-time

- **Peringatan & Pengingat**
  - Produk mendekati expired date
  - Produk stok dibawah minimum
  - Hutang pemasok yang jatuh tempo
  - Piutang pelanggan yang jatuh tempo
  - Purchase order yang belum diterima

### 4.2 Manajemen Data Master

#### 4.2.1 Manajemen Pemasok
- **CRUD Pemasok**
  - Tambah, edit, hapus (soft delete), restore pemasok
  - Import pemasok dari Excel/CSV
  - Export daftar pemasok ke Excel/CSV/PDF
  - Validasi nomor telepon, email, NPWP
  
- **Fitur Lanjutan**
  - Filter & pencarian multi-field
  - Sorting semua kolom
  - Pagination dengan opsi jumlah per halaman
  - Status aktif/nonaktif pemasok
  - Track histori pembelian dari pemasok
  - Evaluasi pemasok (rating, delivery time, quality)
  - Notifikasi hutang jatuh tempo
  - QR Code untuk quick access profil pemasok

#### 4.2.2 Manajemen Kategori Produk
- **CRUD Kategori**
  - Multi-level kategori (parent-child unlimited)
  - Drag & drop untuk reorder kategori
  - Icon selection dari Font Awesome
  - Bulk actions (activate, deactivate, delete)
  
- **Fitur Lanjutan**
  - Tree view untuk hierarki kategori
  - Count produk per kategori
  - Export struktur kategori

#### 4.2.3 Manajemen Satuan
- **CRUD Satuan**
  - Satuan dasar (PCS, BOX, STRIP, BOTOL, dll)
  - Status aktif/nonaktif
  
#### 4.2.4 Manajemen Produk
- **CRUD Produk**
  - Form lengkap dengan validasi
  - Auto-generate kode produk dengan prefix
  - Barcode scanner integration
  - Barcode generator & print label
  - Upload gambar produk (multi-upload)
  - Crop & resize image
  
- **Multi Satuan**
  - Setup konversi satuan (contoh: 1 BOX = 10 STRIP = 100 PCS)
  - Harga berbeda per satuan
  - Barcode berbeda per satuan
  - Default satuan pembelian & penjualan
  
- **Batch & Expired Management**
  - Multi batch per produk
  - Track nomor batch, production date, expiry date
  - Alert produk mendekati/sudah expired
  - FIFO/FEFO method untuk stock out
  
- **Fitur Lanjutan**
  - Import produk dari Excel dengan template
  - Export produk ke Excel/CSV/PDF
  - Bulk update harga (by percentage atau fixed amount)
  - Bulk update kategori, satuan, dll
  - Clone/duplicate produk
  - Product history (price changes, stock movements)
  - QR Code untuk produk
  - Print label harga (dengan berbagai template)
  - Filter advanced (kategori, pemasok, price range, stock status)
  - Gallery view & list view
  - Related products suggestion

### 4.3 Manajemen Persediaan

#### 4.3.1 Stok Produk
- **Real-time Stock Monitoring**
  - View stok per produk dengan semua batch
  - Stock level indicator (aman, warning, kritis)
  - Filter by kategori, pemasok, lokasi rak
  - Multi-lokasi gudang (jika ada cabang)
  
- **Laporan Stok**
  - Laporan stok lengkap dengan nilai
  - Stok per kategori
  - Stok per pemasok
  - Stock aging report
  - Export ke Excel/CSV/PDF

#### 4.3.2 Penyesuaian Stok
- **Manual Adjustment**
  - Increase/decrease stok dengan alasan
  - Multi-item adjustment dalam satu transaksi
  - Upload bukti foto (untuk damage/expired)
  - Workflow approval (create → approve → post)
  - History tracking semua adjustment
  
- **Auto Adjustment**
  - Adjustment otomatis dari stock opname
  - Adjustment dari retur
  - Adjustment dari expired/damaged goods
  
- **Laporan**
  - Laporan penyesuaian per periode
  - Laporan per jenis adjustment
  - Export Excel/CSV/PDF

#### 4.3.3 Stok Opname
- **Workflow Stock Opname**
  - Buat dokumen opname (pilih kategori/semua produk)
  - Print form opname (barcode, nama produk, stok sistem)
  - Input hasil hitung fisik (bisa via barcode scanner)
  - Compare sistem vs fisik
  - Generate adjustment otomatis untuk selisih
  - Approval workflow
  
- **Features**
  - Opname partial (per kategori) atau full
  - Opname scheduled (recurring)
  - Multi-user counting dengan assignment
  - Variance report dengan drill-down
  - Export hasil opname

#### 4.3.4 Histori Pergerakan Stok
- **Tracking**
  - Semua movement (MASUK/KELUAR) tercatat otomatis
  - Filter by produk, tanggal, jenis movement
  - Drill-down ke dokumen sumber
  - Export history

### 4.4 Transaksi Pembelian

#### 4.4.1 Purchase Order (PO)
- **Buat PO**
  - Multi-product dalam satu PO
  - Pilih pemasok, auto-fill data pemasok
  - Input expected delivery date
  - Diskon per item dan global
  - Pajak per item dan global
  - Biaya pengiriman & biaya lain
  - Notes & terms
  
- **PO Status Tracking**
  - DRAFT → DIPESAN → SEBAGIAN → DITERIMA → SELESAI
  - Email/print PO ke pemasok
  - Track delivery status
  - Reminder untuk overdue PO
  
- **Terima Barang**
  - Partial receive (terima sebagian)
  - Input batch number, production date, expiry date
  - Auto-create product batch
  - Auto-update stock
  - Generate stock movement
  - Print receiving report

- **Features**
  - Convert quotation to PO
  - Copy/duplicate PO
  - Void/cancel PO dengan alasan
  - PO approval workflow
  - Export PO ke PDF

#### 4.4.2 Pembayaran Pembelian
- **Manajemen Pembayaran**
  - Partial payment support
  - Multi payment method per transaksi
  - Giro/cek management dengan due date
  - Auto-update payment status
  - Send payment reminder ke pemasok
  
- **Laporan**
  - Outstanding payables (aging)
  - Payment history
  - Export Excel/CSV/PDF

#### 4.4.3 Retur Pembelian
- **Buat Retur**
  - Pilih dari purchase yang ada
  - Multi-item return
  - Return reason selection
  - Foto bukti (untuk damaged goods)
  - Approval workflow
  
- **Proses Retur**
  - Credit note generation
  - Auto-update stock (decrease)
  - Auto-update payable
  - Refund tracking
  
- **Laporan**
  - Return summary by pemasok
  - Return summary by reason
  - Export Excel/CSV/PDF

### 4.5 Manajemen Pelanggan

#### 4.5.1 Data Pelanggan
- **CRUD Pelanggan**
  - Profil lengkap dengan foto
  - Kategori pelanggan (retail, reseller, healthcare)
  - Custom discount per pelanggan
  - Credit limit & payment term
  
- **Features**
  - Import pelanggan dari Excel
  - Export customer list
  - Birthday reminder
  - Membership card dengan barcode/QR
  - Print membership card

#### 4.5.2 Histori Pembelian
- **Purchase History**
  - Complete transaction history
  - Preferred products
  - Purchase pattern analysis

### 4.6 Transaksi Penjualan

#### 4.6.1 Point of Sale (POS)
- **Interface POS**
  - Clean, user-friendly kasir interface
  - Product search (nama, kode, barcode)
  - Barcode scanner integration
  - Quick access frequently bought items
  - Shopping cart dengan edit quantity
  - Real-time calculation (subtotal, discount, tax, total)
  
- **Customer Selection**
  - Search customer by name, phone, membership
  - Quick register new customer
  - Apply customer discount otomatis
  
- **Payment Processing**
  - Multi payment method dalam satu transaksi
  - Split payment support
  - Auto-calculate change
  
- **Print & Notification**
  - Auto-print receipt (thermal/A4)
  - Customizable receipt template
  
- **Hold & Resume**
  - Hold transaction untuk dilanjutkan nanti
  - Multiple hold transactions
  - Quick resume dari hold list

#### 4.6.2 Penjualan Non-POS
- **Sales Order**
  - Untuk penjualan wholesale/B2B
  - Quotation/penawaran harga
  - Convert quotation to sales order
  - Partial delivery support
  - Delivery order (DO) generation
  
- **Prescription Sales**
  - Link ke resep dokter
  - Pharmacist verification required
  - Special notes per item
  - Patient counseling checklist

#### 4.6.3 Retur Penjualan
- **Manajemen Retur**
  - Search original transaction
  - Select items to return
  - Return reason
  - Refund processing
  - Auto-update stock & financials
  
- **Laporan**
  - Return analysis by reason
  - Return analysis by product
  - Export Excel/CSV/PDF

### 4.7 Resep Dokter

#### 4.7.1 Data Dokter
- **CRUD Dokter**
  - Profil dokter lengkap
  - SIP validation & expiry tracking
  - Spesialisasi
  - Rumah sakit/klinik afiliasi
  
- **Features**
  - Import dokter dari Excel
  - Export daftar dokter
  - Most prescribing doctors report

#### 4.7.2 Input & Proses Resep
- **Prescription Entry**
  - Scan/upload foto resep
  - OCR untuk extract text (optional)
  - Manual input prescription details
  - Link to patient (pelanggan)
  - Link to doctor
  
- **Pharmacist Verification**
  - Drug interaction checking
  - Dosage verification
  - Allergy checking
  - Alternative drug suggestion (jika stok habis)
  - Patient counseling notes
  
- **Dispensing**
  - Generate label obat dengan instruksi
  - Print label per item
  - Auto-create sales transaction
  - Mark prescription as completed

#### 4.7.3 Laporan Resep
- **Analysis**
  - Most prescribed drugs
  - Prescription by doctor
  - Prescription by diagnosis
  - Revenue from prescriptions
  - Export Excel/CSV/PDF

### 4.8 Manajemen Karyawan

#### 4.8.1 Data Karyawan
- **CRUD Karyawan**
  - Profil lengkap dengan foto
  - Employment details
  - License/certification (STRA/SIPA untuk apoteker)
  - License expiry reminder
  - Emergency contact
  
- **Features**
  - Import karyawan dari Excel
  - Export employee list
  - Organizational chart
  - Employee directory with search

### 4.9 Laporan (Reports)

#### 4.9.1 Laporan Penjualan
- **Laporan Penjualan**
  - Ringkasan penjualan (harian, mingguan, bulanan, tahunan)
  - Penjualan per produk
  - Penjualan per kategori
  - Penjualan per pelanggan
  - Penjualan per metode pembayaran
  - Penjualan per kasir
  - Analisis penjualan per jam
  - Perbandingan penjualan (periode vs periode)
  - Tren pertumbuhan penjualan
  
- **Opsi Export**
  - Export ke Excel (.xlsx)
  - Export ke CSV
  - Export ke PDF (dengan grafik)
  - Auto-email scheduled reports

#### 4.9.2 Laporan Pembelian
- **Laporan Pembelian**
  - Ringkasan pembelian (harian, mingguan, bulanan, tahunan)
  - Pembelian per produk
  - Pembelian per kategori
  - Pembelian per pemasok
  - Analisis harga pembelian
  - Perbandingan pembelian
  
- **Opsi Export**
  - Excel/CSV/PDF

#### 4.9.3 Laporan Persediaan
- **Laporan Stok**
  - Laporan stok saat ini
  - Laporan nilai stok
  - Laporan pergerakan stok
  - Slow moving items
  - Fast moving items
  - Dead stock report
  - Laporan kadaluarsa (30, 60, 90 hari)
  - Laporan penyesuaian stok
  - Laporan variance stock opname
  
- **Opsi Export**
  - Excel/CSV/PDF dengan grafik

#### 4.9.4 Laporan Keuangan
- **Laporan Keuangan**
  - Laporan laba rugi
  - Perbandingan penjualan vs pembelian
  - Pendapatan per kategori
  - Analisis gross profit margin
  - Analisis net profit
  
- **Opsi Export**
  - Excel/CSV/PDF

#### 4.9.5 Laporan Pelanggan
- **Laporan Pelanggan**
  - Daftar pelanggan
  - Histori pembelian pelanggan
  - Top pelanggan berdasarkan revenue
  - Akuisisi pelanggan baru
  - Analisis retensi pelanggan
  
- **Opsi Export**
  - Excel/CSV/PDF

### 4.10 Manajemen Pengguna & Peran

#### 4.10.1 Manajemen Pengguna
- **CRUD Pengguna**
  - Create user account linked to employee
  - Assign peran
  - Set hak akses
  - Active/inactive status
  - Password management
  - Force password reset
  
- **Features**
  - Edit profil pengguna
  - Ganti password
  - Upload avatar
  - Activity tracking
  - Login history
  - Session management

#### 4.10.2 Peran & Hak Akses
- **Manajemen Peran**
  - Predefined peran (Admin, Apoteker)
  - Custom peran
  - Assign hak akses per peran
  
- **Manajemen Hak Akses**
  - Granular hak akses per module
  - Create, Read, Update, Delete hak akses
  - Special hak akses (approve, print, export, dll)
  
- **Grup Hak Akses**
  - Dashboard
  - Data Master (Pemasok, Kategori, Satuan, Produk)
  - Persediaan (Stok, Penyesuaian, Opname)
  - Pembelian (PO, Payment, Return)
  - Pelanggan
  - Penjualan (POS, Order, Return)
  - Resep
  - Karyawan
  - Laporan (Penjualan, Pembelian, Persediaan, Keuangan)
  - Pengguna & Peran

---

## 5. KONVENSI PENGEMBANGAN

### 5.1 Styling dengan Bootstrap & Custom CSS

#### CDN Links yang Digunakan
```
<!-- Bootstrap 5.3.x -->
https://cdn.jsdelivr.net/npm/bootstrap@5.3.x/dist/css/bootstrap.min.css
https://cdn.jsdelivr.net/npm/bootstrap@5.3.x/dist/js/bootstrap.bundle.min.js

<!-- Font Awesome 6.x -->
https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.x.x/css/all.min.css

<!-- SweetAlert2 -->
https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css
https://cdn.jsdelivr.net/npm/sweetalert2@11
```

#### Organisasi Custom CSS
- Semua custom CSS diletakkan di `public/assets/css/`
- File utama: `custom.css` untuk override Bootstrap
- File per modul: `dashboard.css`, `pos.css`, `product.css`, dll
- Menggunakan CSS variables untuk colors & spacing consistency
- Responsive design mengikuti Bootstrap breakpoints

### 5.2 Icon dengan Font Awesome

#### Penggunaan Icon
- **Navigation**: Icon untuk menu sidebar & navbar
- **Buttons**: Icon di semua tombol aksi (tambah, edit, hapus, cetak, dll)
- **Status Indicators**: Icon untuk status (success, warning, error, info)
- **Cards**: Icon di header cards untuk visual enhancement
- **Forms**: Icon di input fields untuk user guidance
- **Tables**: Icon di action columns

#### Icon Consistency
- Tambah: `fa-plus`
- Edit: `fa-edit` atau `fa-pen`
- Hapus: `fa-trash`
- View: `fa-eye`
- Print: `fa-print`
- Download: `fa-download`
- Upload: `fa-upload`
- Search: `fa-search`
- Filter: `fa-filter`
- Export: `fa-file-export`
- dll sesuai kebutuhan

### 5.3 Alert dengan SweetAlert2

#### Jenis Alert
**1. Success Alert**
- Digunakan setelah operasi berhasil (create, update, delete)
- Icon: success
- Timer: auto-close 3 detik
- Position: top-end (toast style)

**2. Error Alert**
- Digunakan untuk error validation atau sistem
- Icon: error
- Tombol: OK untuk close
- ShowCloseButton: true

**3. Warning Alert**
- Digunakan untuk peringatan
- Icon: warning
- Tombol: OK untuk close

**4. Confirmation Alert**
- Digunakan sebelum aksi penting (delete, void, dll)
- Icon: question atau warning
- Tombol: Yes & Cancel
- ShowCancelButton: true
- ConfirmButtonText: sesuai aksi (Hapus, Void, dll)
- CancelButtonText: Batal
- ReverseButtons: true

#### Implementasi Alert
- Success & Error: Otomatis muncul dari response Ajax
- Confirmation: Muncul saat user klik tombol delete/void
- Custom styling mengikuti theme aplikasi

### 5.4 Blade Templating Structure

#### Layout Structure
**app.blade.php**
- Include CDN links (Bootstrap, Font Awesome, SweetAlert2)
- Include custom CSS via stack
- Include navbar partial
- Include sidebar partial
- Main content area dengan `@yield('content')`
- Include footer partial
- Include scripts (jQuery, Bootstrap JS, dll)
- Include custom scripts via stack
- SweetAlert2 global configuration

**auth.blade.php**
- Minimalist layout untuk halaman login/register
- Include CDN & custom CSS
- Content area tanpa navbar/sidebar
- Include scripts

**print.blade.php**
- Clean layout untuk print
- Include minimal CSS
- Content area
- Print-specific styling

#### Page Structure
Setiap page harus mengikuti struktur:
```
@extends('layouts.app')

@push('styles')
    <!-- Custom CSS untuk halaman ini -->
@endpush

@section('content')
    <!-- Breadcrumb -->
    @include('partials.breadcrumb')
    
    <!-- Page content -->
    <div class="container-fluid">
        <!-- Content here -->
    </div>
@endsection

@push('scripts')
    <!-- Custom JavaScript untuk halaman ini -->
@endpush
```

### 5.5 Naming Conventions

#### Database
- Table names: plural, snake_case (pengguna, produk, detail_pembelian)
- Column names: snake_case (created_at, produk_id, harga_satuan)
- Foreign keys: singular_table_name + _id (pengguna_id, produk_id)
- Pivot tables: alphabetical order (hak_akses_peran, bukan peran_hak_akses)

#### Laravel
- Models: Singular, PascalCase (Pengguna, Produk, DetailPembelian)
- Controllers: Plural + Controller (ProdukController, PenjualanController)
- Views: kebab-case (daftar-produk.blade.php, buat-penjualan.blade.php)
- Routes: kebab-case (produk.index, retur-penjualan.create)
- Variables: camelCase ($daftarProduk, $totalHarga)
- Methods: camelCase (buatInvoice(), hitungTotal())

#### Frontend
- CSS classes: kebab-case (product-card, btn-primary-custom)
- JavaScript functions: camelCase (calculateTotal(), showAlert())
- IDs: kebab-case (product-form, sale-table)

### 5.6 Security & Validation

#### Input Validation
- Semua input user harus divalidasi
- Menggunakan Laravel Form Request untuk validasi kompleks
- Client-side validation menggunakan HTML5 & JavaScript
- Server-side validation wajib untuk semua input

#### Authentication & Authorization
- Middleware untuk proteksi route
- Gate & Policy untuk authorization
- Role-based access control
- Permission-based access untuk fitur specific

#### Data Security
- Password hashing (bcrypt)
- CSRF protection
- SQL injection prevention (Eloquent ORM)
- XSS prevention (Blade escaping)
- Encryption untuk data sensitif

### 5.7 Performance Optimization

#### Database
- Proper indexing (foreign keys, frequently queried columns)
- Eager loading untuk relasi (prevent N+1 problem)
- Query optimization
- Database caching

#### Frontend
- Asset minification & compression
- Image optimization
- Lazy loading untuk images & tables
- CDN usage untuk libraries

#### Backend
- Route caching
- Config caching
- View caching
- Queue untuk heavy operations (email, report generation)

### 5.8 Export Functionality

#### Excel Export
- Menggunakan Maatwebsite/Laravel-Excel
- Custom styling (header bold, border, color)
- Multiple sheets untuk complex reports
- Formula support
- Auto-width columns

#### CSV Export
- UTF-8 encoding
- Proper delimiter (comma)
- Header row
- Quote handling

#### PDF Export
- Menggunakan Barryvdh/Laravel-DomPDF
- Custom template per laporan
- Header & footer dengan logo
- Page number
- Landscape/portrait orientation
- Chart/graph inclusion

---

## 6. TEKNOLOGI TAMBAHAN (OPTIONAL)

### 6.1 Barcode & QR Code
- **Library**: picqer/php-barcode-generator, simplesoftwareio/simple-qrcode
- **Usage**: Generate barcode untuk produk, QR untuk customer card, pemasok

### 6.2 Chart & Visualization
- **Library**: Chart.js via CDN
- **Usage**: Dashboard charts, report visualization

### 6.3 Datatable
- **Library**: DataTables via CDN
- **Features**: Server-side processing, export buttons, responsive

### 6.4 Image Handling
- **Library**: Intervention Image
- **Usage**: Upload, crop, resize product images

---

**END OF DOCUMENT**