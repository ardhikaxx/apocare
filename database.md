# Dokumentasi Database Apocare

Dokumen ini menjelaskan struktur tabel database aplikasi Apocare, termasuk tipe data dan relasi antar tabel.

---

## 1. Tabel Autentikasi & Otorisasi

### Tabel `peran` (Roles)

Tabel ini menyimpan data peran (role) pengguna dalam sistem.

| Kolom | Tipe Data | Nullable | Deskripsi |
|-------|-----------|----------|-----------|
| id | BIGINT (UNSIGNED) | NO | Primary key, auto increment |
| nama | VARCHAR(50) | NO | Nama peran (Admin, Apoteker, Kasir, Gudang) |
| keterangan | TEXT | YES | Keterangan/deskripsi peran |
| created_at | TIMESTAMP | YES | Tanggal dibuat |
| updated_at | TIMESTAMP | YES | Tanggal diperbarui |

**Relasi:**
- One-to-Many dengan tabel `pengguna` (via role_id)
- Many-to-Many dengan tabel `hak_akses` (via tabel pivot `peran_hak_akses`)

---

### Tabel `hak_akses` (Permissions)

Tabel ini menyimpan data permission/hak akses dalam sistem.

| Kolom | Tipe Data | Nullable | Deskripsi |
|-------|-----------|----------|-----------|
| id | BIGINT (UNSIGNED) | NO | Primary key, auto increment |
| nama | VARCHAR(100) | NO | Nama hak akses |
| kode | VARCHAR(50) | NO | Kode unik hak akses (contoh: produk.view) |
| modul | VARCHAR(50) | NO | Modul terkait |
| created_at | TIMESTAMP | YES | Tanggal dibuat |
| updated_at | TIMESTAMP | YES | Tanggal diperbarui |

**Relasi:**
- Many-to-Many dengan tabel `peran` (via tabel pivot `peran_hak_akses`)

---

### Tabel `peran_hak_akses` (Role Permission Pivot)

Tabel pivot untuk relasi Many-to-Many antara peran dan hak akses.

| Kolom | Tipe Data | Nullable | Deskripsi |
|-------|-----------|----------|-----------|
| id | BIGINT (UNSIGNED) | NO | Primary key, auto increment |
| role_id | BIGINT (UNSIGNED) | NO | Foreign key ke tabel peran |
| permission_id | BIGINT (UNSIGNED) | NO | Foreign key ke tabel hak_akses |
| created_at | TIMESTAMP | YES | Tanggal dibuat |
| updated_at | TIMESTAMP | YES | Tanggal diperbarui |

**Relasi:**
- Foreign Key: role_id → peran(id)
- Foreign Key: permission_id → hak_akses(id)

---

## 2. Tabel Master Data

### Tabel `pengguna` (Users)

Tabel ini menyimpan data pengguna sistem.

| Kolom | Tipe Data | Nullable | Deskripsi |
|-------|-----------|----------|-----------|
| id | BIGINT (UNSIGNED) | NO | Primary key, auto increment |
| nama | VARCHAR(100) | NO | Nama lengkap pengguna |
| email | VARCHAR(100) | NO | Email (unik) |
| username | VARCHAR(50) | NO | Username (unik) |
| password | VARCHAR(255) | NO | Password terenkripsi |
| remember_token | VARCHAR(100) | YES | Token remember me |
| role_id | BIGINT (UNSIGNED) | NO | Foreign key ke tabel peran |
| telepon | VARCHAR(20) | YES | Nomor telepon |
| alamat | TEXT | YES | Alamat lengkap |
| foto | VARCHAR(255) | YES | Path foto profil |
| status_aktif | BOOLEAN | NO | Status aktif/nonaktif (default: true) |
| login_terakhir | TIMESTAMP | YES | Timestamp login terakhir |
| dibuat_oleh | BIGINT (UNSIGNED) | YES | User yang membuat |
| diubah_oleh | BIGINT (UNSIGNED) | YES | User terakhir yang mengubah |
| deleted_at | TIMESTAMP | YES | Soft delete |
| created_at | TIMESTAMP | YES | Tanggal dibuat |
| updated_at | TIMESTAMP | YES | Tanggal diperbarui |

**Relasi:**
- BelongsTo: peran (via role_id)
- BelongsTo: pengguna (via dibuat_oleh, diubah_oleh) - self reference
- HasMany: karyawan, resep, penjualan, pembelian, penyesuaian_stok, dll.

---

### Tabel `kategori` (Categories)

Tabel ini menyimpan data kategori produk.

| Kolom | Tipe Data | Nullable | Deskripsi |
|-------|-----------|----------|-----------|
| id | BIGINT (UNSIGNED) | NO | Primary key, auto increment |
| kode | VARCHAR(20) | NO | Kode kategori (unik) |
| nama | VARCHAR(100) | NO | Nama kategori |
| parent_id | BIGINT (UNSIGNED) | YES | Foreign key ke kategori induk (self-reference) |
| keterangan | TEXT | YES | Keterangan kategori |
| ikon | VARCHAR(50) | YES | Ikon kategori |
| status_aktif | BOOLEAN | NO | Status aktif/nonaktif |
| dibuat_oleh | BIGINT (UNSIGNED) | YES | Foreign key ke pengguna |
| diubah_oleh | BIGINT (UNSIGNED) | YES | Foreign key ke pengguna |
| deleted_at | TIMESTAMP | YES | Soft delete |
| created_at | TIMESTAMP | YES | Tanggal dibuat |
| updated_at | TIMESTAMP | YES | Tanggal diperbarui |

**Relasi:**
- BelongsTo: kategori (via parent_id) - self reference
- HasMany: kategori (children)
- HasMany: produk
- BelongsTo: pengguna (via dibuat_oleh, diubah_oleh)

---

### Tabel `satuan` (Units)

Tabel ini menyimpan data satuan produk.

| Kolom | Tipe Data | Nullable | Deskripsi |
|-------|-----------|----------|-----------|
| id | BIGINT (UNSIGNED) | NO | Primary key, auto increment |
| kode | VARCHAR(10) | NO | Kode satuan (unik) |
| nama | VARCHAR(50) | NO | Nama satuan (Tablet, Kapsul, ml, dll) |
| keterangan | TEXT | YES | Keterangan |
| status_aktif | BOOLEAN | NO | Status aktif/nonaktif |
| created_at | TIMESTAMP | YES | Tanggal dibuat |
| updated_at | TIMESTAMP | YES | Tanggal diperbarui |

**Relasi:**
- HasMany: produk
- HasMany: satuan_produk

---

### Tabel `produk` (Products)

Tabel ini menyimpan data produk/obat.

