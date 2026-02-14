<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DokterSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $adminId = DB::table('pengguna')->where('username', 'admin')->value('id');

        $dokter = [
            ['kode' => 'DR-0001', 'nama' => 'dr. Arif Hidayat', 'spesialisasi' => 'Penyakit Dalam', 'telepon' => '081400000001'],
            ['kode' => 'DR-0002', 'nama' => 'dr. Lestari Putri', 'spesialisasi' => 'Anak', 'telepon' => '081400000002'],
        ];

        foreach ($dokter as $item) {
            DB::table('dokter')->updateOrInsert(
                ['kode' => $item['kode']],
                [
                    'nama' => $item['nama'],
                    'spesialisasi' => $item['spesialisasi'],
                    'nomor_sip' => 'SIP-' . $item['kode'],
                    'telepon' => $item['telepon'],
                    'email' => strtolower(str_replace(' ', '.', $item['nama'])) . '@klinik.id',
                    'rumah_sakit' => 'Klinik Sehat Medika',
                    'alamat' => 'Alamat dokter ' . $item['kode'],
                    'status_aktif' => true,
                    'catatan' => 'Dokter rekanan',
                    'dibuat_oleh' => $adminId,
                    'diubah_oleh' => $adminId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
