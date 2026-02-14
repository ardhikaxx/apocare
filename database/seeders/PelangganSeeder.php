<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PelangganSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $adminId = DB::table('pengguna')->where('username', 'admin')->value('id');

        $pelanggan = [
            ['kode' => 'PLG-0001', 'nama' => 'Andi Wijaya', 'jenis' => 'REGULAR', 'telepon' => '081300000001', 'kota' => 'Jakarta'],
            ['kode' => 'PLG-0002', 'nama' => 'Klinik Sehat Medika', 'jenis' => 'KESEHATAN', 'telepon' => '081300000002', 'kota' => 'Bandung'],
            ['kode' => 'PLG-0003', 'nama' => 'Toko Farma Jaya', 'jenis' => 'RESELLER', 'telepon' => '081300000003', 'kota' => 'Depok'],
            ['kode' => 'PLG-0004', 'nama' => 'Maya Lestari', 'jenis' => 'REGULAR', 'telepon' => '081300000004', 'kota' => 'Bekasi'],
            ['kode' => 'PLG-0005', 'nama' => 'Rudi Hartono', 'jenis' => 'REGULAR', 'telepon' => '081300000005', 'kota' => 'Tangerang'],
            ['kode' => 'PLG-0006', 'nama' => 'PT Sejahtera Abadi', 'jenis' => 'PERUSAHAAN', 'telepon' => '081300000006', 'kota' => 'Bogor'],
            ['kode' => 'PLG-0007', 'nama' => 'Dewi Kurnia', 'jenis' => 'REGULAR', 'telepon' => '081300000007', 'kota' => 'Jakarta'],
            ['kode' => 'PLG-0008', 'nama' => 'Klinik Amanah', 'jenis' => 'KESEHATAN', 'telepon' => '081300000008', 'kota' => 'Bandung'],
            ['kode' => 'PLG-0009', 'nama' => 'Bapak Hendra', 'jenis' => 'REGULAR', 'telepon' => '081300000009', 'kota' => 'Depok'],
            ['kode' => 'PLG-0010', 'nama' => 'Ibu Ratna', 'jenis' => 'REGULAR', 'telepon' => '081300000010', 'kota' => 'Bekasi'],
        ];

        foreach ($pelanggan as $item) {
            DB::table('pelanggan')->updateOrInsert(
                ['kode' => $item['kode']],
                [
                    'nama' => $item['nama'],
                    'jenis_pelanggan' => $item['jenis'],
                    'jenis_identitas' => null,
                    'nomor_identitas' => null,
                    'jenis_kelamin' => null,
                    'tanggal_lahir' => null,
                    'telepon' => $item['telepon'],
                    'email' => strtolower(str_replace(' ', '.', $item['nama'])) . '@example.com',
                    'alamat' => 'Alamat ' . $item['nama'],
                    'kota' => $item['kota'],
                    'provinsi' => 'Jawa Barat',
                    'kode_pos' => '12345',
                    'persentase_diskon' => $item['jenis'] === 'RESELLER' ? 5 : 0,
                    'limit_kredit' => $item['jenis'] === 'KESEHATAN' ? 10000000 : 0,
                    'termin_pembayaran' => $item['jenis'] === 'KESEHATAN' ? 14 : 0,
                    'total_pembelian' => 0,
                    'tanggal_beli_terakhir' => null,
                    'status_aktif' => true,
                    'catatan' => 'Data pelanggan seeding',
                    'dibuat_oleh' => $adminId,
                    'diubah_oleh' => $adminId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