| Kolom | Tipe Data | Nullable | Deskripsi |
|-------|-----------|----------|-----------|
| id | BIGINT (UNSIGNED) | NO | Primary key, auto increment |
| kode | VARCHAR(30) | NO | Kode produk (unik) |
| barcode | VARCHAR(50) | YES | Barcode produk (unik) |
| nama | VARCHAR(200) | NO | Nama produk |
| nama_generik | VARCHAR(200) | YES | Nama generik obat |
| kategori_id | BIGINT (UNSIGNED) | NO | Foreign key ke kategori |
| satuan_id | BIGINT (UNSIGNED) | NO | Foreign key ke satuan |
| produsen | VARCHAR(100) | YES | Produsen/pabrik obat |
| keterangan | TEXT | YES | Keterangan produk |
| jenis_produk | ENUM | NO | Jenis: Obat, Alkes, Vitamin, Kosmetik, Umum |
| gol_obat | ENUM | YES | Golongan: Obat Bebas, Obat Bebas Terbatas, Obat Keras, Narkotika, Psikotropika |
| perlu_resep | BOOLEAN | NO | Apakah memerlukan resep |
| harga_beli | DECIMAL(15,2) | NO | Harga beli per satuan |
| harga_jual | DECIMAL(15,2) | NO | Harga jual per satuan |
| stok_minimum | INT | NO | Stok minimum (buffer stock) |
| stok_maksimum | INT | NO | Stok maksimum |
| titik_pesan_ulang | INT | NO | Titik pemesanan ulang |
| lokasi_rak | VARCHAR(20) | YES | Lokasi rak penyimpanan |
| kondisi_penyimpanan | TEXT | YES | Kondisi penyimpanan |
| gambar | VARCHAR(255) | YES | Path gambar produk |
| status_aktif | BOOLEAN | NO | Status aktif/nonaktif |
| konsinyasi | BOOLEAN | NO | Apakah produk konsinyasi |
| persentase_pajak | DECIMAL(5,2) | NO | Persentase pajak |
| catatan | TEXT | YES | Catatan tambahan |
| dibuat_oleh | BIGINT (UNSIGNED) | YES | Foreign key ke pengguna |
| diubah_oleh | BIGINT (UNSIGNED) | YES | Foreign key ke pengguna |
| deleted_at | TIMESTAMP | YES | Soft delete |
| created_at | TIMESTAMP | YES | Tanggal dibuat |
| updated_at | TIMESTAMP | YES | Tanggal diperbarui |

**Relasi:**
- BelongsTo: kategori
- BelongsTo: satuan
- BelongsTo: pengguna (via dibuat_oleh, diubah_oleh)
- HasMany: stok_produk
- HasMany: batch_produk
- HasMany: pergerakan_stok
- HasMany: detail_penjualan
- HasMany: detail_pembelian
- HasMany: detail_resep

---

### Tabel `satuan_produk` (Product Units)

Tabel ini menyimpan data konversi satuan per produk (satu produk bisa memiliki多个satuan).

| Kolom | Tipe Data | Nullable | Deskripsi |
|-------|-----------|----------|-----------|
| id | BIGINT (UNSIGNED) | NO | Primary key, auto increment |
| produk_id | BIGINT (UNSIGNED) | NO | Foreign key ke produk |
| satuan_id | BIGINT (UNSIGNED) | NO | Foreign key ke satuan |
| faktor_konversi | DECIMAL(10,2) | NO | Faktor konversi ke satuan dasar |
| barcode | VARCHAR(50) | YES | Barcode untuk satuan ini |
| harga_beli | DECIMAL(15,2) | NO | Harga beli untuk satuan ini |
| harga_jual | DECIMAL(15,2) | NO | Harga jual untuk satuan ini |
| default_pembelian | BOOLEAN | NO | Apakah default saat pembelian |
| default_penjualan | BOOLEAN | NO | Apakah default saat penjualan |
| created_at | TIMESTAMP | YES | Tanggal dibuat |
| updated_at | TIMESTAMP | YES | Tanggal diperbarui |

**Relasi:**
- BelongsTo: produk
- BelongsTo: satuan

---

### Tabel `pemasok` (Suppliers)

Tabel ini menyimpan data supplier/pemasok.

| Kolom | Tipe Data | Nullable | Deskripsi |
|-------|-----------|----------|-----------|
| id | BIGINT (UNSIGNED) | NO | Primary key, auto increment |
| kode | VARCHAR(20) | NO | Kode pemasok (unik) |
| nama | VARCHAR(100) | NO | Nama pemasok |
| kontak_person | VARCHAR(100) | YES | Nama kontak person |
| telepon | VARCHAR(20) | YES | Nomor telepon |
| email | VARCHAR(100) | YES | Email pemasok |
| alamat | TEXT | YES | Alamat lengkap |
| kota | VARCHAR(50) | YES | Kota |
| provinsi | VARCHAR(50) | YES | Provinsi |
| kode_pos | VARCHAR(10) | YES | Kode pos |
| npwp | VARCHAR(30) | YES | NPWP pemasok |
| termin_pembayaran | INT | NO | Termin pembayaran (hari) |
| limit_kredit | DECIMAL(15,2) | NO | Limit kredit |
| status_aktif | BOOLEAN | NO | Status aktif/nonaktif |
| catatan | TEXT | YES | Catatan tambahan |
| dibuat_oleh | BIGINT (UNSIGNED) | YES | Foreign key ke pengguna |
| diubah_oleh | BIGINT (UNSIGNED) | YES | Foreign key ke pengguna |
| deleted_at | TIMESTAMP | YES | Soft delete |
| created_at | TIMESTAMP | YES | Tanggal dibuat |
| updated_at | TIMESTAMP | YES | Tanggal diperbarui |

**Relasi:**
- HasMany: pembelian
- HasMany: retur_pembelian
- HasMany: batch_produk
- BelongsTo: pengguna (via dibuat_oleh, diubah_oleh)

---

### Tabel `pelanggan` (Customers)

Tabel ini menyimpan data pelanggan.

| Kolom | Tipe Data | Nullable | Deskripsi |
|-------|-----------|----------|-----------|
| id | BIGINT (UNSIGNED) | NO | Primary key, auto increment |
| kode | VARCHAR(20) | NO | Kode pelanggan (unik) |
| nama | VARCHAR(100) | NO | Nama pelanggan |
| jenis_pelanggan | ENUM | NO | Jenis: REGULAR, RESELLER, KESEHATAN, PERUSAHAAN |
| jenis_identitas | ENUM | YES | Jenis identitas: KTP, SIM, PASSPORT |
| nomor_identitas | VARCHAR(50) | YES | Nomor identitas |
| jenis_kelamin | ENUM | YES | Jenis kelamin: PRIA, WANITA |
| tanggal_lahir | DATE | YES | Tanggal lahir |
| telepon | VARCHAR(20) | YES | Nomor telepon |
| email | VARCHAR(100) | YES | Email |
| alamat | TEXT | YES | Alamat lengkap |
| kota | VARCHAR(50) | YES | Kota |
| provinsi | VARCHAR(50) | YES | Provinsi |
| kode_pos | VARCHAR(10) | YES | Kode pos |
| persentase_diskon | DECIMAL(5,2) | NO | Diskon khusus pelanggan |
| limit_kredit | DECIMAL(15,2) | NO | Limit kredit |
| termin_pembayaran | INT | NO | Termin pembayaran (hari) |
| total_pembelian | DECIMAL(15,2) | NO | Total pembelian |
| tanggal_beli_terakhir | DATE | YES | Tanggal terakhir membeli |
| status_aktif | BOOLEAN | NO | Status aktif/nonaktif |
| catatan | TEXT | YES | Catatan tambahan |
| dibuat_oleh | BIGINT (UNSIGNED) | YES | Foreign key ke pengguna |
| diubah_oleh | BIGINT (UNSIGNED) | YES | Foreign key ke pengguna |
| deleted_at | TIMESTAMP | YES | Soft delete |
| created_at | TIMESTAMP | YES | Tanggal dibuat |
| updated_at | TIMESTAMP | YES | Tanggal diperbarui |

