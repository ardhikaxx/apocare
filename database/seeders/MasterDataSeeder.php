<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $adminId = DB::table('pengguna')->where('username', 'admin')->value('id');

        $satuan = [
            ['kode' => 'PCS', 'nama' => 'Pieces'],
            ['kode' => 'BOX', 'nama' => 'Box'],
            ['kode' => 'STRIP', 'nama' => 'Strip'],
            ['kode' => 'BOTOL', 'nama' => 'Botol'],
            ['kode' => 'SACH', 'nama' => 'Sachet'],
            ['kode' => 'TUBE', 'nama' => 'Tube'],
            ['kode' => 'KAPS', 'nama' => 'Kapsul'],
            ['kode' => 'TABLET', 'nama' => 'Tablet'],
            ['kode' => 'SYRUP', 'nama' => 'Syrup'],
            ['kode' => 'SUSP', 'nama' => 'Suspensi'],
        ];

        foreach ($satuan as $item) {
            DB::table('satuan')->updateOrInsert(
                ['kode' => $item['kode']],
                [
                    'nama' => $item['nama'],
                    'keterangan' => 'Satuan ' . $item['nama'],
                    'status_aktif' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $kategori = [
            ['kode' => 'OBT', 'nama' => 'Obat-obatan', 'ikon' => 'fa-solid fa-pills'],
            ['kode' => 'VIT', 'nama' => 'Vitamin & Suplemen', 'ikon' => 'fa-solid fa-capsules'],
            ['kode' => 'ALKES', 'nama' => 'Alat Kesehatan', 'ikon' => 'fa-solid fa-user-nurse'],
            ['kode' => 'KOS', 'nama' => 'Kosmetik & Perawatan Diri', 'ikon' => 'fa-solid fa-spray-can-sparkles'],
            ['kode' => 'UMUM', 'nama' => 'Produk Umum', 'ikon' => 'fa-solid fa-box'],
        ];

        foreach ($kategori as $item) {
            DB::table('kategori')->updateOrInsert(
                ['kode' => $item['kode']],
                [
                    'nama' => $item['nama'],
                    'keterangan' => 'Kategori ' . $item['nama'],
                    'ikon' => $item['ikon'] ?? null,
                    'status_aktif' => true,
                    'dibuat_oleh' => $adminId,
                    'diubah_oleh' => $adminId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $pemasok = [
            ['kode' => 'PMS-001', 'nama' => 'PT Kimia Farma, tbk', 'kontak' => 'Budi Santoso', 'telepon' => '021-5269222', 'email' => 'sales@kimiafarma.co.id', 'alamat' => 'Jl. Veteran Raya No. 9, Jakarta Pusat', 'kota' => 'Jakarta', 'provinsi' => 'DKI Jakarta', 'npwp' => '01.061.717.9-093.000', 'termin' => 30, 'limit' => 500000000],
            ['kode' => 'PMS-002', 'nama' => 'PT Indofarma, tbk', 'kontak' => 'Hendra Wijaya', 'telepon' => '021-634851', 'email' => 'marketing@indofarma.co.id', 'alamat' => 'Jl. Indofarma Rayan, Bekasi', 'kota' => 'Bekasi', 'provinsi' => 'Jawa Barat', 'npwp' => '01.300.286.4-093.000', 'termin' => 30, 'limit' => 400000000],
            ['kode' => 'PMS-003', 'nama' => 'PT Sanbe Farma', 'kontak' => 'Mochamad Saleh', 'telepon' => '021-84332100', 'email' => 'order@sanbefarma.com', 'alamat' => 'Jl. Tambun Utara, Bekasi', 'kota' => 'Bekasi', 'provinsi' => 'Jawa Barat', 'npwp' => '01.300.287.4-093.000', 'termin' => 45, 'limit' => 600000000],
            ['kode' => 'PMS-004', 'nama' => 'PT Kalbe Farma, tbk', 'kontak' => 'Vidjong Helios', 'telepon' => '021-5249202', 'email' => 'sales@kalbefarma.com', 'alamat' => 'Jl. Letjen MT Haryono Kav. 52-53, Jakarta', 'kota' => 'Jakarta', 'provinsi' => 'DKI Jakarta', 'npwp' => '01.061.728.7-093.000', 'termin' => 30, 'limit' => 750000000],
            ['kode' => 'PMS-005', 'nama' => 'PT Dexa Medica', 'kontak' => 'Dr. Irwan Herisyanta', 'telepon' => '021-4531111', 'email' => 'marketing@dexa-medica.com', 'alamat' => 'Jl. Pulo Brayan Laut No. 2, Medan', 'kota' => 'Medan', 'provinsi' => 'Sumatera Utara', 'npwp' => '01.300.288.4-093.000', 'termin' => 30, 'limit' => 350000000],
            ['kode' => 'PMS-006', 'nama' => 'PT Tempo Scan Pacific, tbk', 'kontak' => 'Djatmiko Wardoyo', 'telepon' => '021-45840800', 'email' => 'sales@tempo-scan.com', 'alamat' => 'Jl. HR Rasuna Said Kav. B-5, Jakarta', 'kota' => 'Jakarta', 'provinsi' => 'DKI Jakarta', 'npwp' => '01.061.729.7-093.000', 'termin' => 30, 'limit' => 300000000],
            ['kode' => 'PMS-007', 'nama' => 'PT Contec Global', 'kontak' => 'Margaret Yan', 'telepon' => '021-57959000', 'email' => 'info@contecglobal.com', 'alamat' => 'Gedung Graha Indramax, Jakarta', 'kota' => 'Jakarta', 'provinsi' => 'DKI Jakarta', 'npwp' => '01.061.730.7-093.000', 'termin' => 45, 'limit' => 500000000],
            ['kode' => 'PMS-008', 'nama' => 'PT Enseval Putera Megatrading', 'kontak' => 'M. Cholid', 'telepon' => '021-4607000', 'email' => 'order@enseval.com', 'alamat' => 'Jakarta', 'kota' => 'Jakarta', 'provinsi' => 'DKI Jakarta', 'npwp' => '01.300.291.4-093.000', 'termin' => 30, 'limit' => 250000000],
            ['kode' => 'PMS-009', 'nama' => 'PT Ethica Industri Farmasi', 'kontak' => 'Jusuf Kalla', 'telepon' => '021-4605510', 'email' => 'marketing@ethica-pharma.com', 'alamat' => 'Jl. Bulevar Selatan, Jakarta', 'kota' => 'Jakarta', 'provinsi' => 'DKI Jakarta', 'npwp' => '01.300.292.4-093.000', 'termin' => 30, 'limit' => 200000000],
            ['kode' => 'PMS-010', 'nama' => 'PT Novell Pharmaceutical Labs', 'kontak' => 'Rudy Salim', 'telepon' => '021-7654321', 'email' => 'sales@novellpharma.com', 'alamat' => 'Science Town, Bogor', 'kota' => 'Bogor', 'provinsi' => 'Jawa Barat', 'npwp' => '01.300.293.4-093.000', 'termin' => 30, 'limit' => 180000000],
            ['kode' => 'PMS-011', 'nama' => 'PT Ifars', 'kontak' => 'Feri Andika', 'telepon' => '021-88990011', 'email' => 'order@ifars.co.id', 'alamat' => 'Industrial Park, Karawang', 'kota' => 'Karawang', 'provinsi' => 'Jawa Barat', 'npwp' => '01.300.294.4-093.000', 'termin' => 30, 'limit' => 150000000],
            ['kode' => 'PMS-012', 'nama' => 'PT Phapros, tbk', 'kontak' => 'Barito Pamularso', 'telepon' => '021-4891234', 'email' => 'marketing@phapros.com', 'alamat' => 'Jl. Simo Pomahan, Surabaya', 'kota' => 'Surabaya', 'provinsi' => 'Jawa Timur', 'npwp' => '01.300.295.4-093.000', 'termin' => 30, 'limit' => 280000000],
            ['kode' => 'PMS-013', 'nama' => 'PT Fahrenheit', 'kontak' => 'Toni', 'telepon' => '031-5021234', 'email' => 'sales@fahrenheit-pharma.com', 'alamat' => 'Surabaya Industrial Estate', 'kota' => 'Surabaya', 'provinsi' => 'Jawa Timur', 'npwp' => '01.300.296.4-093.000', 'termin' => 30, 'limit' => 150000000],
            ['kode' => 'PMS-014', 'nama' => 'PT Anugerah Pharmindo Lestari', 'kontak' => 'Iwan', 'telepon' => '021-6901234', 'email' => 'sales@anugerahpharmindo.com', 'alamat' => 'Tangerang', 'kota' => 'Tangerang', 'provinsi' => 'Banten', 'npwp' => '01.300.299.4-093.000', 'termin' => 30, 'limit' => 180000000],
            ['kode' => 'PMS-015', 'nama' => 'PT APL (Astra)', 'kontak' => 'Harry', 'telepon' => '021-53111111', 'email' => 'marketing@astra-apl.com', 'alamat' => 'Gedung Astra, Jakarta', 'kota' => 'Jakarta', 'provinsi' => 'DKI Jakarta', 'npwp' => '01.300.301.4-093.000', 'termin' => 30, 'limit' => 250000000],
            ['kode' => 'PMS-016', 'nama' => 'PT Berlico Mulia Farma', 'kontak' => 'Heri', 'telepon' => '024-3541234', 'email' => 'order@berlico.com', 'alamat' => 'Semarang', 'kota' => 'Semarang', 'provinsi' => 'Jawa Tengah', 'npwp' => '01.300.303.4-093.000', 'termin' => 30, 'limit' => 80000000],
        ];

        foreach ($pemasok as $item) {
            DB::table('pemasok')->updateOrInsert(
                ['kode' => $item['kode']],
                [
                    'nama' => $item['nama'],
                    'kontak_person' => $item['kontak'],
                    'telepon' => $item['telepon'],
                    'email' => $item['email'],
                    'alamat' => $item['alamat'],
                    'kota' => $item['kota'],
                    'provinsi' => $item['provinsi'],
                    'npwp' => $item['npwp'],
                    'termin_pembayaran' => $item['termin'],
                    'limit_kredit' => $item['limit'],
                    'status_aktif' => true,
                    'catatan' => 'Pemasok farmasi Indonesia',
                    'dibuat_oleh' => $adminId,
                    'diubah_oleh' => $adminId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $kategoriMap = DB::table('kategori')->pluck('id', 'kode');
        $satuanMap = DB::table('satuan')->pluck('id', 'kode');

        // Data produk obat-obatan dengan nama generik asli
        $produkObat = [
            // Analgesik & Antipiretik
            ['kode' => 'PRD-OBT-001', 'barcode' => '8991000001001', 'nama' => 'Paracetamol 500mg Tablet', 'generik' => 'Paracetamol', 'satuan' => 'STRIP', 'golongan' => 'Obat Bebas', 'resep' => false, 'beli' => 1500, 'jual' => 2500, 'stok_min' => 100, 'stok_max' => 1000, 'rop' => 200, 'rak' => 'A1-01', 'pemasok' => 'PMS-001'],
            ['kode' => 'PRD-OBT-002', 'barcode' => '8991000001002', 'nama' => 'Panadol 500mg Film Coated Tablet', 'generik' => 'Paracetamol', 'satuan' => 'STRIP', 'golongan' => 'Obat Bebas', 'resep' => false, 'beli' => 3500, 'jual' => 5500, 'stok_min' => 50, 'stok_max' => 500, 'rop' => 100, 'rak' => 'A1-02', 'pemasok' => 'PMS-004'],
            ['kode' => 'PRD-OBT-003', 'barcode' => '8991000001003', 'nama' => 'Bodrex Tablet', 'generik' => 'Paracetamol + Phenylephrine', 'satuan' => 'STRIP', 'golongan' => 'Obat Bebas', 'resep' => false, 'beli' => 2800, 'jual' => 4500, 'stok_min' => 60, 'stok_max' => 600, 'rop' => 120, 'rak' => 'A1-03', 'pemasok' => 'PMS-004'],
            ['kode' => 'PRD-OBT-004', 'barcode' => '8991000001004', 'nama' => 'Oskadon SP Tablet', 'generik' => 'Paracetamol + Caffeine', 'satuan' => 'STRIP', 'golongan' => 'Obat Bebas', 'resep' => false, 'beli' => 2200, 'jual' => 3500, 'stok_min' => 50, 'stok_max' => 500, 'rop' => 100, 'rak' => 'A1-04', 'pemasok' => 'PMS-003'],
            ['kode' => 'PRD-OBT-005', 'barcode' => '8991000001005', 'nama' => 'Dewapar 500mg Tablet', 'generik' => 'Paracetamol', 'satuan' => 'STRIP', 'golongan' => 'Obat Bebas', 'resep' => false, 'beli' => 1200, 'jual' => 2000, 'stok_min' => 80, 'stok_max' => 800, 'rop' => 160, 'rak' => 'A1-05', 'pemasok' => 'PMS-001'],
            
            // NSAIDs
            ['kode' => 'PRD-OBT-006', 'barcode' => '8991000001006', 'nama' => 'Ibuprofen 400mg Tablet', 'generik' => 'Ibuprofen', 'satuan' => 'STRIP', 'golongan' => 'Obat Bebas', 'resep' => false, 'beli' => 1800, 'jual' => 3000, 'stok_min' => 60, 'stok_max' => 600, 'rop' => 120, 'rak' => 'A2-01', 'pemasok' => 'PMS-001'],
            ['kode' => 'PRD-OBT-007', 'barcode' => '8991000001007', 'nama' => 'Proris 400mg Tablet', 'generik' => 'Ibuprofen', 'satuan' => 'STRIP', 'golongan' => 'Obat Bebas', 'resep' => false, 'beli' => 3200, 'jual' => 5000, 'stok_min' => 40, 'stok_max' => 400, 'rop' => 80, 'rak' => 'A2-02', 'pemasok' => 'PMS-004'],
            ['kode' => 'PRD-OBT-008', 'barcode' => '8991000001008', 'nama' => 'Mylanta 200mg/20mg Tablet', 'generik' => 'Aluminium Hydroxide + Magnesium Hydroxide', 'satuan' => 'STRIP', 'golongan' => 'Obat Bebas', 'resep' => false, 'beli' => 3500, 'jual' => 5500, 'stok_min' => 40, 'stok_max' => 400, 'rop' => 80, 'rak' => 'A2-03', 'pemasok' => 'PMS-004'],
            ['kode' => 'PRD-OBT-009', 'barcode' => '8991000001009', 'nama' => 'Asam Mefenamat 500mg Kapsul', 'generik' => 'Mefenamic Acid', 'satuan' => 'KAPS', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 2800, 'jual' => 4500, 'stok_min' => 40, 'stok_max' => 400, 'rop' => 80, 'rak' => 'A2-04', 'pemasok' => 'PMS-002'],
            ['kode' => 'PRD-OBT-010', 'barcode' => '8991000001010', 'nama' => 'Natrium Diklofenak 50mg Tablet', 'generik' => 'Diclofenac Sodium', 'satuan' => 'STRIP', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 2200, 'jual' => 3800, 'stok_min' => 50, 'stok_max' => 500, 'rop' => 100, 'rak' => 'A2-05', 'pemasok' => 'PMS-002'],
            ['kode' => 'PRD-OBT-011', 'barcode' => '8991000001011', 'nama' => 'Cataflam 50mg Tablet', 'generik' => 'Diclofenac Potassium', 'satuan' => 'STRIP', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 5500, 'jual' => 8500, 'stok_min' => 30, 'stok_max' => 300, 'rop' => 60, 'rak' => 'A2-06', 'pemasok' => 'PMS-004'],
            
            // Antihistamin
            ['kode' => 'PRD-OBT-012', 'barcode' => '8991000001012', 'nama' => 'Cetirizine 10mg Tablet', 'generik' => 'Cetirizine Dihydrochloride', 'satuan' => 'STRIP', 'golongan' => 'Obat Bebas Terbatas', 'resep' => false, 'beli' => 2000, 'jual' => 3500, 'stok_min' => 80, 'stok_max' => 800, 'rop' => 160, 'rak' => 'A3-01', 'pemasok' => 'PMS-005'],
            ['kode' => 'PRD-OBT-013', 'barcode' => '8991000001013', 'nama' => 'Cetirizine 10mg Tablet (Generik)', 'generik' => 'Cetirizine Dihydrochloride', 'satuan' => 'STRIP', 'golongan' => 'Obat Bebas Terbatas', 'resep' => false, 'beli' => 1500, 'jual' => 2500, 'stok_min' => 60, 'stok_max' => 600, 'rop' => 120, 'rak' => 'A3-02', 'pemasok' => 'PMS-001'],
            ['kode' => 'PRD-OBT-014', 'barcode' => '8991000001014', 'nama' => 'Loratadine 10mg Tablet', 'generik' => 'Loratadine', 'satuan' => 'STRIP', 'golongan' => 'Obat Bebas Terbatas', 'resep' => false, 'beli' => 1800, 'jual' => 3000, 'stok_min' => 60, 'stok_max' => 600, 'rop' => 120, 'rak' => 'A3-03', 'pemasok' => 'PMS-005'],
            ['kode' => 'PRD-OBT-015', 'barcode' => '8991000001015', 'nama' => 'Ina 10mg Tablet', 'generik' => 'Loratadine', 'satuan' => 'STRIP', 'golongan' => 'Obat Bebas Terbatas', 'resep' => false, 'beli' => 2500, 'jual' => 4000, 'stok_min' => 50, 'stok_max' => 500, 'rop' => 100, 'rak' => 'A3-04', 'pemasok' => 'PMS-003'],
            ['kode' => 'PRD-OBT-016', 'barcode' => '8991000001016', 'nama' => 'CTM 4mg Tablet', 'generik' => 'Chlorpheniramine Maleate', 'satuan' => 'STRIP', 'golongan' => 'Obat Bebas Terbatas', 'resep' => false, 'beli' => 800, 'jual' => 1500, 'stok_min' => 100, 'stok_max' => 1000, 'rop' => 200, 'rak' => 'A3-05', 'pemasok' => 'PMS-001'],
            
            // Antibiotik
            ['kode' => 'PRD-OBT-017', 'barcode' => '8991000001017', 'nama' => 'Amoxicillin 500mg Kapsul', 'generik' => 'Amoxicillin Trihydrate', 'satuan' => 'KAPS', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 2200, 'jual' => 3800, 'stok_min' => 80, 'stok_max' => 800, 'rop' => 160, 'rak' => 'A4-01', 'pemasok' => 'PMS-002'],
            ['kode' => 'PRD-OBT-018', 'barcode' => '8991000001018', 'nama' => 'Amoxsan 500mg Kapsul', 'generik' => 'Amoxicillin Trihydrate', 'satuan' => 'KAPS', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 3500, 'jual' => 5500, 'stok_min' => 50, 'stok_max' => 500, 'rop' => 100, 'rak' => 'A4-02', 'pemasok' => 'PMS-003'],
            ['kode' => 'PRD-OBT-019', 'barcode' => '8991000001019', 'nama' => 'Biocillin 500mg Kapsul', 'generik' => 'Amoxicillin Trihydrate', 'satuan' => 'KAPS', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 2800, 'jual' => 4500, 'stok_min' => 60, 'stok_max' => 600, 'rop' => 120, 'rak' => 'A4-03', 'pemasok' => 'PMS-005'],
            ['kode' => 'PRD-OBT-020', 'barcode' => '8991000001020', 'nama' => 'Azithromycin 250mg Kapsul', 'generik' => 'Azithromycin Dihydrate', 'satuan' => 'KAPS', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 6500, 'jual' => 10000, 'stok_min' => 40, 'stok_max' => 400, 'rop' => 80, 'rak' => 'A4-04', 'pemasok' => 'PMS-005'],
            ['kode' => 'PRD-OBT-021', 'barcode' => '8991000001021', 'nama' => 'Zithromax 250mg Kapsul', 'generik' => 'Azithromycin Dihydrate', 'satuan' => 'KAPS', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 12000, 'jual' => 18000, 'stok_min' => 20, 'stok_max' => 200, 'rop' => 40, 'rak' => 'A4-05', 'pemasok' => 'PMS-004'],
            ['kode' => 'PRD-OBT-022', 'barcode' => '8991000001022', 'nama' => 'Cefixime 100mg Kapsul', 'generik' => 'Cefixime Trihydrate', 'satuan' => 'KAPS', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 7500, 'jual' => 11500, 'stok_min' => 30, 'stok_max' => 300, 'rop' => 60, 'rak' => 'A4-06', 'pemasok' => 'PMS-005'],
            ['kode' => 'PRD-OBT-023', 'barcode' => '8991000001023', 'nama' => 'Cefspan 100mg Kapsul', 'generik' => 'Cefixime Trihydrate', 'satuan' => 'KAPS', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 9000, 'jual' => 13500, 'stok_min' => 25, 'stok_max' => 250, 'rop' => 50, 'rak' => 'A4-07', 'pemasok' => 'PMS-004'],
            ['kode' => 'PRD-OBT-024', 'barcode' => '8991000001024', 'nama' => 'Levofloxacin 500mg Tablet', 'generik' => 'Levofloxacin Hemihydrate', 'satuan' => 'TABLET', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 8500, 'jual' => 13000, 'stok_min' => 30, 'stok_max' => 300, 'rop' => 60, 'rak' => 'A4-08', 'pemasok' => 'PMS-005'],
            ['kode' => 'PRD-OBT-025', 'barcode' => '8991000001025', 'nama' => 'Metronidazole 500mg Tablet', 'generik' => 'Metronidazole', 'satuan' => 'TABLET', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 1800, 'jual' => 3000, 'stok_min' => 50, 'stok_max' => 500, 'rop' => 100, 'rak' => 'A4-09', 'pemasok' => 'PMS-002'],
            
            // Antidiabetes
            ['kode' => 'PRD-OBT-026', 'barcode' => '8991000001026', 'nama' => 'Metformin 500mg Tablet', 'generik' => 'Metformin HCl', 'satuan' => 'TABLET', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 1200, 'jual' => 2000, 'stok_min' => 80, 'stok_max' => 800, 'rop' => 160, 'rak' => 'A5-01', 'pemasok' => 'PMS-002'],
            ['kode' => 'PRD-OBT-027', 'barcode' => '8991000001027', 'nama' => 'Glimepirid 4mg Tablet', 'generik' => 'Glimepiride', 'satuan' => 'TABLET', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 3500, 'jual' => 5500, 'stok_min' => 40, 'stok_max' => 400, 'rop' => 80, 'rak' => 'A5-02', 'pemasok' => 'PMS-005'],
            ['kode' => 'PRD-OBT-028', 'barcode' => '8991000001028', 'nama' => 'Glibenclamide 5mg Tablet', 'generik' => 'Glibenclamide', 'satuan' => 'TABLET', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 900, 'jual' => 1500, 'stok_min' => 60, 'stok_max' => 600, 'rop' => 120, 'rak' => 'A5-03', 'pemasok' => 'PMS-002'],
            
            // Antihipertensi
            ['kode' => 'PRD-OBT-029', 'barcode' => '8991000001029', 'nama' => 'Amlodipine 10mg Tablet', 'generik' => 'Amlodipine Besylate', 'satuan' => 'TABLET', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 1500, 'jual' => 2500, 'stok_min' => 80, 'stok_max' => 800, 'rop' => 160, 'rak' => 'A6-01', 'pemasok' => 'PMS-005'],
            ['kode' => 'PRD-OBT-030', 'barcode' => '8991000001030', 'nama' => 'Captopril 25mg Tablet', 'generik' => 'Captopril', 'satuan' => 'TABLET', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 800, 'jual' => 1400, 'stok_min' => 60, 'stok_max' => 600, 'rop' => 120, 'rak' => 'A6-02', 'pemasok' => 'PMS-002'],
            ['kode' => 'PRD-OBT-031', 'barcode' => '8991000001031', 'nama' => 'Lisinopril 10mg Tablet', 'generik' => 'Lisinopril', 'satuan' => 'TABLET', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 4200, 'jual' => 6500, 'stok_min' => 40, 'stok_max' => 400, 'rop' => 80, 'rak' => 'A6-03', 'pemasok' => 'PMS-005'],
            ['kode' => 'PRD-OBT-032', 'barcode' => '8991000001032', 'nama' => 'Losartan 50mg Tablet', 'generik' => 'Losartan Potassium', 'satuan' => 'TABLET', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 2500, 'jual' => 4000, 'stok_min' => 60, 'stok_max' => 600, 'rop' => 120, 'rak' => 'A6-04', 'pemasok' => 'PMS-005'],
            
            // Antiasma & Batuk
            ['kode' => 'PRD-OBT-033', 'barcode' => '8991000001033', 'nama' => 'Salbutamol 4mg Tablet', 'generik' => 'Salbutamol Sulfate', 'satuan' => 'TABLET', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 1800, 'jual' => 3000, 'stok_min' => 50, 'stok_max' => 500, 'rop' => 100, 'rak' => 'A7-01', 'pemasok' => 'PMS-002'],
            ['kode' => 'PRD-OBT-034', 'barcode' => '8991000001034', 'nama' => 'Combivent Inhalation', 'generik' => 'Salbutamol + Ipratropium', 'satuan' => 'PCS', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 35000, 'jual' => 52000, 'stok_min' => 10, 'stok_max' => 100, 'rop' => 20, 'rak' => 'A7-02', 'pemasok' => 'PMS-004'],
            ['kode' => 'PRD-OBT-035', 'barcode' => '8991000001035', 'nama' => 'OBH Ijus 100ml Syrup', 'generik' => 'Phenylephrine + Chlorpheniramine', 'satuan' => 'BOTOL', 'golongan' => 'Obat Bebas', 'resep' => false, 'beli' => 8500, 'jual' => 14000, 'stok_min' => 30, 'stok_max' => 300, 'rop' => 60, 'rak' => 'A7-03', 'pemasok' => 'PMS-004'],
            ['kode' => 'PRD-OBT-036', 'barcode' => '8991000001036', 'nama' => 'Woods 100ml Syrup', 'generik' => 'Phenylephrine + Chlorpheniramine', 'satuan' => 'BOTOL', 'golongan' => 'Obat Bebas', 'resep' => false, 'beli' => 12000, 'jual' => 18000, 'stok_min' => 25, 'stok_max' => 250, 'rop' => 50, 'rak' => 'A7-04', 'pemasok' => 'PMS-003'],
            ['kode' => 'PRD-OBT-037', 'barcode' => '8991000001037', 'nama' => 'Siladex 100ml Syrup', 'generik' => 'Diphenhydramine + Ammonium Chloride', 'satuan' => 'BOTOL', 'golongan' => 'Obat Bebas', 'resep' => false, 'beli' => 9000, 'jual' => 15000, 'stok_min' => 30, 'stok_max' => 300, 'rop' => 60, 'rak' => 'A7-05', 'pemasok' => 'PMS-004'],
            
            // Antiemetik & Antidiare
            ['kode' => 'PRD-OBT-038', 'barcode' => '8991000001038', 'nama' => 'Domperidone 10mg Tablet', 'generik' => 'Domperidone', 'satuan' => 'TABLET', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 1500, 'jual' => 2500, 'stok_min' => 50, 'stok_max' => 500, 'rop' => 100, 'rak' => 'A8-01', 'pemasok' => 'PMS-002'],
            ['kode' => 'PRD-OBT-039', 'barcode' => '8991000001039', 'nama' => 'Ondansetron 4mg Tablet', 'generik' => 'Ondansetron HCl', 'satuan' => 'TABLET', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 4500, 'jual' => 7000, 'stok_min' => 40, 'stok_max' => 400, 'rop' => 80, 'rak' => 'A8-02', 'pemasok' => 'PMS-005'],
            ['kode' => 'PRD-OBT-040', 'barcode' => '8991000001040', 'nama' => 'Loperamide 2mg Kapsul', 'generik' => 'Loperamide HCl', 'satuan' => 'KAPS', 'golongan' => 'Obat Bebas Terbatas', 'resep' => false, 'beli' => 1800, 'jual' => 3000, 'stok_min' => 60, 'stok_max' => 600, 'rop' => 120, 'rak' => 'A8-03', 'pemasok' => 'PMS-002'],
            ['kode' => 'PRD-OBT-041', 'barcode' => '8991000001041', 'nama' => 'Enteroimunol 250mg Kapsul', 'generik' => 'Lactobacillus', 'satuan' => 'KAPS', 'golongan' => 'Obat Bebas', 'resep' => false, 'beli' => 5500, 'jual' => 8500, 'stok_min' => 40, 'stok_max' => 400, 'rop' => 80, 'rak' => 'A8-04', 'pemasok' => 'PMS-004'],
            
            // Psikotropik & NN
            ['kode' => 'PRD-OBT-042', 'barcode' => '8991000001042', 'nama' => 'Diazepam 5mg Tablet', 'generik' => 'Diazepam', 'satuan' => 'TABLET', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 1000, 'jual' => 1800, 'stok_min' => 30, 'stok_max' => 300, 'rop' => 60, 'rak' => 'A9-01', 'pemasok' => 'PMS-002'],
            ['kode' => 'PRD-OBT-043', 'barcode' => '8991000001043', 'nama' => 'Aminophylline 100mg Tablet', 'generik' => 'Aminophylline', 'satuan' => 'TABLET', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 1200, 'jual' => 2000, 'stok_min' => 40, 'stok_max' => 400, 'rop' => 80, 'rak' => 'A9-02', 'pemasok' => 'PMS-002'],
            ['kode' => 'PRD-OBT-044', 'barcode' => '8991000001044', 'nama' => 'Furosemide 40mg Tablet', 'generik' => 'Furosemide', 'satuan' => 'TABLET', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 900, 'jual' => 1500, 'stok_min' => 50, 'stok_max' => 500, 'rop' => 100, 'rak' => 'A9-03', 'pemasok' => 'PMS-002'],
            ['kode' => 'PRD-OBT-045', 'barcode' => '8991000001045', 'nama' => 'Spironolactone 25mg Tablet', 'generik' => 'Spironolactone', 'satuan' => 'TABLET', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 2500, 'jual' => 4000, 'stok_min' => 40, 'stok_max' => 400, 'rop' => 80, 'rak' => 'A9-04', 'pemasok' => 'PMS-005'],
            
            // Antikoagulan & Kardio
            ['kode' => 'PRD-OBT-046', 'barcode' => '8991000001046', 'nama' => 'Aspirin 80mg Tablet', 'generik' => 'Acetylsalicylic Acid', 'satuan' => 'TABLET', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 800, 'jual' => 1400, 'stok_min' => 80, 'stok_max' => 800, 'rop' => 160, 'rak' => 'A10-01', 'pemasok' => 'PMS-002'],
            ['kode' => 'PRD-OBT-047', 'barcode' => '8991000001047', 'nama' => 'Clopidogrel 75mg Tablet', 'generik' => 'Clopidogrel Bisulfate', 'satuan' => 'TABLET', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 8500, 'jual' => 13000, 'stok_min' => 30, 'stok_max' => 300, 'rop' => 60, 'rak' => 'A10-02', 'pemasok' => 'PMS-005'],
            ['kode' => 'PRD-OBT-048', 'barcode' => '8991000001048', 'nama' => 'Atorvastatin 20mg Tablet', 'generik' => 'Atorvastatin Calcium', 'satuan' => 'TABLET', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 4500, 'jual' => 7000, 'stok_min' => 50, 'stok_max' => 500, 'rop' => 100, 'rak' => 'A10-03', 'pemasok' => 'PMS-005'],
            ['kode' => 'PRD-OBT-049', 'barcode' => '8991000001049', 'nama' => 'Simvastatin 20mg Tablet', 'generik' => 'Simvastatin', 'satuan' => 'TABLET', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 3000, 'jual' => 4800, 'stok_min' => 50, 'stok_max' => 500, 'rop' => 100, 'rak' => 'A10-04', 'pemasok' => 'PMS-005'],
            ['kode' => 'PRD-OBT-050', 'barcode' => '8991000001050', 'nama' => 'Omeprazole 20mg Kapsul', 'generik' => 'Omeprazole', 'satuan' => 'KAPS', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 2500, 'jual' => 4000, 'stok_min' => 60, 'stok_max' => 600, 'rop' => 120, 'rak' => 'A10-05', 'pemasok' => 'PMS-002'],
        ];

        // Data Vitamin & Suplemen
        $produkVitamin = [
            ['kode' => 'PRD-VIT-001', 'barcode' => '8991000002001', 'nama' => 'Vitamin C 500mg Tablet', 'generik' => 'Ascorbic Acid', 'satuan' => 'STRIP', 'golongan' => null, 'resep' => false, 'beli' => 1800, 'jual' => 3000, 'stok_min' => 100, 'stok_max' => 1000, 'rop' => 200, 'rak' => 'B1-01', 'pemasok' => 'PMS-001'],
            ['kode' => 'PRD-VIT-002', 'barcode' => '8991000002002', 'nama' => 'Redoxon 500mg Effervescent', 'generik' => 'Ascorbic Acid + Zinc', 'satuan' => 'STRIP', 'golongan' => null, 'resep' => false, 'beli' => 6500, 'jual' => 10000, 'stok_min' => 40, 'stok_max' => 400, 'rop' => 80, 'rak' => 'B1-02', 'pemasok' => 'PMS-004'],
            ['kode' => 'PRD-VIT-003', 'barcode' => '8991000002003', 'nama' => 'Vitamin C 1000mg Effervescent', 'generik' => 'Ascorbic Acid', 'satuan' => 'STRIP', 'golongan' => null, 'resep' => false, 'beli' => 5500, 'jual' => 8500, 'stok_min' => 50, 'stok_max' => 500, 'rop' => 100, 'rak' => 'B1-03', 'pemasok' => 'PMS-005'],
            ['kode' => 'PRD-VIT-004', 'barcode' => '8991000002004', 'nama' => 'Vitamin D3 1000IU Softgel', 'generik' => 'Cholecalciferol', 'satuan' => 'STRIP', 'golongan' => null, 'resep' => false, 'beli' => 3500, 'jual' => 5500, 'stok_min' => 60, 'stok_max' => 600, 'rop' => 120, 'rak' => 'B1-04', 'pemasok' => 'PMS-005'],
            ['kode' => 'PRD-VIT-005', 'barcode' => '8991000002005', 'nama' => 'Vitamin D3 4000IU Softgel', 'generik' => 'Cholecalciferol', 'satuan' => 'STRIP', 'golongan' => null, 'resep' => false, 'beli' => 4500, 'jual' => 7000, 'stok_min' => 50, 'stok_max' => 500, 'rop' => 100, 'rak' => 'B1-05', 'pemasok' => 'PMS-005'],
            ['kode' => 'PRD-VIT-006', 'barcode' => '8991000002006', 'nama' => 'Vitamin B Complex Tablet', 'generik' => 'Vitamin B1+B6+B12', 'satuan' => 'STRIP', 'golongan' => null, 'resep' => false, 'beli' => 2500, 'jual' => 4000, 'stok_min' => 60, 'stok_max' => 600, 'rop' => 120, 'rak' => 'B2-01', 'pemasok' => 'PMS-001'],
            ['kode' => 'PRD-VIT-007', 'barcode' => '8991000002007', 'nama' => 'Neurobion 5000mcg Tablet', 'generik' => 'Vitamin B1+B6+B12', 'satuan' => 'STRIP', 'golongan' => null, 'resep' => false, 'beli' => 6500, 'jual' => 10000, 'stok_min' => 40, 'stok_max' => 400, 'rop' => 80, 'rak' => 'B2-02', 'pemasok' => 'PMS-004'],
            ['kode' => 'PRD-VIT-008', 'barcode' => '8991000002008', 'nama' => 'Becom C Tablet', 'generik' => 'Vitamin B-Complex + Vitamin C', 'satuan' => 'STRIP', 'golongan' => null, 'resep' => false, 'beli' => 5500, 'jual' => 8500, 'stok_min' => 40, 'stok_max' => 400, 'rop' => 80, 'rak' => 'B2-03', 'pemasok' => 'PMS-004'],
            ['kode' => 'PRD-VIT-009', 'barcode' => '8991000002009', 'nama' => 'Vitamin B1 50mg Tablet', 'generik' => 'Thiamine HCl', 'satuan' => 'STRIP', 'golongan' => null, 'resep' => false, 'beli' => 1200, 'jual' => 2000, 'stok_min' => 50, 'stok_max' => 500, 'rop' => 100, 'rak' => 'B2-04', 'pemasok' => 'PMS-001'],
            ['kode' => 'PRD-VIT-010', 'barcode' => '8991000002010', 'nama' => 'Vitamin B6 10mg Tablet', 'generik' => 'Pyridoxine HCl', 'satuan' => 'STRIP', 'golongan' => null, 'resep' => false, 'beli' => 1000, 'jual' => 1700, 'stok_min' => 50, 'stok_max' => 500, 'rop' => 100, 'rak' => 'B2-05', 'pemasok' => 'PMS-001'],
            ['kode' => 'PRD-VIT-011', 'barcode' => '8991000002011', 'nama' => 'Zinc 20mg Tablet', 'generik' => 'Zinc Gluconate', 'satuan' => 'STRIP', 'golongan' => null, 'resep' => false, 'beli' => 2000, 'jual' => 3200, 'stok_min' => 60, 'stok_max' => 600, 'rop' => 120, 'rak' => 'B3-01', 'pemasok' => 'PMS-005'],
            ['kode' => 'PRD-VIT-012', 'barcode' => '8991000002012', 'nama' => 'Zinc+ Syrup 100ml', 'generik' => 'Zinc Gluconate', 'satuan' => 'BOTOL', 'golongan' => null, 'resep' => false, 'beli' => 12000, 'jual' => 18000, 'stok_min' => 30, 'stok_max' => 300, 'rop' => 60, 'rak' => 'B3-02', 'pemasok' => 'PMS-004'],
            ['kode' => 'PRD-VIT-013', 'barcode' => '8991000002013', 'nama' => 'Curcuma 300mg Tablet', 'generik' => 'Curcuma Longa', 'satuan' => 'STRIP', 'golongan' => null, 'resep' => false, 'beli' => 3500, 'jual' => 5500, 'stok_min' => 40, 'stok_max' => 400, 'rop' => 80, 'rak' => 'B3-03', 'pemasok' => 'PMS-003'],
            ['kode' => 'PRD-VIT-014', 'barcode' => '8991000002014', 'nama' => 'Kunyit+ Tablet', 'generik' => 'Curcuma + Ginger', 'satuan' => 'STRIP', 'golongan' => null, 'resep' => false, 'beli' => 4000, 'jual' => 6500, 'stok_min' => 40, 'stok_max' => 400, 'rop' => 80, 'rak' => 'B3-04', 'pemasok' => 'PMS-004'],
            ['kode' => 'PRD-VIT-015', 'barcode' => '8991000002015', 'nama' => 'Vitamin E 400IU Softgel', 'generik' => 'Tocopherol', 'satuan' => 'STRIP', 'golongan' => null, 'resep' => false, 'beli' => 4500, 'jual' => 7000, 'stok_min' => 50, 'stok_max' => 500, 'rop' => 100, 'rak' => 'B4-01', 'pemasok' => 'PMS-005'],
            ['kode' => 'PRD-VIT-016', 'barcode' => '8991000002016', 'nama' => 'Vitamin A 5000IU Softgel', 'generik' => 'Retinol', 'satuan' => 'STRIP', 'golongan' => null, 'resep' => false, 'beli' => 2000, 'jual' => 3200, 'stok_min' => 50, 'stok_max' => 500, 'rop' => 100, 'rak' => 'B4-02', 'pemasok' => 'PMS-001'],
            ['kode' => 'PRD-VIT-017', 'barcode' => '8991000002017', 'nama' => 'Multivitamin Tablet', 'generik' => 'Multi Vitamin + Mineral', 'satuan' => 'STRIP', 'golongan' => null, 'resep' => false, 'beli' => 3500, 'jual' => 5500, 'stok_min' => 60, 'stok_max' => 600, 'rop' => 120, 'rak' => 'B4-03', 'pemasok' => 'PMS-004'],
            ['kode' => 'PRD-VIT-018', 'barcode' => '8991000002018', 'nama' => 'Supradyn Tablet Effervescent', 'generik' => 'Multi Vitamin + Mineral', 'satuan' => 'STRIP', 'golongan' => null, 'resep' => false, 'beli' => 8000, 'jual' => 12000, 'stok_min' => 30, 'stok_max' => 300, 'rop' => 60, 'rak' => 'B4-04', 'pemasok' => 'PMS-004'],
            ['kode' => 'PRD-VIT-019', 'barcode' => '8991000002019', 'nama' => 'Folic Acid 1mg Tablet', 'generik' => 'Folic Acid', 'satuan' => 'TABLET', 'golongan' => null, 'resep' => false, 'beli' => 800, 'jual' => 1400, 'stok_min' => 60, 'stok_max' => 600, 'rop' => 120, 'rak' => 'B4-05', 'pemasok' => 'PMS-001'],
            ['kode' => 'PRD-VIT-020', 'barcode' => '8991000002020', 'nama' => 'Calcium 500mg Tablet', 'generik' => 'Calcium Carbonate', 'satuan' => 'STRIP', 'golongan' => null, 'resep' => false, 'beli' => 2500, 'jual' => 4000, 'stok_min' => 50, 'stok_max' => 500, 'rop' => 100, 'rak' => 'B5-01', 'pemasok' => 'PMS-005'],
            ['kode' => 'PRD-VIT-021', 'barcode' => '8991000002021', 'nama' => 'Cal+ D3 Tablet', 'generik' => 'Calcium + Vitamin D3', 'satuan' => 'STRIP', 'golongan' => null, 'resep' => false, 'beli' => 4500, 'jual' => 7000, 'stok_min' => 40, 'stok_max' => 400, 'rop' => 80, 'rak' => 'B5-02', 'pemasok' => 'PMS-004'],
            ['kode' => 'PRD-VIT-022', 'barcode' => '8991000002022', 'nama' => 'Ferro Sulfat 300mg Tablet', 'generik' => 'Ferrous Sulfate', 'satuan' => 'STRIP', 'golongan' => null, 'resep' => false, 'beli' => 1500, 'jual' => 2500, 'stok_min' => 50, 'stok_max' => 500, 'rop' => 100, 'rak' => 'B5-03', 'pemasok' => 'PMS-001'],
            ['kode' => 'PRD-VIT-023', 'barcode' => '8991000002023', 'nama' => 'Sangobion kapsul', 'generik' => 'Ferrous Gluconate + Vitamin', 'satuan' => 'KAPS', 'golongan' => null, 'resep' => false, 'beli' => 6500, 'jual' => 10000, 'stok_min' => 40, 'stok_max' => 400, 'rop' => 80, 'rak' => 'B5-04', 'pemasok' => 'PMS-004'],
            ['kode' => 'PRD-VIT-024', 'barcode' => '8991000002024', 'nama' => 'Omega-3 1000mg Softgel', 'generik' => 'Fish Oil (EPA/DHA)', 'satuan' => 'STRIP', 'golongan' => null, 'resep' => false, 'beli' => 12000, 'jual' => 18000, 'stok_min' => 25, 'stok_max' => 250, 'rop' => 50, 'rak' => 'B5-05', 'pemasok' => 'PMS-005'],
            ['kode' => 'PRD-VIT-025', 'barcode' => '8991000002025', 'nama' => 'Propolis 500mg Tablet', 'generik' => 'Propolis Extract', 'satuan' => 'STRIP', 'golongan' => null, 'resep' => false, 'beli' => 8000, 'jual' => 12000, 'stok_min' => 20, 'stok_max' => 200, 'rop' => 40, 'rak' => 'B6-01', 'pemasok' => 'PMS-004'],
        ];

        // Data Alat Kesehatan
        $produkAlkes = [
            ['kode' => 'PRD-ALK-001', 'barcode' => '8991000003001', 'nama' => 'Masker Medis 3 Ply 50pcs/Box', 'generik' => null, 'satuan' => 'BOX', 'golongan' => null, 'resep' => false, 'beli' => 18000, 'jual' => 28000, 'stok_min' => 50, 'stok_max' => 500, 'rop' => 100, 'rak' => 'C1-01', 'pemasok' => 'PMS-007'],
            ['kode' => 'PRD-ALK-002', 'barcode' => '8991000003002', 'nama' => 'Masker N95 20pcs/Box', 'generik' => null, 'satuan' => 'BOX', 'golongan' => null, 'resep' => false, 'beli' => 45000, 'jual' => 65000, 'stok_min' => 20, 'stok_max' => 200, 'rop' => 40, 'rak' => 'C1-02', 'pemasok' => 'PMS-007'],
            ['kode' => 'PRD-ALK-003', 'barcode' => '8991000003003', 'nama' => 'Masker Anak 3 Ply 50pcs/Box', 'generik' => null, 'satuan' => 'BOX', 'golongan' => null, 'resep' => false, 'beli' => 20000, 'jual' => 30000, 'stok_min' => 30, 'stok_max' => 300, 'rop' => 60, 'rak' => 'C1-03', 'pemasok' => 'PMS-007'],
            ['kode' => 'PRD-ALK-004', 'barcode' => '8991000003004', 'nama' => 'Handschoen Latex S 100pcs/Box', 'generik' => null, 'satuan' => 'BOX', 'golongan' => null, 'resep' => false, 'beli' => 25000, 'jual' => 38000, 'stok_min' => 25, 'stok_max' => 250, 'rop' => 50, 'rak' => 'C2-01', 'pemasok' => 'PMS-007'],
            ['kode' => 'PRD-ALK-005', 'barcode' => '8991000003005', 'nama' => 'Handschoen Latex M 100pcs/Box', 'generik' => null, 'satuan' => 'BOX', 'golongan' => null, 'resep' => false, 'beli' => 25000, 'jual' => 38000, 'stok_min' => 25, 'stok_max' => 250, 'rop' => 50, 'rak' => 'C2-02', 'pemasok' => 'PMS-007'],
            ['kode' => 'PRD-ALK-006', 'barcode' => '8991000003006', 'nama' => 'Handschoen Non Latex S 100pcs/Box', 'generik' => null, 'satuan' => 'BOX', 'golongan' => null, 'resep' => false, 'beli' => 30000, 'jual' => 45000, 'stok_min' => 20, 'stok_max' => 200, 'rop' => 40, 'rak' => 'C2-03', 'pemasok' => 'PMS-007'],
            ['kode' => 'PRD-ALK-007', 'barcode' => '8991000003007', 'nama' => 'Termometer Digital', 'generik' => null, 'satuan' => 'PCS', 'golongan' => null, 'resep' => false, 'beli' => 25000, 'jual' => 42000, 'stok_min' => 20, 'stok_max' => 200, 'rop' => 40, 'rak' => 'C3-01', 'pemasok' => 'PMS-007'],
            ['kode' => 'PRD-ALK-008', 'barcode' => '8991000003008', 'nama' => 'Termometer Infrared', 'generik' => null, 'satuan' => 'PCS', 'golongan' => null, 'resep' => false, 'beli' => 85000, 'jual' => 125000, 'stok_min' => 10, 'stok_max' => 100, 'rop' => 20, 'rak' => 'C3-02', 'pemasok' => 'PMS-007'],
            ['kode' => 'PRD-ALK-009', 'barcode' => '8991000003009', 'nama' => 'Tensimeter Digital', 'generik' => null, 'satuan' => 'PCS', 'golongan' => null, 'resep' => false, 'beli' => 145000, 'jual' => 210000, 'stok_min' => 10, 'stok_max' => 100, 'rop' => 20, 'rak' => 'C4-01', 'pemasok' => 'PMS-007'],
            ['kode' => 'PRD-ALK-010', 'barcode' => '8991000003010', 'nama' => 'Tensimeter Aneroid', 'generik' => null, 'satuan' => 'PCS', 'golongan' => null, 'resep' => false, 'beli' => 95000, 'jual' => 145000, 'stok_min' => 10, 'stok_max' => 100, 'rop' => 20, 'rak' => 'C4-02', 'pemasok' => 'PMS-007'],
            ['kode' => 'PRD-ALK-011', 'barcode' => '8991000003011', 'nama' => 'Stetoskop', 'generik' => null, 'satuan' => 'PCS', 'golongan' => null, 'resep' => false, 'beli' => 65000, 'jual' => 95000, 'stok_min' => 10, 'stok_max' => 100, 'rop' => 20, 'rak' => 'C4-03', 'pemasok' => 'PMS-007'],
            ['kode' => 'PRD-ALK-012', 'barcode' => '8991000003012', 'nama' => 'Nebulizer', 'generik' => null, 'satuan' => 'PCS', 'golongan' => null, 'resep' => false, 'beli' => 185000, 'jual' => 275000, 'stok_min' => 5, 'stok_max' => 50, 'rop' => 10, 'rak' => 'C5-01', 'pemasok' => 'PMS-007'],
            ['kode' => 'PRD-ALK-013', 'barcode' => '8991000003013', 'nama' => 'Nebulizer Portable', 'generik' => null, 'satuan' => 'PCS', 'golongan' => null, 'resep' => false, 'beli' => 225000, 'jual' => 325000, 'stok_min' => 5, 'stok_max' => 50, 'rop' => 10, 'rak' => 'C5-02', 'pemasok' => 'PMS-007'],
            ['kode' => 'PRD-ALK-014', 'barcode' => '8991000003014', 'nama' => 'Glukometer', 'generik' => null, 'satuan' => 'PCS', 'golongan' => null, 'resep' => false, 'beli' => 95000, 'jual' => 145000, 'stok_min' => 15, 'stok_max' => 150, 'rop' => 30, 'rak' => 'C6-01', 'pemasok' => 'PMS-007'],
            ['kode' => 'PRD-ALK-015', 'barcode' => '8991000003015', 'nama' => 'Test Strips Glukometer 50pcs', 'generik' => null, 'satuan' => 'BOX', 'golongan' => null, 'resep' => false, 'beli' => 55000, 'jual' => 85000, 'stok_min' => 20, 'stok_max' => 200, 'rop' => 40, 'rak' => 'C6-02', 'pemasok' => 'PMS-007'],
            ['kode' => 'PRD-ALK-016', 'barcode' => '8991000003016', 'nama' => 'Lancet Glukometer 100pcs', 'generik' => null, 'satuan' => 'BOX', 'golongan' => null, 'resep' => false, 'beli' => 15000, 'jual' => 25000, 'stok_min' => 30, 'stok_max' => 300, 'rop' => 60, 'rak' => 'C6-03', 'pemasok' => 'PMS-007'],
            ['kode' => 'PRD-ALK-017', 'barcode' => '8991000003017', 'nama' => 'Injeksi Terlacak 1ml 100pcs', 'generik' => null, 'satuan' => 'BOX', 'golongan' => null, 'resep' => false, 'beli' => 35000, 'jual' => 52000, 'stok_min' => 20, 'stok_max' => 200, 'rop' => 40, 'rak' => 'C7-01', 'pemasok' => 'PMS-007'],
            ['kode' => 'PRD-ALK-018', 'barcode' => '8991000003018', 'nama' => 'Injeksi Terlacak 3ml 100pcs', 'generik' => null, 'satuan' => 'BOX', 'golongan' => null, 'resep' => false, 'beli' => 42000, 'jual' => 62000, 'stok_min' => 20, 'stok_max' => 200, 'rop' => 40, 'rak' => 'C7-02', 'pemasok' => 'PMS-007'],
            ['kode' => 'PRD-ALK-019', 'barcode' => '8991000003019', 'nama' => 'Infus Set Adult', 'generik' => null, 'satuan' => 'PCS', 'golongan' => null, 'resep' => false, 'beli' => 5500, 'jual' => 8500, 'stok_min' => 50, 'stok_max' => 500, 'rop' => 100, 'rak' => 'C7-03', 'pemasok' => 'PMS-007'],
            ['kode' => 'PRD-ALK-020', 'barcode' => '8991000003020', 'nama' => 'Spuit 1ml with Needle 100pcs', 'generik' => null, 'satuan' => 'BOX', 'golongan' => null, 'resep' => false, 'beli' => 38000, 'jual' => 55000, 'stok_min' => 20, 'stok_max' => 200, 'rop' => 40, 'rak' => 'C7-04', 'pemasok' => 'PMS-007'],
        ];

        // Data Produk Kosmetik & Perawatan Diri
        $produkKosmetik = [
            ['kode' => 'PRD-KOS-001', 'barcode' => '8991000004001', 'nama' => 'Bedak Salic 100g', 'generik' => null, 'satuan' => 'PCS', 'golongan' => null, 'resep' => false, 'beli' => 8000, 'jual' => 13000, 'stok_min' => 30, 'stok_max' => 300, 'rop' => 60, 'rak' => 'D1-01', 'pemasok' => 'PMS-006'],
            ['kode' => 'PRD-KOS-002', 'barcode' => '8991000004002', 'nama' => 'Krim Salic 10g', 'generik' => null, 'satuan' => 'TUBE', 'golongan' => null, 'resep' => false, 'beli' => 12000, 'jual' => 18000, 'stok_min' => 25, 'stok_max' => 250, 'rop' => 50, 'rak' => 'D1-02', 'pemasok' => 'PMS-006'],
            ['kode' => 'PRD-KOS-003', 'barcode' => '8991000004003', 'nama' => 'Lip Cream SPF 15', 'generik' => null, 'satuan' => 'PCS', 'golongan' => null, 'resep' => false, 'beli' => 15000, 'jual' => 24000, 'stok_min' => 20, 'stok_max' => 200, 'rop' => 40, 'rak' => 'D1-03', 'pemasok' => 'PMS-006'],
            ['kode' => 'PRD-KOS-004', 'barcode' => '8991000004004', 'nama' => 'Sunblock SPF 30 50ml', 'generik' => null, 'satuan' => 'PCS', 'golongan' => null, 'resep' => false, 'beli' => 25000, 'jual' => 38000, 'stok_min' => 25, 'stok_max' => 250, 'rop' => 50, 'rak' => 'D1-04', 'pemasok' => 'PMS-006'],
            ['kode' => 'PRD-KOS-005', 'barcode' => '8991000004005', 'nama' => 'Pantyliner 10pcs', 'generik' => null, 'satuan' => 'PCS', 'golongan' => null, 'resep' => false, 'beli' => 8000, 'jual' => 13000, 'stok_min' => 40, 'stok_max' => 400, 'rop' => 80, 'rak' => 'D2-01', 'pemasok' => 'PMS-006'],
        ];

        // Data Produk Umum
        $produkUmum = [
            ['kode' => 'PRD-UMUM-001', 'barcode' => '8991000005001', 'nama' => 'Hand Sanitizer 100ml', 'generik' => null, 'satuan' => 'BOTOL', 'golongan' => null, 'resep' => false, 'beli' => 8000, 'jual' => 14000, 'stok_min' => 50, 'stok_max' => 500, 'rop' => 100, 'rak' => 'E1-01', 'pemasok' => 'PMS-006'],
            ['kode' => 'PRD-UMUM-002', 'barcode' => '8991000005002', 'nama' => 'Hand Sanitizer 500ml', 'generik' => null, 'satuan' => 'BOTOL', 'golongan' => null, 'resep' => false, 'beli' => 18000, 'jual' => 28000, 'stok_min' => 30, 'stok_max' => 300, 'rop' => 60, 'rak' => 'E1-02', 'pemasok' => 'PMS-006'],
            ['kode' => 'PRD-UMUM-003', 'barcode' => '8991000005003', 'nama' => 'P3K Kit', 'generik' => null, 'satuan' => 'PCS', 'golongan' => null, 'resep' => false, 'beli' => 35000, 'jual' => 52000, 'stok_min' => 15, 'stok_max' => 150, 'rop' => 30, 'rak' => 'E2-01', 'pemasok' => 'PMS-007'],
            ['kode' => 'PRD-UMUM-004', 'barcode' => '8991000005004', 'nama' => 'Plester 1 rol', 'generik' => null, 'satuan' => 'PCS', 'golongan' => null, 'resep' => false, 'beli' => 5000, 'jual' => 8500, 'stok_min' => 40, 'stok_max' => 400, 'rop' => 80, 'rak' => 'E2-02', 'pemasok' => 'PMS-007'],
            ['kode' => 'PRD-UMUM-005', 'barcode' => '8991000005005', 'nama' => 'Kassa Steril 10x10cm 100pcs', 'generik' => null, 'satuan' => 'PCS', 'golongan' => null, 'resep' => false, 'beli' => 25000, 'jual' => 38000, 'stok_min' => 20, 'stok_max' => 200, 'rop' => 40, 'rak' => 'E2-03', 'pemasok' => 'PMS-007'],
            ['kode' => 'PRD-UMUM-006', 'barcode' => '8991000005006', 'nama' => 'Alkohol 70% 100ml', 'generik' => null, 'satuan' => 'BOTOL', 'golongan' => null, 'resep' => false, 'beli' => 6000, 'jual' => 10000, 'stok_min' => 40, 'stok_max' => 400, 'rop' => 80, 'rak' => 'E3-01', 'pemasok' => 'PMS-001'],
            ['kode' => 'PRD-UMUM-007', 'barcode' => '8991000005007', 'nama' => 'Betadine 30ml', 'generik' => null, 'satuan' => 'BOTOL', 'golongan' => null, 'resep' => false, 'beli' => 12000, 'jual' => 18000, 'stok_min' => 30, 'stok_max' => 300, 'rop' => 60, 'rak' => 'E3-02', 'pemasok' => 'PMS-004'],
            ['kode' => 'PRD-UMUM-008', 'barcode' => '8991000005008', 'nama' => 'Obat Kumur 200ml', 'generik' => null, 'satuan' => 'BOTOL', 'golongan' => null, 'resep' => false, 'beli' => 15000, 'jual' => 24000, 'stok_min' => 25, 'stok_max' => 250, 'rop' => 50, 'rak' => 'E3-03', 'pemasok' => 'PMS-006'],
            ['kode' => 'PRD-UMUM-009', 'barcode' => '8991000005009', 'nama' => 'Kondom 12pcs', 'generik' => null, 'satuan' => 'BOX', 'golongan' => null, 'resep' => false, 'beli' => 25000, 'jual' => 38000, 'stok_min' => 30, 'stok_max' => 300, 'rop' => 60, 'rak' => 'E4-01', 'pemasok' => 'PMS-004'],
            ['kode' => 'PRD-UMUM-010', 'barcode' => '8991000005010', 'nama' => 'Test Pack', 'generik' => null, 'satuan' => 'PCS', 'golongan' => null, 'resep' => false, 'beli' => 8000, 'jual' => 13000, 'stok_min' => 40, 'stok_max' => 400, 'rop' => 80, 'rak' => 'E4-02', 'pemasok' => 'PMS-004'],
        ];

        // Gabungkan semua produk
        $semuaProduk = array_merge($produkObat, $produkVitamin, $produkAlkes, $produkKosmetik, $produkUmum);

        $kategoriByCode = [
            'OBT' => 'OBT',
            'VIT' => 'VIT',
            'ALK' => 'ALKES',
            'KOS' => 'KOS',
            'UMU' => 'UMUM',
        ];

        foreach ($semuaProduk as $item) {
            $kategoriCode = $kategoriByCode[substr($item['kode'], 4, 3)];
            $kategoriId = $kategoriMap[$kategoriCode] ?? null;
            $satuanId = $satuanMap[$item['satuan']] ?? null;
            
            if (!$kategoriId || !$satuanId) {
                continue;
            }

            DB::table('produk')->updateOrInsert(
                ['kode' => $item['kode']],
                [
                    'barcode' => $item['barcode'],
                    'nama' => $item['nama'],
                    'nama_generik' => $item['generik'],
                    'kategori_id' => $kategoriId,
                    'satuan_id' => $satuanId,
                    'produsen' => 'Apocare',
                    'keterangan' => 'Produk farmasi',
                    'jenis_produk' => $kategoriCode === 'OBT' ? 'Obat' : ($kategoriCode === 'VIT' ? 'Vitamin' : ($kategoriCode === 'ALKES' ? 'Alkes' : ($kategoriCode === 'KOS' ? 'Kosmetik' : 'Umum'))),
                    'golongan_obat' => $item['golongan'],
                    'perlu_resep' => $item['resep'],
                    'harga_beli' => $item['beli'],
                    'harga_jual' => $item['jual'],
                    'stok_minimum' => $item['stok_min'],
                    'stok_maksimum' => $item['stok_max'],
                    'titik_pesan_ulang' => $item['rop'],
                    'lokasi_rak' => $item['rak'],
                    'kondisi_penyimpanan' => 'Simpan di tempat sejuk dan kering',
                    'status_aktif' => true,
                    'konsinyasi' => false,
                    'persentase_pajak' => 11,
                    'catatan' => 'Data master produk farmasi',
                    'dibuat_oleh' => $adminId,
                    'diubah_oleh' => $adminId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $produkMap = DB::table('produk')->pluck('id', 'kode');

        foreach ($semuaProduk as $item) {
            $produkId = $produkMap[$item['kode']] ?? null;
            $satuanId = $satuanMap[$item['satuan']] ?? null;
            if (!$produkId || !$satuanId) {
                continue;
            }

            DB::table('satuan_produk')->updateOrInsert(
                ['produk_id' => $produkId, 'satuan_id' => $satuanId],
                [
                    'faktor_konversi' => 1,
                    'barcode' => null,
                    'harga_beli' => $item['beli'],
                    'harga_jual' => $item['jual'],
                    'default_pembelian' => true,
                    'default_penjualan' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
