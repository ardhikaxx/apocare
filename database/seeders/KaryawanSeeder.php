<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KaryawanSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $adminId = DB::table('pengguna')->where('username', 'admin')->value('id');
        $users = DB::table('pengguna')
            ->whereIn('username', ['apoteker', 'kasir', 'gudang'])
            ->get()
            ->keyBy('username');

        if ($users->isEmpty()) {
            return;
        }

        $items = [
            [
                'username' => 'apoteker',
                'nomor_karyawan' => 'KRY-0001',
                'jabatan' => 'Apoteker',
                'departemen' => 'Farmasi',
                'status_kepegawaian' => 'TETAP',
                'tanggal_bergabung' => $now->copy()->subYears(3)->toDateString(),
            ],
            [
                'username' => 'kasir',
                'nomor_karyawan' => 'KRY-0002',
                'jabatan' => 'Kasir',
                'departemen' => 'Front Office',
                'status_kepegawaian' => 'KONTRAK',
                'tanggal_bergabung' => $now->copy()->subYears(2)->toDateString(),
            ],
            [
                'username' => 'gudang',
                'nomor_karyawan' => 'KRY-0003',
                'jabatan' => 'Staff Gudang',
                'departemen' => 'Gudang',
                'status_kepegawaian' => 'TETAP',
                'tanggal_bergabung' => $now->copy()->subYears(4)->toDateString(),
            ],
        ];

        foreach ($items as $item) {
            $user = $users->get($item['username']);
            if (!$user) {
                continue;
            }

            DB::table('karyawan')->updateOrInsert(
                ['pengguna_id' => $user->id],
                [
                    'nomor_karyawan' => $item['nomor_karyawan'],
                    'nomor_identitas' => 'ID-' . $item['nomor_karyawan'],
                    'jabatan' => $item['jabatan'],
                    'departemen' => $item['departemen'],
                    'status_kepegawaian' => $item['status_kepegawaian'],
                    'tanggal_bergabung' => $item['tanggal_bergabung'],
                    'status_aktif' => true,
                    'catatan' => 'Data karyawan seeding',
                    'dibuat_oleh' => $adminId,
                    'diubah_oleh' => $adminId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