**Relasi:**
- HasMany: penjualan
- HasMany: retur_penjualan
- HasMany: resep
- BelongsTo: pengguna (via dibuat_oleh, diubah_oleh)

---

### Tabel `karyawan` (Employees)

Tabel ini menyimpan data karyawan/apoteker.

| Kolom | Tipe Data | Nullable | Deskripsi |
|-------|-----------|----------|-----------|
| id | BIGINT (UNSIGNED) | NO | Primary key, auto increment |
| pengguna_id | BIGINT (UNSIGNED) | NO | Foreign key ke pengguna |
| nomor_karyawan | VARCHAR(20) | NO | Nomor karyawan (unik) |
| nomor_identitas | VARCHAR(50) | YES | Nomor KTP/SIM |
| npwp | VARCHAR(30) | YES | NPWP |
| jabatan | VARCHAR(50) | YES | Jabatan |
| departemen | VARCHAR(50) | YES | Departemen |
| status_kepegawaian | ENUM | YES | Status: TETAP, KONTRAK, MAGANG, FREELANCE |
| tanggal_bergabung | DATE | YES | Tanggal bergabung |
| tanggal_resign | DATE | YES | Tanggal resign |
| pendidikan | VARCHAR(50) | YES | Pendidikan terakhir |
| nomor_lisensi | VARCHAR(50) | YES | Nomor lisensi apoteker |
| kadaluarsa_lisensi | DATE | YES | Kadaluarsa lisensi |
| nama_bank | VARCHAR(100) | YES | Nama bank |
| nomor_rekening | VARCHAR(50) | YES | Nomor rekening |
| kontak_darurat_nama | VARCHAR(100) | YES | Nama kontak darurat |
| kontak_darurat_telepon | VARCHAR(20) | YES | Telepon kontak darurat |
| kontak_darurat_hubungan | VARCHAR(50) | YES | Hubungan kontak darurat |
| status_aktif | BOOLEAN | NO | Status aktif/nonaktif |
| catatan | TEXT | YES | Catatan tambahan |
| dibuat_oleh | BIGINT (UNSIGNED) | YES | Foreign key ke pengguna |
| diubah_oleh | BIGINT (UNSIGNED) | YES | Foreign key ke pengguna |
| deleted_at | TIMESTAMP | YES | Soft delete |
| created_at | TIMESTAMP | YES | Tanggal dibuat |
| updated_at | TIMESTAMP | YES | Tanggal diperbarui |

**Relasi:**
- BelongsTo: pengguna (via pengguna_id)
- BelongsTo: pengguna (via dibuat_oleh, diubah_oleh)

---

### Tabel `dokter` (Doctors)

Tabel ini menyimpan data dokter.

| Kolom | Tipe Data | Nullable | Deskripsi |
|-------|-----------|----------|-----------|
| id | BIGINT (UNSIGNED) | NO | Primary key, auto increment |
| kode | VARCHAR(20) | NO | Kode dokter (unik) |
| nama | VARCHAR(100) | NO | Nama dokter |
| spesialisasi | VARCHAR(100) | YES | Spesialisasi dokter |
| nomor_sip | VARCHAR(50) | YES | Nomor SIP (Surat Izin Praktik) |
| telepon | VARCHAR(20) | YES | Nomor telepon |
| email | VARCHAR(100) | YES | Email |
| rumah_sakit | VARCHAR(100) | YES | Rumah sakit/klinik |
| alamat | TEXT | YES | Alamat |
| status_aktif | BOOLEAN | NO | Status aktif/nonaktif |
| catatan | TEXT | YES | Catatan tambahan |
| dibuat_oleh | BIGINT (UNSIGNED) | YES | Foreign key ke pengguna |
| diubah_oleh | BIGINT (UNSIGNED) | YES | Foreign key ke pengguna |
| deleted_at | TIMESTAMP | YES | Soft delete |
| created_at | TIMESTAMP | YES | Tanggal dibuat |
| updated_at | TIMESTAMP | YES | Tanggal diperbarui |

**Relasi:**
- HasMany: resep
- BelongsTo: pengguna (via dibuat_oleh, diubah_oleh)

---

## 3. Tabel Persediaan (Inventory)

### Tabel `stok_produk` (Product Stock)

Tabel ini menyimpan data stok per produk.

| Kolom | Tipe Data | Nullable | Deskripsi |
|-------|-----------|----------|-----------|
| id | BIGINT (UNSIGNED) | NO | Primary key, auto increment |
| produk_id | BIGINT (UNSIGNED) | NO | Foreign key ke produk |
| jumlah | DECIMAL(10,2) | NO | Jumlah stok total |
| jumlah_reservasi | DECIMAL(10,2) | NO | Jumlah stok reserved |
| jumlah_tersedia | DECIMAL(10,2) | NO | Jumlah stok tersedia (jumlah - reservasi) |
| harga_beli_terakhir | DECIMAL(15,2) | YES | Harga beli terakhir |
| harga_beli_rata | DECIMAL(15,2) | YES | Harga beli rata-rata |
| terakhir_diubah | TIMESTAMP | YES | Timestamp perubahan terakhir |
| created_at | TIMESTAMP | YES | Tanggal dibuat |
| updated_at | TIMESTAMP | YES | Tanggal diperbarui |

**Relasi:**
- BelongsTo: produk

---

### Tabel `batch_produk` (Product Batch)

Tabel ini menyimpan data batch produk (untuk tracking kadaluarsa).

| Kolom | Tipe Data | Nullable | Deskripsi |
|-------|-----------|----------|-----------|
| id | BIGINT (UNSIGNED) | NO | Primary key, auto increment |
| produk_id | BIGINT (UNSIGNED) | NO | Foreign key ke produk |
| nomor_batch | VARCHAR(50) | NO | Nomor batch |
| tanggal_produksi | DATE | YES | Tanggal produksi |
| tanggal_kadaluarsa | DATE | YES | Tanggal kadaluarsa |
| jumlah | DECIMAL(10,2) | NO | Jumlah stok dalam batch |
| harga_beli | DECIMAL(15,2) | YES | Harga beli batch |
| pemasok_id | BIGINT (UNSIGNED) | YES | Foreign key ke pemasok |
| pembelian_id | BIGINT (UNSIGNED) | YES | Foreign key ke pembelian |
| sudah_kadaluarsa | BOOLEAN | NO | Status kadaluarsa |
| catatan | TEXT | YES | Catatan |
| created_at | TIMESTAMP | YES | Tanggal dibuat |
| updated_at | TIMESTAMP | YES | Tanggal diperbarui |

