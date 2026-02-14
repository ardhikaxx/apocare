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
            ['kode' => 'OBT', 'nama' => 'Obat-obatan'],
            ['kode' => 'VIT', 'nama' => 'Vitamin & Suplemen'],
            ['kode' => 'ALKES', 'nama' => 'Alat Kesehatan'],
            ['kode' => 'KOS', 'nama' => 'Kosmetik'],
            ['kode' => 'UMUM', 'nama' => 'Umum'],
        ];

        foreach ($kategori as $item) {
            DB::table('kategori')->updateOrInsert(
                ['kode' => $item['kode']],
                [
                    'nama' => $item['nama'],
                    'keterangan' => 'Kategori ' . $item['nama'],
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
            ['kode' => 'PMS-007', 'nama' => 'PT First Pacific', 'kontak' => 'Margaret Yan', 'telepon' => '021-57959000', 'email' => 'info@firstpacific.com', 'alamat' => 'Gedung Matahari, Jakarta', 'kota' => 'Jakarta', 'provinsi' => 'DKI Jakarta', 'npwp' => '01.061.730.7-093.000', 'termin' => 45, 'limit' => 500000000],
            ['kode' => 'PMS-008', 'nama' => 'PT Hexion Pharmaceuticals', 'kontak' => 'Susanto', 'telepon' => '021-89112345', 'email' => 'order@hexionpharma.com', 'alamat' => 'Industrial Estate, Bekasi', 'kota' => 'Bekasi', 'provinsi' => 'Jawa Barat', 'npwp' => '01.300.291.4-093.000', 'termin' => 30, 'limit' => 250000000],
            ['kode' => 'PMS-009', 'nama' => 'PT Ethica Industri Farmasi', 'kontak' => 'Jusuf Kalla', 'telepon' => '021-4605510', 'email' => 'marketing@ethica-pharma.com', 'alamat' => 'Jl. Bulevar Selatan, Jakarta', 'kota' => 'Jakarta', 'provinsi' => 'DKI Jakarta', 'npwp' => '01.300.292.4-093.000', 'termin' => 30, 'limit' => 200000000],
            ['kode' => 'PMS-010', 'nama' => 'PT Novell Pharmaceuticals', 'kontak' => 'Rudy Salim', 'telepon' => '021-7654321', 'email' => 'sales@novellpharma.com', 'alamat' => 'Science Town, Bogor', 'kota' => 'Bogor', 'provinsi' => 'Jawa Barat', 'npwp' => '01.300.293.4-093.000', 'termin' => 30, 'limit' => 180000000],
            ['kode' => 'PMS-011', 'nama' => 'PT Ifars', 'kontak' => 'Feri Andika', 'telepon' => '021-88990011', 'email' => 'order@ifars.co.id', 'alamat' => 'Industrial Park, Karawang', 'kota' => 'Karawang', 'provinsi' => 'Jawa Barat', 'npwp' => '01.300.294.4-093.000', 'termin' => 30, 'limit' => 150000000],
            ['kode' => 'PMS-012', 'nama' => 'PT Phapros, tbk', 'kontak' => 'Barito Pamularso', 'telepon' => '021-4891234', 'email' => 'marketing@phapros.com', 'alamat' => 'Jl. Simo Pomahan, Surabaya', 'kota' => 'Surabaya', 'provinsi' => 'Jawa Timur', 'npwp' => '01.300.295.4-093.000', 'termin' => 30, 'limit' => 280000000],
            ['kode' => 'PMS-013', 'nama' => 'PT Fahrenheit', 'kontak' => 'Toni', 'telepon' => '031-5021234', 'email' => 'sales@fahrenheit-pharma.com', 'alamat' => 'Surabaya Industrial Estate', 'kota' => 'Surabaya', 'provinsi' => 'Jawa Timur', 'npwp' => '01.300.296.4-093.000', 'termin' => 30, 'limit' => 150000000],
            ['kode' => 'PMS-014', 'nama' => 'PT Molex Ayu', 'kontak' => 'Andreas', 'telepon' => '021-29560000', 'email' => 'order@molexayu.com', 'alamat' => 'Jl. Pulo Buaran Raya, Jakarta', 'kota' => 'Jakarta', 'provinsi' => 'DKI Jakarta', 'npwp' => '01.300.297.4-093.000', 'termin' => 30, 'limit' => 120000000],
            ['kode' => 'PMS-015', 'nama' => 'PT APL', 'kontak' => 'David', 'telepon' => '021-54321098', 'email' => 'marketing@aplpharma.com', 'alamat' => 'Industrial Area, Cibitung', 'kota' => 'Cibitung', 'provinsi' => 'Jawa Barat', 'npwp' => '01.300.298.4-093.000', 'termin' => 30, 'limit' => 100000000],
            ['kode' => 'PMS-016', 'nama' => 'PT Anugerah Pharmindo Lestari', 'kontak' => 'Iwan', 'telepon' => '021-6901234', 'email' => 'sales@anugerahpharmindo.com', 'alamat' => 'Tangerang', 'kota' => 'Tangerang', 'provinsi' => 'Banten', 'npwp' => '01.300.299.4-093.000', 'termin' => 30, 'limit' => 180000000],
            ['kode' => 'PMS-017', 'nama' => 'PT Enseval', 'kontak' => 'M. Cholid', 'telepon' => '021-4607000', 'email' => 'order@enseval.com', 'alamat' => 'Jakarta', 'kota' => 'Jakarta', 'provinsi' => 'DKI Jakarta', 'npwp' => '01.300.300.4-093.000', 'termin' => 45, 'limit' => 300000000],
            ['kode' => 'PMS-018', 'nama' => 'PT APL (Astra)', 'kontak' => 'Harry', 'telepon' => '021-53111111', 'email' => 'marketing@astra-apl.com', 'alamat' => 'Gedung Astra, Jakarta', 'kota' => 'Jakarta', 'provinsi' => 'DKI Jakarta', 'npwp' => '01.300.301.4-093.000', 'termin' => 30, 'limit' => 250000000],
            ['kode' => 'PMS-019', 'nama' => 'PT Lucas', 'kontak' => 'Jansen', 'telepon' => '022-5201234', 'email' => 'sales@lucaspharma.com', 'alamat' => 'Bandung', 'kota' => 'Bandung', 'provinsi' => 'Jawa Barat', 'npwp' => '01.300.302.4-093.000', 'termin' => 30, 'limit' => 90000000],
            ['kode' => 'PMS-020', 'nama' => 'PT Berlico Mulia Farma', 'kontak' => 'Heri', 'telepon' => '024-3541234', 'email' => 'order@berlico.com', 'alamat' => 'Semarang', 'kota' => 'Semarang', 'provinsi' => 'Jawa Tengah', 'npwp' => '01.300.303.4-093.000', 'termin' => 30, 'limit' => 80000000],
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

        $produkData = [
            ['kode' => 'PRD-OBT-001', 'barcode' => '899100000001', 'nama' => 'Paracetamol 500mg', 'generik' => 'Paracetamol', 'kategori' => 'OBT', 'satuan' => 'STRIP', 'jenis' => 'Obat', 'golongan' => 'Obat Bebas', 'resep' => false, 'beli' => 1500, 'jual' => 2500, 'stok_min' => 50, 'stok_max' => 500, 'rop' => 100, 'rak' => 'A1'],
            ['kode' => 'PRD-OBT-002', 'barcode' => '899100000002', 'nama' => 'Amoxicillin 500mg', 'generik' => 'Amoxicillin', 'kategori' => 'OBT', 'satuan' => 'STRIP', 'jenis' => 'Obat', 'golongan' => 'Obat Keras', 'resep' => true, 'beli' => 2500, 'jual' => 4000, 'stok_min' => 30, 'stok_max' => 300, 'rop' => 60, 'rak' => 'A2'],
            ['kode' => 'PRD-VIT-003', 'barcode' => '899100000003', 'nama' => 'Vitamin C 500mg', 'generik' => 'Ascorbic Acid', 'kategori' => 'VIT', 'satuan' => 'STRIP', 'jenis' => 'Vitamin', 'golongan' => null, 'resep' => false, 'beli' => 1800, 'jual' => 3000, 'stok_min' => 40, 'stok_max' => 400, 'rop' => 80, 'rak' => 'B1'],
            ['kode' => 'PRD-ALK-004', 'barcode' => '899100000004', 'nama' => 'Masker Medis 3 Ply', 'generik' => null, 'kategori' => 'ALKES', 'satuan' => 'BOX', 'jenis' => 'Alkes', 'golongan' => null, 'resep' => false, 'beli' => 15000, 'jual' => 25000, 'stok_min' => 20, 'stok_max' => 200, 'rop' => 40, 'rak' => 'C1'],
            ['kode' => 'PRD-UMUM-005', 'barcode' => '899100000005', 'nama' => 'Hand Sanitizer 100ml', 'generik' => null, 'kategori' => 'UMUM', 'satuan' => 'BOTOL', 'jenis' => 'Umum', 'golongan' => null, 'resep' => false, 'beli' => 8000, 'jual' => 15000, 'stok_min' => 25, 'stok_max' => 250, 'rop' => 50, 'rak' => 'C2'],
            ['kode' => 'PRD-KOS-006', 'barcode' => '899100000006', 'nama' => 'Salep Antiseptik 10g', 'generik' => null, 'kategori' => 'KOS', 'satuan' => 'TUBE', 'jenis' => 'Kosmetik', 'golongan' => null, 'resep' => false, 'beli' => 7000, 'jual' => 12000, 'stok_min' => 30, 'stok_max' => 300, 'rop' => 60, 'rak' => 'B2'],
            ['kode' => 'PRD-OBT-007', 'barcode' => '899100000007', 'nama' => 'Cetirizine 10mg', 'generik' => 'Cetirizine', 'kategori' => 'OBT', 'satuan' => 'STRIP', 'jenis' => 'Obat', 'golongan' => 'Obat Bebas Terbatas', 'resep' => false, 'beli' => 2000, 'jual' => 3500, 'stok_min' => 40, 'stok_max' => 400, 'rop' => 80, 'rak' => 'A3'],
            ['kode' => 'PRD-ALK-008', 'barcode' => '899100000008', 'nama' => 'Termometer Digital', 'generik' => null, 'kategori' => 'ALKES', 'satuan' => 'PCS', 'jenis' => 'Alkes', 'golongan' => null, 'resep' => false, 'beli' => 25000, 'jual' => 45000, 'stok_min' => 10, 'stok_max' => 150, 'rop' => 30, 'rak' => 'C3'],
            ['kode' => 'PRD-VIT-009', 'barcode' => '899100000009', 'nama' => 'Vitamin D3 1000IU', 'generik' => 'Cholecalciferol', 'kategori' => 'VIT', 'satuan' => 'STRIP', 'jenis' => 'Vitamin', 'golongan' => null, 'resep' => false, 'beli' => 2000, 'jual' => 3200, 'stok_min' => 35, 'stok_max' => 350, 'rop' => 70, 'rak' => 'B3'],
            ['kode' => 'PRD-OBT-010', 'barcode' => '899100000010', 'nama' => 'Ibuprofen 400mg', 'generik' => 'Ibuprofen', 'kategori' => 'OBT', 'satuan' => 'STRIP', 'jenis' => 'Obat', 'golongan' => 'Obat Bebas', 'resep' => false, 'beli' => 2200, 'jual' => 3600, 'stok_min' => 45, 'stok_max' => 450, 'rop' => 90, 'rak' => 'A4'],
        ];

        $kategoriCodes = ['OBT', 'VIT', 'ALKES', 'KOS', 'UMUM'];
        $satuanByKategori = [
            'OBT' => 'STRIP',
            'VIT' => 'STRIP',
            'ALKES' => 'PCS',
            'KOS' => 'TUBE',
            'UMUM' => 'BOTOL',
        ];

        $current = count($produkData);
        for ($i = $current + 1; $i <= 50; $i++) {
            $kategoriCode = $kategoriCodes[($i - 1) % count($kategoriCodes)];
            $jenis = $kategoriCode === 'OBT' ? 'Obat' : ($kategoriCode === 'VIT' ? 'Vitamin' : ($kategoriCode === 'ALKES' ? 'Alkes' : ($kategoriCode === 'KOS' ? 'Kosmetik' : 'Umum')));
            $golongan = null;
            $perluResep = false;
            if ($kategoriCode === 'OBT') {
                $golongan = ($i % 3 === 0) ? 'Obat Keras' : 'Obat Bebas';
                $perluResep = $golongan === 'Obat Keras';
            }

            $kode = 'PRD-' . str_pad($i, 4, '0', STR_PAD_LEFT);
            $barcode = '8991' . str_pad((string) $i, 8, '0', STR_PAD_LEFT);
            $hargaBeli = 1200 + ($i * 55);
            $hargaJual = (int) round($hargaBeli * 1.6);
            $nama = $jenis . ' Item ' . str_pad((string) $i, 2, '0', STR_PAD_LEFT);

            $produkData[] = [
                'kode' => $kode,
                'barcode' => $barcode,
                'nama' => $nama,
                'generik' => $kategoriCode === 'OBT' ? $nama : null,
                'kategori' => $kategoriCode,
                'satuan' => $satuanByKategori[$kategoriCode],
                'jenis' => $jenis,
                'golongan' => $golongan,
                'resep' => $perluResep,
                'beli' => $hargaBeli,
                'jual' => $hargaJual,
                'stok_min' => 20 + ($i % 10) * 5,
                'stok_max' => 200 + ($i % 10) * 50,
                'rop' => 40 + ($i % 10) * 10,
                'rak' => chr(65 + ($i % 5)) . (string) (1 + ($i % 10)),
            ];
        }

        foreach ($produkData as $item) {
            $kategoriId = $kategoriMap[$item['kategori']] ?? null;
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
                    'keterangan' => 'Produk master',
                    'jenis_produk' => $item['jenis'],
                    'golongan_obat' => $item['golongan'],
                    'perlu_resep' => $item['resep'],
                    'harga_beli' => $item['beli'],
                    'harga_jual' => $item['jual'],
                    'stok_minimum' => $item['stok_min'],
                    'stok_maksimum' => $item['stok_max'],
                    'titik_pesan_ulang' => $item['rop'],
                    'lokasi_rak' => $item['rak'],
                    'kondisi_penyimpanan' => 'Simpan di tempat sejuk',
                    'status_aktif' => true,
                    'konsinyasi' => false,
                    'persentase_pajak' => 11,
                    'catatan' => 'Data master produk',
                    'dibuat_oleh' => $adminId,
                    'diubah_oleh' => $adminId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $produkMap = DB::table('produk')->pluck('id', 'kode');

        foreach ($produkData as $item) {
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
