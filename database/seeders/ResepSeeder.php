<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResepSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $adminId = DB::table('pengguna')->where('username', 'admin')->value('id');
        $apotekerId = DB::table('pengguna')->first()?->id;

        $dokterIds = DB::table('dokter')->pluck('id')->toArray();
        $pelangganIds = DB::table('pelanggan')->pluck('id')->toArray();

        if (empty($dokterIds) || empty($pelangganIds)) {
            $this->command->warn('Dokter atau Pelanggan belum ada. Seed dokter dan pelanggan terlebih dahulu.');
            return;
        }

        $produkIds = DB::table('produk')->pluck('id')->toArray();

        $reseps = [
            [
                'nomor_resep' => 'RSP-2026-0001',
                'tanggal_resep' => now()->subDays(5)->format('Y-m-d'),
                'pelanggan_index' => 0,
                'dokter_index' => 0,
                'diagnosa' => 'Demam dan flu biasa',
                'status' => 'SELESAI',
                'total_item' => 3,
                'total_harga' => 75000,
                'catatan' => 'Resep kontrol',
                'apoteker_id' => $apotekerId,
                'waktu_verifikasi' => now()->subDays(5),
                'items' => [
                    ['produk_index' => 0, 'dosis' => '500mg', 'frekuensi' => '3x sehari', 'durasi' => '5 hari', 'cara_pakai' => 'Setelah makan', 'jumlah_resep' => 15, 'jumlah_diberikan' => 15, 'harga_satuan' => 2000, 'instruksi' => 'Diminum setelah makan pagi, siang, malam'],
                    ['produk_index' => 1, 'dosis' => '100mg', 'frekuensi' => '3x sehari', 'durasi' => '5 hari', 'cara_pakai' => 'Sebelum makan', 'jumlah_resep' => 15, 'jumlah_diberikan' => 15, 'harga_satuan' => 1500, 'instruksi' => 'Diminum sebelum makan'],
                    ['produk_index' => 2, 'dosis' => '1 tablet', 'frekuensi' => 'Jika demam', 'durasi' => '5 hari', 'cara_pakai' => 'Jika demam', 'jumlah_resep' => 10, 'jumlah_diberikan' => 10, 'harga_satuan' => 2500, 'instruksi' => 'Diminum jika suhu > 37.5°C'],
                ]
            ],
            [
                'nomor_resep' => 'RSP-2026-0002',
                'tanggal_resep' => now()->subDays(3)->format('Y-m-d'),
                'pelanggan_index' => 3,
                'dokter_index' => 1,
                'diagnosa' => 'Infeksi saluran pernafasan atas',
                'status' => 'SEBAGIAN',
                'total_item' => 4,
                'total_harga' => 125000,
                'catatan' => 'Harap kontrol 1 minggu',
                'apoteker_id' => $apotekerId,
                'waktu_verifikasi' => now()->subDays(3),
                'items' => [
                    ['produk_index' => 3, 'dosis' => '250mg', 'frekuensi' => '2x sehari', 'durasi' => '7 hari', 'cara_pakai' => 'Setelah makan', 'jumlah_resep' => 14, 'jumlah_diberikan' => 14, 'harga_satuan' => 3500, 'instruksi' => 'Antibiotik -habiskan obat'],
                    ['produk_index' => 4, 'dosis' => '5ml', 'frekuensi' => '3x sehari', 'durasi' => '7 hari', 'cara_pakai' => 'Sesudah makan', 'jumlah_resep' => 105, 'jumlah_diberikan' => 105, 'harga_satuan' => 500, 'instruksi' => 'Sirup obat batuk'],
                    ['produk_index' => 5, 'dosis' => '1 tablet', 'frekuensi' => 'Jika hidung tersumbat', 'durasi' => '7 hari', 'cara_pakai' => 'Jika diperlukan', 'jumlah_resep' => 7, 'jumlah_diberikan' => 7, 'harga_satuan' => 1500, 'instruksi' => 'Tidak boleh lebih dari 3x sehari'],
                    ['produk_index' => 6, 'dosis' => '100mg', 'frekuensi' => '1x sehari', 'durasi' => '7 hari', 'cara_pakai' => 'Malam sebelum tidur', 'jumlah_resep' => 7, 'jumlah_diberikan' => 0, 'harga_satuan' => 4000, 'instruksi' => 'Stok habis - diambil kemudian'],
                ]
            ],
            [
                'nomor_resep' => 'RSP-2026-0003',
                'tanggal_resep' => now()->subDays(1)->format('Y-m-d'),
                'pelanggan_index' => 6,
                'dokter_index' => 0,
                'diagnosa' => 'Sakit kepala dan mual',
                'status' => 'PENDING',
                'total_item' => 2,
                'total_harga' => 45000,
                'catatan' => 'Resep baru',
                'apoteker_id' => null,
                'waktu_verifikasi' => null,
                'items' => [
                    ['produk_index' => 7, 'dosis' => '400mg', 'frekuensi' => 'Jika sakit kepala', 'durasi' => '3 hari', 'cara_pakai' => 'Jika diperlukan', 'jumlah_resep' => 9, 'jumlah_diberikan' => 0, 'harga_satuan' => 2000, 'instruksi' => 'Maksimal 3x sehari'],
                    ['produk_index' => 8, 'dosis' => '1 tablet', 'frekuensi' => 'Jika mual', 'durasi' => '3 hari', 'cara_pakai' => 'Sebelum makan', 'jumlah_resep' => 6, 'jumlah_diberikan' => 0, 'harga_satuan' => 2500, 'instruksi' => 'Diminum 30 menit sebelum makan'],
                ]
            ],
            [
                'nomor_resep' => 'RSP-2026-0004',
                'tanggal_resep' => now()->subDays(7)->format('Y-m-d'),
                'pelanggan_index' => 9,
                'dokter_index' => 1,
                'diagnosa' => 'Diabetes melitus type 2',
                'status' => 'SELESAI',
                'total_item' => 3,
                'total_harga' => 285000,
                'catatan' => 'Kontrol bulan depan',
                'apoteker_id' => $apotekerId,
                'waktu_verifikasi' => now()->subDays(7),
                'items' => [
                    ['produk_index' => 9, 'dosis' => '500mg', 'frekuensi' => '2x sehari', 'durasi' => '30 hari', 'cara_pakai' => 'Sesudah makan', 'jumlah_resep' => 60, 'jumlah_diberikan' => 60, 'harga_satuan' => 2500, 'instruksi' => 'Obat diabetes rutin'],
                    ['produk_index' => 10, 'dosis' => '80mg', 'frekuensi' => '1x sehari', 'durasi' => '30 hari', 'cara_pakai' => 'Pagi hari', 'jumlah_resep' => 30, 'jumlah_diberikan' => 30, 'harga_satuan' => 3500, 'instruksi' => 'Obat darah tinggi'],
                    ['produk_index' => 11, 'dosis' => '10mg', 'frekuensi' => '1x sehari', 'durasi' => '30 hari', 'cara_pakai' => 'Malam sebelum tidur', 'jumlah_resep' => 30, 'jumlah_diberikan' => 30, 'harga_satuan' => 4000, 'instruksi' => 'Statin untuk kolesterol'],
                ]
            ],
            [
                'nomor_resep' => 'RSP-2026-0005',
                'tanggal_resep' => now()->subDays(2)->format('Y-m-d'),
                'pelanggan_index' => 4,
                'dokter_index' => 0,
                'diagnosa' => 'Alergi dingin',
                'status' => 'BATAL',
                'total_item' => 2,
                'total_harga' => 0,
                'catatan' => 'Pasien tidak jadi mengambil',
                'apoteker_id' => null,
                'waktu_verifikasi' => null,
                'items' => [
                    ['produk_index' => 12, 'dosis' => '10mg', 'frekuensi' => '1x sehari', 'durasi' => '7 hari', 'cara_pakai' => 'Malam', 'jumlah_resep' => 7, 'jumlah_diberikan' => 0, 'harga_satuan' => 1500, 'instruksi' => 'Antihistamin'],
                    ['produk_index' => 13, 'dosis' => '0.05%', 'frekuensi' => '3x sehari', 'durasi' => '7 hari', 'cara_pakai' => 'Teteskan ke mata', 'jumlah_resep' => 1, 'jumlah_diberikan' => 0, 'harga_satuan' => 12000, 'instruksi' => 'Tetes mata untuk iritasi'],
                ]
            ],
        ];

        foreach ($reseps as $resep) {
            $pelangganId = $pelangganIds[$resep['pelanggan_index']] ?? $pelangganIds[0];
            $dokterId = $dokterIds[$resep['dokter_index']] ?? $dokterIds[0];
            $items = $resep['items'];
            unset($resep['pelanggan_index'], $resep['dokter_index'], $resep['items']);

            $resepId = DB::table('resep')->updateOrInsert(
                ['nomor_resep' => $resep['nomor_resep']],
                array_merge($resep, [
                    'pelanggan_id' => $pelangganId,
                    'dokter_id' => $dokterId,
                    'dibuat_oleh' => $adminId,
                    'diubah_oleh' => $adminId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );

            $resepId = DB::table('resep')->where('nomor_resep', $resep['nomor_resep'])->value('id');

            foreach ($items as $item) {
                $produkIndex = $item['produk_index'];
                $produkId = $produkIds[$produkIndex] ?? $produkIds[0];
                unset($item['produk_index']);

                DB::table('detail_resep')->updateOrInsert(
                    [
                        'resep_id' => $resepId,
                        'produk_id' => $produkId,
                    ],
                    array_merge($item, [
                        'total' => $item['jumlah_diberikan'] * $item['harga_satuan'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ])
                );
            }
        }
    }
}