**Relasi:**
- BelongsTo: produk
- BelongsTo: pemasok
- BelongsTo: pembelian (via pembelian_id)
- HasMany: pergerakan_stok
- HasMany: detail_penjualan
- HasMany: detail_penyesuaian_stok
- HasMany: detail_stok_opname

---

### Tabel `pergerakan_stok` (Stock Movements)

Tabel ini menyimpan histori pergerakan stok.

| Kolom | Tipe Data | Nullable | Deskripsi |
|-------|-----------|----------|-----------|
| id | BIGINT (UNSIGNED) | NO | Primary key, auto increment |
| produk_id | BIGINT (UNSIGNED) | NO | Foreign key ke produk |
| batch_id | BIGINT (UNSIGNED) | YES | Foreign key ke batch_produk |
| jenis_pergerakan | ENUM | NO | Jenis: MASUK, KELUAR, PENYESUAIAN, RETUR, KADALUARSA, RUSAK |
| tipe_referensi | VARCHAR(50) | YES | Tipe referensi (Penjualan, Pembelian, dll) |
| id_referensi | BIGINT | YES | ID referensi |
| jumlah | DECIMAL(10,2) | NO | Jumlah pergerakan |
| jumlah_sebelum | DECIMAL(10,2) | NO | Jumlah sebelum pergerakan |
| jumlah_sesudah | DECIMAL(10,2) | NO | Jumlah setelah pergerakan |
| harga_satuan | DECIMAL(15,2) | YES | Harga satuan |
| catatan | TEXT | YES | Catatan |
| dibuat_oleh | BIGINT (UNSIGNED) | YES | Foreign key ke pengguna |
| created_at | TIMESTAMP | YES | Tanggal dibuat |
| updated_at | TIMESTAMP | YES | Tanggal diperbarui |

**Relasi:**
- BelongsTo: produk
- BelongsTo: batch_produk
- BelongsTo: pengguna (via dibuat_oleh)

---

### Tabel `penyesuaian_stok` (Stock Adjustments)

Tabel ini menyimpan data penyesuaian stok.

| Kolom | Tipe Data | Nullable | Deskripsi |
|-------|-----------|----------|-----------|
| id | BIGINT (UNSIGNED) | NO | Primary key, auto increment |
| nomor_penyesuaian | VARCHAR(30) | NO | Nomor penyesuaian (unik) |
| tanggal_penyesuaian | DATE | NO | Tanggal penyesuaian |
| jenis_penyesuaian | ENUM | NO | Jenis: PENAMBAHAN, PENGURANGAN, RUSAK, KADALUARSA, KOREKSI |
| status | ENUM | NO | Status: DRAFT, DISETUJUI, DITOLAK |
| total_item | INT | NO | Total item disesuaikan |
| catatan | TEXT | YES | Catatan |
| disetujui_oleh | BIGINT (UNSIGNED) | YES | User yang menyetujui |
| waktu_persetujuan | TIMESTAMP | YES | Timestamp persetujuan |
| dibuat_oleh | BIGINT (UNSIGNED) | YES | User yang membuat |
| diubah_oleh | BIGINT (UNSIGNED) | YES | User terakhir mengubah |
| deleted_at | TIMESTAMP | YES | Soft delete |
| created_at | TIMESTAMP | YES | Tanggal dibuat |
| updated_at | TIMESTAMP | YES | Tanggal diperbarui |

**Relasi:**
- HasMany: detail_penyesuaian_stok
- BelongsTo: pengguna (via disetujui_oleh, dibuat_oleh, diubah_oleh)

---

### Tabel `detail_penyesuaian_stok` (Stock Adjustment Details)

Tabel detail untuk penyesuaian stok.

| Kolom | Tipe Data | Nullable | Deskripsi |
|-------|-----------|----------|-----------|
| id | BIGINT (UNSIGNED) | NO | Primary key, auto increment |
| penyesuaian_id | BIGINT (UNSIGNED) | NO | Foreign key ke penyesuaian_stok |
| produk_id | BIGINT (UNSIGNED) | NO | Foreign key ke produk |
| batch_id | BIGINT (UNSIGNED) | YES | Foreign key ke batch_produk |
| jumlah_sistem | DECIMAL(10,2) | NO | Jumlah di sistem |
| jumlah_aktual | DECIMAL(10,2) | NO | Jumlah aktual (fisik) |
| selisih | DECIMAL(10,2) | NO | Selisih (aktual - sistem) |
| harga_satuan | DECIMAL(15,2) | YES | Harga satuan |
| total_nilai | DECIMAL(15,2) | NO | Total nilai selisih |
| catatan | TEXT | YES | Catatan |
| created_at | TIMESTAMP | YES | Tanggal dibuat |
| updated_at | TIMESTAMP | YES | Tanggal diperbarui |

**Relasi:**
- BelongsTo: penyesuaian_stok
- BelongsTo: produk
- BelongsTo: batch_produk

---

### Tabel `stok_opname` (Stock Opname)

Tabel ini menyimpan data stok opname.

| Kolom | Tipe Data | Nullable | Deskripsi |
|-------|-----------|----------|-----------|
| id | BIGINT (UNSIGNED) | NO | Primary key, auto increment |
| nomor_opname | VARCHAR(30) | NO | Nomor opname (unik) |
| tanggal_opname | DATE | NO | Tanggal opname |
| status | ENUM | NO | Status: DRAFT, PROSES, SELESAI, DISETUJUI |
| kategori_id | BIGINT (UNSIGNED) | YES | Foreign key ke kategori (filter) |
| total_item_dihitung | INT | NO | Total item yang dihitung |
| total_item_cocok | INT | NO | Total item cocok |
| total_item_selisih | INT | NO | Total item ada selisih |
| total_nilai_selisih | DECIMAL(15,2) | NO | Total nilai selisih |
| catatan | TEXT | YES | Catatan |
| disetujui_oleh | BIGINT (UNSIGNED) | YES | User yang menyetujui |
| waktu_persetujuan | TIMESTAMP | YES | Timestamp persetujuan |
| dibuat_oleh | BIGINT (UNSIGNED) | YES | User yang membuat |
| diubah_oleh | BIGINT (UNSIGNED) | YES | User terakhir mengubah |
| deleted_at | TIMESTAMP | YES | Soft delete |
| created_at | TIMESTAMP | YES | Tanggal dibuat |
| updated_at | TIMESTAMP | YES | Tanggal diperbarui |

**Relasi:**
- HasMany: detail_stok_opname
- BelongsTo: kategori
- BelongsTo: pengguna (via disetujui_oleh, dibuat_oleh, diubah_oleh)

---

### Tabel `detail_stok_opname` (Stock Opname Details)

Tabel detail untuk stok opname.

| Kolom | Tipe Data | Nullable | Deskripsi |
|-------|-----------|----------|-----------|
| id | BIGINT (UNSIGNED) | NO | Primary key, auto increment |
| opname_id | BIGINT (UNSIGNED) | NO | Foreign key ke stok_opname |
| produk_id | BIGINT (UNSIGNED) | NO | Foreign key ke produk |
| batch_id | BIGINT (UNSIGNED) | YES | Foreign key ke batch_produk |
| jumlah_sistem | DECIMAL(10,2) | NO | Jumlah di sistem |
| jumlah_hitung | DECIMAL(10,2) | NO | Jumlah hasil hitung |
| selisih | DECIMAL(10,2) | NO | Selisih |
| harga_satuan | DECIMAL(15,2) | YES | Harga satuan |
| total_nilai_selisih | DECIMAL(15,2) | NO | Total nilai selisih |
| status | ENUM | NO | Status: COCOK, LEBIH, KURANG |
| hitung_oleh | BIGINT (UNSIGNED) | YES | User yang menghitung |
| waktu_hitung | TIMESTAMP | YES | Timestamp penghitungan |
| catatan | TEXT | YES | Catatan |
| created_at | TIMESTAMP | YES | Tanggal dibuat |
| updated_at | TIMESTAMP | YES | Tanggal diperbarui |

**Relasi:**
- BelongsTo: stok_opname
- BelongsTo: produk
- BelongsTo: batch_produk
- BelongsTo: pengguna (via hitung_oleh)

---

## 4. Tabel Transaksi

### Tabel `pembelian` (Purchases)

Tabel ini menyimpan data purchase order/pembelian.

| Kolom | Tipe Data | Nullable | Deskripsi |
|-------|-----------|----------|-----------|
| id | BIGINT (UNSIGNED) | NO | Primary key, auto increment |
| nomor_pembelian | VARCHAR(30) | NO | Nomor purchase order (unik) |
| nomor_po | VARCHAR(30) | YES | Nomor PO (Purchase Order) |
| pemasok_id | BIGINT (UNSIGNED) | NO | Foreign key ke pemasok |
| tanggal_pembelian | DATE | NO | Tanggal pembelian |
| tanggal_jatuh_tempo | DATE | YES | Tanggal jatuh tempo pembayaran |
| status | ENUM | NO | Status: DRAFT, DIPESAN, SEBAGIAN, DITERIMA, SELESAI, BATAL |
| status_pembayaran | ENUM | NO | Status: BELUM_BAYAR, SEBAGIAN, LUNAS |
| metode_pembayaran | ENUM | YES | Metode: TUNAI, TRANSFER, KREDIT, GIRO |
| subtotal | DECIMAL(15,2) | NO | Subtotal |
| jenis_diskon | ENUM | YES | Jenis diskon: PERSENTASE, NOMINAL |
| nilai_diskon | DECIMAL(15,2) | NO | Nilai diskon |
| jumlah_diskon | DECIMAL(15,2) | NO | Jumlah diskon yang dihitung |
| jumlah_pajak | DECIMAL(15,2) | NO | Jumlah pajak |
| biaya_kirim | DECIMAL(15,2) | NO | Biaya pengiriman |
| biaya_lain | DECIMAL(15,2) | NO | Biaya lain-lain |
| total_akhir | DECIMAL(15,2) | NO | Total akhir |
| jumlah_bayar | DECIMAL(15,2) | NO | Jumlah yang sudah dibayar |
| sisa_bayar | DECIMAL(15,2) | NO | Sisa yang harus dibayar |
| nomor_faktur | VARCHAR(50) | YES | Nomor faktur dari supplier |
| tanggal_faktur | DATE | YES | Tanggal faktur |
| nomor_faktur_pajak | VARCHAR(50) | YES | Nomor faktur pajak |
| catatan | TEXT | YES | Catatan |
| disetujui_oleh | BIGINT (UNSIGNED) | YES | User yang menyetujui |
| waktu_persetujuan | TIMESTAMP | YES | Timestamp persetujuan |
| diterima_oleh | BIGINT (UNSIGNED) | YES | User yang menerima |
| waktu_penerimaan | TIMESTAMP | YES | Timestamp penerimaan |
| dibuat_oleh | BIGINT (UNSIGNED) | YES | User yang membuat |
| diubah_oleh | BIGINT (UNSIGNED) | YES | User terakhir mengubah |
| deleted_at | TIMESTAMP | YES | Soft delete |
| created_at | TIMESTAMP | YES | Tanggal dibuat |
| updated_at | TIMESTAMP | YES | Tanggal diperbarui |

**Relasi:**
- BelongsTo: pemasok
- HasMany: detail_pembelian
- HasMany: pembayaran_pembelian
- HasMany: retur_pembelian
- BelongsTo: pengguna (via disetujui_oleh, diterima_oleh, dibuat_oleh, diubah_oleh)

---

### Tabel `detail_pembelian` (Purchase Details)

Tabel detail untuk pembelian.

| Kolom | Tipe Data | Nullable | Deskripsi |
|-------|-----------|----------|-----------|
| id | BIGINT (UNSIGNED) | NO | Primary key, auto increment |
| pembelian_id | BIGINT (UNSIGNED) | NO | Foreign key ke pembelian |
| produk_id | BIGINT (UNSIGNED) | NO | Foreign key ke produk |
| satuan_produk_id | BIGINT (UNSIGNED) | YES | Foreign key ke satuan_produk |
| nomor_batch | VARCHAR(50) | YES | Nomor batch |
| tanggal_produksi | DATE | YES | Tanggal produksi |
| tanggal_kadaluarsa | DATE | YES | Tanggal kadaluarsa |
| jumlah_pesan | DECIMAL(10,2) | NO | Jumlah yang dipesan |
| jumlah_terima | DECIMAL(10,2) | NO | Jumlah yang diterima |
| harga_satuan | DECIMAL(15,2) | NO | Harga satuan |
| persentase_diskon | DECIMAL(5,2) | NO | Persentase diskon |
| jumlah_diskon | DECIMAL(15,2) | NO | Jumlah diskon |
| persentase_pajak | DECIMAL(5,2) | NO | Persentase pajak |
| jumlah_pajak | DECIMAL(15,2) | NO | Jumlah pajak |
| subtotal | DECIMAL(15,2) | NO | Subtotal |
| total | DECIMAL(15,2) | NO | Total |
| catatan | TEXT | YES | Catatan |
| created_at | TIMESTAMP | YES | Tanggal dibuat |
| updated_at | TIMESTAMP | YES | Tanggal diperbarui |

**Relasi:**
- BelongsTo: pembelian
- BelongsTo: produk
- BelongsTo: satuan_produk
- HasMany: detail_retur_pembelian

---

### Tabel `pembayaran_pembelian` (Purchase Payments)

Tabel ini menyimpan data pembayaran untuk pembelian.

| Kolom | Tipe Data | Nullable | Deskripsi |
|-------|-----------|----------|-----------|
| id | BIGINT (UNSIGNED) | NO | Primary key, auto increment |
| pembelian_id | BIGINT (UNSIGNED) | NO | Foreign key ke pembelian |
| nomor_pembayaran | VARCHAR(30) | NO | Nomor pembayaran (unik) |
| tanggal_bayar | DATE | NO | Tanggal pembayaran |
| metode_pembayaran | ENUM | NO | Metode: TUNAI, TRANSFER, KREDIT, GIRO |
| jumlah | DECIMAL(15,2) | NO | Jumlah pembayaran |
| nama_bank | VARCHAR(100) | YES | Nama bank |
| nomor_rekening | VARCHAR(50) | YES | Nomor rekening |
| nomor_giro | VARCHAR(50) | YES | Nomor giro |
| tanggal_giro | DATE | YES | Tanggal giro |
| nomor_referensi | VARCHAR(50) | YES | Nomor referensi |
| catatan | TEXT | YES | Catatan |
| dibuat_oleh | BIGINT (UNSIGNED) | YES | User yang membuat |
| created_at | TIMESTAMP | YES | Tanggal dibuat |
| updated_at | TIMESTAMP | YES | Tanggal diperbarui |

**Relasi:**
- BelongsTo: pembelian
- BelongsTo: pengguna (via dibuat_oleh)

---

### Tabel `penjualan` (Sales)

Tabel ini menyimpan data transaksi penjualan.

| Kolom | Tipe Data | Nullable | Deskripsi |
|-------|-----------|----------|-----------|
| id | BIGINT (UNSIGNED) | NO | Primary key, auto increment |
| nomor_penjualan | VARCHAR(30) | NO | Nomor penjualan (unik) |
| pelanggan_id | BIGINT (UNSIGNED) | YES | Foreign key ke pelanggan |
| resep_id | BIGINT (UNSIGNED) | YES | Foreign key ke resep |
| tanggal_penjualan | DATETIME | NO | Tanggal/waktu penjualan |
| jenis_penjualan | ENUM | NO | Jenis: RETAIL, GROSIR, RESEP, ONLINE |
| status_pembayaran | ENUM | NO | Status: BELUM_BAYAR, SEBAGIAN, LUNAS |
| metode_pembayaran | ENUM | YES | Metode: TUNAI, DEBIT, KREDIT, TRANSFER, EWALLET, QRIS |
| subtotal | DECIMAL(15,2) | NO | Subtotal |
| jenis_diskon | ENUM | YES | Jenis diskon: PERSENTASE, NOMINAL |
| nilai_diskon | DECIMAL(15,2) | NO | Nilai diskon |
| jumlah_diskon | DECIMAL(15,2) | NO | Jumlah diskon |
| jumlah_pajak | DECIMAL(15,2) | NO | Jumlah pajak |
| total_akhir | DECIMAL(15,2) | NO | Total akhir |
| jumlah_bayar | DECIMAL(15,2) | NO | Jumlah yang dibayar |
| jumlah_kembalian | DECIMAL(15,2) | NO | Jumlah kembalian |
| nomor_kartu | VARCHAR(50) | YES | Nomor kartu (jika pakai kartu) |
| nama_pemegang_kartu | VARCHAR(100) | YES | Nama pemilik kartu |
| kode_approval | VARCHAR(50) | YES | Kode approval |
| catatan | TEXT | YES | Catatan |
| dilayani_oleh | BIGINT (UNSIGNED) | YES | User yang melayani |
| dibuat_oleh | BIGINT (UNSIGNED) | YES | User yang membuat |
| diubah_oleh | BIGINT (UNSIGNED) | YES | User terakhir mengubah |
| deleted_at | TIMESTAMP | YES | Soft delete |
| created_at | TIMESTAMP | YES | Tanggal dibuat |
| updated_at | TIMESTAMP | YES | Tanggal diperbarui |

**Relasi:**
- BelongsTo: pelanggan
- BelongsTo: resep
- HasMany: detail_penjualan
- HasMany: retur_penjualan
- BelongsTo: pengguna (via dilayani_oleh, dibuat_oleh, diubah_oleh)

---

### Tabel `detail_penjualan` (Sales Details)

Tabel detail untuk penjualan.

| Kolom | Tipe Data | Nullable | Deskripsi |
|-------|-----------|----------|-----------|
| id | BIGINT (UNSIGNED) | NO | Primary key, auto increment |
| penjualan_id | BIGINT (UNSIGNED) | NO | Foreign key ke penjualan |
| produk_id | BIGINT (UNSIGNED) | NO | Foreign key ke produk |
| satuan_produk_id | BIGINT (UNSIGNED) | YES | Foreign key ke satuan_produk |
| batch_id | BIGINT (UNSIGNED) | YES | Foreign key ke batch_produk |
| jumlah | DECIMAL(10,2) | NO | Jumlah terjual |
| harga_satuan | DECIMAL(15,2) | NO | Harga satuan |
| persentase_diskon | DECIMAL(5,2) | NO | Persentase diskon |
| jumlah_diskon | DECIMAL(15,2) | NO | Jumlah diskon |
| persentase_pajak | DECIMAL(5,2) | NO | Persentase pajak |
| jumlah_pajak | DECIMAL(15,2) | NO | Jumlah pajak |
| subtotal | DECIMAL(15,2) | NO | Subtotal |
| total | DECIMAL(15,2) | NO | Total |
| catatan | TEXT | YES | Catatan |
| created_at | TIMESTAMP | YES | Tanggal dibuat |
| updated_at | TIMESTAMP | YES | Tanggal diperbarui |

**Relasi:**
- BelongsTo: penjualan
- BelongsTo: produk
- BelongsTo: satuan_produk
- BelongsTo: batch_produk
- HasMany: detail_retur_penjualan

---

## 5. Tabel Retur

### Tabel `retur_pembelian` (Purchase Returns)

Tabel ini menyimpan data retur pembelian ke supplier.

| Kolom | Tipe Data | Nullable | Deskripsi |
|-------|-----------|----------|-----------|
| id | BIGINT (UNSIGNED) | NO | Primary key, auto increment |
| nomor_retur | VARCHAR(30) | NO | Nomor retur (unik) |
| pembelian_id | BIGINT (UNSIGNED) | NO | Foreign key ke pembelian |
| pemasok_id | BIGINT (UNSIGNED) | NO | Foreign key ke pemasok |
| tanggal_retur | DATE | NO | Tanggal retur |
| alasan | TEXT | YES | Alasan retur |
| status | ENUM | NO | Status: PENDING, DISETUJUI, DITOLAK, SELESAI |
| subtotal | DECIMAL(15,2) | NO | Subtotal |
| jumlah_pajak | DECIMAL(15,2) | NO | Jumlah pajak |
| total | DECIMAL(15,2) | NO | Total retur |
| metode_refund | ENUM | YES | Metode refund: TUNAI, TRANSFER, NOTA_KREDIT |
| jumlah_refund | DECIMAL(15,2) | NO | Jumlah refund |
| catatan | TEXT | YES | Catatan |
| dibuat_oleh | BIGINT (UNSIGNED) | YES | User yang membuat |
| diubah_oleh | BIGINT (UNSIGNED) | YES | User terakhir mengubah |
| deleted_at | TIMESTAMP | YES | Soft delete |
| created_at | TIMESTAMP | YES | Tanggal dibuat |
| updated_at | TIMESTAMP | YES | Tanggal diperbarui |

**Relasi:**
- BelongsTo: pembelian
- BelongsTo: pemasok
- HasMany: detail_retur_pembelian
- BelongsTo: pengguna (via dibuat_oleh, diubah_oleh)

---

### Tabel `detail_retur_pembelian` (Purchase Return Details)

Tabel detail untuk retur pembelian.

| Kolom | Tipe Data | Nullable | Deskripsi |
|-------|-----------|----------|-----------|
| id | BIGINT (UNSIGNED) | NO | Primary key, auto increment |
| retur_id | BIGINT (UNSIGNED) | NO | Foreign key ke retur_pembelian |
| detail_pembelian_id | BIGINT (UNSIGNED) | YES | Foreign key ke detail_pembelian |
| produk_id | BIGINT (UNSIGNED) | NO | Foreign key ke produk |
| batch_id | BIGINT (UNSIGNED) | YES | Foreign key ke batch_produk |
| jumlah | DECIMAL(10,2) | NO | Jumlah yang diretur |
| harga_satuan | DECIMAL(15,2) | NO | Harga satuan |
| persentase_pajak | DECIMAL(5,2) | NO | Persentase pajak |
| jumlah_pajak | DECIMAL(15,2) | NO | Jumlah pajak |
| subtotal | DECIMAL(15,2) | NO | Subtotal |
| total | DECIMAL(15,2) | NO | Total |
| alasan | TEXT | YES | Alasan retur item |
| created_at | TIMESTAMP | YES | Tanggal dibuat |
| updated_at | TIMESTAMP | YES | Tanggal diperbarui |

**Relasi:**
- BelongsTo: retur_pembelian
- BelongsTo: detail_pembelian
- BelongsTo: produk
- BelongsTo: batch_produk

---

### Tabel `retur_penjualan` (Sales Returns)

Tabel ini menyimpan data retur penjualan dari pelanggan.

| Kolom | Tipe Data | Nullable | Deskripsi |
|-------|-----------|----------|-----------|
| id | BIGINT (UNSIGNED) | NO | Primary key, auto increment |
| nomor_retur | VARCHAR(30) | NO | Nomor retur (unik) |
| penjualan_id | BIGINT (UNSIGNED) | NO | Foreign key ke penjualan |
| pelanggan_id | BIGINT (UNSIGNED) | YES | Foreign key ke pelanggan |
| tanggal_retur | DATETIME | NO | Tanggal retur |
| alasan | TEXT | YES | Alasan retur |
| status | ENUM | NO | Status: PENDING, DISETUJUI, DITOLAK, SELESAI |
| subtotal | DECIMAL(15,2) | NO | Subtotal |
| jumlah_pajak | DECIMAL(15,2) | NO | Jumlah pajak |
| total | DECIMAL(15,2) | NO | Total retur |
| metode_refund | ENUM | YES | Metode refund: TUNAI, TRANSFER, NOTA_KREDIT |
| jumlah_refund | DECIMAL(15,2) | NO | Jumlah refund |
| catatan | TEXT | YES | Catatan |
| disetujui_oleh | BIGINT (UNSIGNED) | YES | User yang menyetujui |
| waktu_persetujuan | TIMESTAMP | YES | Timestamp persetujuan |
| dibuat_oleh | BIGINT (UNSIGNED) | YES | User yang membuat |
| diubah_oleh | BIGINT (UNSIGNED) | YES | User terakhir mengubah |
| deleted_at | TIMESTAMP | YES | Soft delete |
| created_at | TIMESTAMP | YES | Tanggal dibuat |
| updated_at | TIMESTAMP | YES | Tanggal diperbarui |

**Relasi:**
- BelongsTo: penjualan
- BelongsTo: pelanggan
- HasMany: detail_retur_penjualan
- BelongsTo: pengguna (via disetujui_oleh, dibuat_oleh, diubah_oleh)

---

### Tabel `detail_retur_penjualan` (Sales Return Details)

Tabel detail untuk retur penjualan.

| Kolom | Tipe Data | Nullable | Deskripsi |
|-------|-----------|----------|-----------|
| id | BIGINT (UNSIGNED) | NO | Primary key, auto increment |
| retur_id | BIGINT (UNSIGNED) | NO | Foreign key ke retur_penjualan |
| detail_penjualan_id | BIGINT (UNSIGNED) | YES | Foreign key ke detail_penjualan |
| produk_id | BIGINT (UNSIGNED) | NO | Foreign key ke produk |
| batch_id | BIGINT (UNSIGNED) | YES | Foreign key ke batch_produk |
| jumlah | DECIMAL(10,2) | NO | Jumlah yang diretur |
| harga_satuan | DECIMAL(15,2) | NO | Harga satuan |
| persentase_pajak | DECIMAL(5,2) | NO | Persentase pajak |
| jumlah_pajak | DECIMAL(15,2) | NO | Jumlah pajak |
| subtotal | DECIMAL(15,2) | NO | Subtotal |
| total | DECIMAL(15,2) | NO | Total |
| alasan | TEXT | YES | Alasan retur item |
| created_at | TIMESTAMP | YES | Tanggal dibuat |
| updated_at | TIMESTAMP | YES | Tanggal diperbarui |

**Relasi:**
- BelongsTo: retur_penjualan
- BelongsTo: detail_penjualan
- BelongsTo: produk
- BelongsTo: batch_produk

---

## 6. Tabel Resep

### Tabel `resep` (Prescriptions)

Tabel ini menyimpan data resep dokter.

| Kolom | Tipe Data | Nullable | Deskripsi |
|-------|-----------|----------|-----------|
| id | BIGINT (UNSIGNED) | NO | Primary key, auto increment |
| nomor_resep | VARCHAR(30) | NO | Nomor resep (unik) |
| tanggal_resep | DATE | NO | Tanggal resep |
| pelanggan_id | BIGINT (UNSIGNED) | YES | Foreign key ke pelanggan |
| dokter_id | BIGINT (UNSIGNED) | YES | Foreign key ke dokter |
| diagnosa | TEXT | YES | Diagnosa dokter |
| status | ENUM | NO | Status: PENDING, SEBAGIAN, SELESAI, BATAL |
| total_item | INT | NO | Total item obat |
| total_harga | DECIMAL(15,2) | NO | Total harga |
| catatan | TEXT | YES | Catatan |
| apoteker_id | BIGINT (UNSIGNED) | YES | Apoteker yang memverifikasi |
| waktu_verifikasi | TIMESTAMP | YES | Timestamp verifikasi |
| dibuat_oleh | BIGINT (UNSIGNED) | YES | User yang membuat |
| diubah_oleh | BIGINT (UNSIGNED) | YES | User terakhir mengubah |
| deleted_at | TIMESTAMP | YES | Soft delete |
| created_at | TIMESTAMP | YES | Tanggal dibuat |
| updated_at | TIMESTAMP | YES | Tanggal diperbarui |

**Relasi:**
- BelongsTo: pelanggan
- BelongsTo: dokter
- HasMany: detail_resep
- HasMany: penjualan (untuk penjualan resep)
- BelongsTo: pengguna (via apoteker_id, dibuat_oleh, diubah_oleh)

---

### Tabel `detail_resep` (Prescription Details)

Tabel detail untuk resep dokter.

| Kolom | Tipe Data | Nullable | Deskripsi |
|-------|-----------|----------|-----------|
| id | BIGINT (UNSIGNED) | NO | Primary key, auto increment |
| resep_id | BIGINT (UNSIGNED) | NO | Foreign key ke resep |
| produk_id | BIGINT (UNSIGNED) | NO | Foreign key ke produk |
| dosis | VARCHAR(100) | YES | Dosis obat |
| frekuensi | VARCHAR(100) | YES | Frekuensi minum obat |
| durasi | VARCHAR(100) | YES | Durasi pengobatan |
| cara_pakai | VARCHAR(150) | YES | Cara pakai obat |
| jumlah_resep | DECIMAL(10,2) | NO | Jumlah yang diresepkan |
| jumlah_diberikan | DECIMAL(10,2) | NO | Jumlah yang diberikan |
| harga_satuan | DECIMAL(15,2) | NO | Harga satuan |
| total | DECIMAL(15,2) | NO | Total harga |
| instruksi | TEXT | YES | Instruksi khusus |
| catatan | TEXT | YES | Catatan |
| created_at | TIMESTAMP | YES | Tanggal dibuat |
| updated_at | TIMESTAMP | YES | Tanggal diperbarui |

**Relasi:**
- BelongsTo: resep
- BelongsTo: produk

---

## Diagram Relasi

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                                    AUTENTIKASI                                      │
├─────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                      │
│   ┌──────────┐       ┌─────────────────┐       ┌─────────────┐                     │
│   │   peran  │──────▶│ peran_hak_akses │◀──────│ hak_akses   │                     │
│   └────┬─────┘       └─────────────────┘       └─────────────┘                     │
│        │                                                                     │
│        │ 1:N                                                               │
│        ▼                                                                     │
│   ┌──────────┐                                                             │
│   │ pengguna │                                                             │
│   └────┬─────┘                                                             │
│        │                                                                     │
├────────┼────────────────────────────────────────────────────────────────────────────┤
│        │                              MASTER DATA                                 │
│        ▼                                                                     │
│   ┌──────────────────────────────────────────────────────────────────────────┐   │
│   │                                                                          │   │
│   │  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐  │   │
│   │  │ kategori │  │  satuan  │  │  produk  │  │ pemasok  │  │ pelanggan│  │   │
│   │  └────┬─────┘  └────┬─────┘  └────┬─────┘  └────┬─────┘  └────┬─────┘  │   │
│   │       │             │             │             │             │        │   │
│   │       │             │             │             │             │        │   │
│   │       │             │             │             │             │        │   │
│   │       │             │      ┌──────▼──────┐      │             │        │   │
│   │       │             │      │satuan_produk│      │             │        │   │
│   │       │             │      └──────┬──────┘      │             │        │   │
│   │       │             │             │             │             │        │   │
│   │       └─────────────┴─────────────┴─────────────┘─────────────┘        │   │
│   │                                                                          │   │
│   │  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐                 │   │
│   │  │  dokter  │  │ karyawan  │  │   resep  │  │ pelanggan│                 │   │
│   │  └──────────┘  └──────────┘  └────┬─────┘  └──────────┘                 │   │
│   │                                  │                                       │   │
│   └──────────────────────────────────┼───────────────────────────────────────┘   │
│                                      │                                           │
├──────────────────────────────────────┼────────────────────────────────────────────┤
│                                      │         PERSEDIAAN                        │
│                                      ▼                                           │
│   ┌──────────────────────────────────────────────────────────────────────────┐   │
│   │                                                                          │   │
│   │  ┌──────────────┐   ┌──────────────┐   ┌──────────────────┐              │   │
│   │  │  stok_produk │◀──│ batch_produk │──▶│ pergerakan_stok  │              │   │
│   │  └──────┬───────┘   └──────┬───────┘   └──────────────────┘              │   │
│   │         │                  │                                              │   │
│   │         │                  │                                              │   │
│   │  ┌──────▼──────────────────▼──────┐                                      │   │
│   │  │                               │                                      │   │
│   │  │  ┌─────────────────────┐      │                                      │   │
│   │  │  │ penyesuaian_stok    │◀─────┤                                      │   │
│   │  │  └──────────┬──────────┘      │                                      │   │
│   │  │             │                 │                                      │   │
│   │  │  ┌──────────▼──────────┐      │                                      │   │
│   │  │  │detail_penyesuaian  │      │                                      │   │
│   │  │  └─────────────────────┘      │                                      │   │
│   │  │                               │                                      │   │
│   │  │  ┌─────────────────────┐      │                                      │   │
│   │  │  │    stok_opname     │◀─────┤                                      │   │
│   │  │  └──────────┬──────────┘      │                                      │   │
│   │  │             │                 │                                      │   │
│   │  │  ┌──────────▼──────────┐      │                                      │   │
│   │  │  │  detail_stok_opname │      │                                      │   │
│   │  │  └─────────────────────┘      │                                      │   │
│   │  │                               │                                      │   │
│   │  └───────────────────────────────┘                                      │   │
│   │                                                                          │   │
│   └──────────────────────────────────────────────────────────────────────────┘   │
│                                                                                      │
├─────────────────────────────────────────────────────────────────────────────────────┐
│                                    TRANSAKSI                                        │
├─────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                      │
│   ┌──────────────────┐              ┌──────────────────┐                           │
│   │    pembelian     │              │    penjualan    │                           │
│   │                  │              │                  │                           │
│   │  ┌────────────┐  │              │  ┌────────────┐  │                           │
│   │  │detail_     │  │              │  │detail_     │  │                           │
│   │  │pembelian   │  │              │  │penjualan   │  │                           │
│   │  └────────────┘  │              │  └────────────┘  │                           │
│   │                  │              │                  │                           │
│   │  ┌────────────┐  │              │                  │                           │
│   │  │pembayaran_ │  │              │                  │                           │
│   │  │pembelian  │  │              │                  │                           │
│   │  └────────────┘  │              │                  │                           │
│   │                  │              │                  │                           │
│   └────────┬─────────┘              └────────┬─────────┘                           │
│            │                                │                                     │
│            │                                │                                     │
│            ▼                                ▼                                     │
│   ┌──────────────────┐              ┌──────────────────┐                           │
│   │ retur_pembelian  │              │ retur_penjualan  │                           │
│   │                  │              │                  │                           │
│   │  ┌────────────┐  │              │  ┌────────────┐  │                           │
│   │  │detail_     │  │              │  │detail_     │  │                           │
│   │  │retur_      │  │              │  │retur_      │  │                           │
│   │  │pembelian   │  │              │  │penjualan   │  │                           │
│   │  └────────────┘  │              │  └────────────┘  │                           │
│   └──────────────────┘              └──────────────────┘                           │
│                                                                                      │
└─────────────────────────────────────────────────────────────────────────────────────┘
```

---

## Catatan

- Semua tabel menggunakan `BIGINT UNSIGNED` sebagai primary key dengan auto increment
- Timestamp `created_at` dan `updated_at` otomatis dikelola oleh Laravel
- Soft delete menggunakan kolom `deleted_at` dengan tipe `TIMESTAMP`
- Relasi menggunakan Foreign Key dengan constraint `ON DELETE CASCADE` atau `SET NULL` sesuai kebutuhan
- Kolom `dibuat_oleh` dan `diubah_oleh` adalah foreign key ke tabel `pengguna` untuk tracking perubahan
