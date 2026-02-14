<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $roles = DB::table('peran')->pluck('id', 'nama');

        DB::table('pengguna')->updateOrInsert(
            ['username' => 'admin'],
            [
                'nama' => 'Administrator',
                'email' => 'admin@apocare.com',
                'password' => Hash::make('password'),
                'role_id' => $roles['Admin'] ?? null,
                'telepon' => '081200000001',
                'alamat' => 'Kantor Pusat',
                'status_aktif' => true,
                'login_terakhir' => $now->copy()->subDays(1),
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        $adminId = DB::table('pengguna')->where('username', 'admin')->value('id');

        $users = [
            [
                'username' => 'apoteker',
                'nama' => 'Sari Kurniawati',
                'email' => 'apoteker@apocare.com',
                'role' => 'Apoteker',
                'telepon' => '081200000002',
                'alamat' => 'Bandung',
            ],
            [
                'username' => 'kasir',
                'nama' => 'Rina Putri',
                'email' => 'kasir@apocare.com',
                'role' => 'Kasir',
                'telepon' => '081200000003',
                'alamat' => 'Jakarta',
            ],
            [
                'username' => 'gudang',
                'nama' => 'Budi Santoso',
                'email' => 'gudang@apocare.com',
                'role' => 'Gudang',
                'telepon' => '081200000004',
                'alamat' => 'Depok',
            ],
        ];

        foreach ($users as $user) {
            $roleId = $roles[$user['role']] ?? ($roles['Admin'] ?? null);

            DB::table('pengguna')->updateOrInsert(
                ['username' => $user['username']],
                [
                    'nama' => $user['nama'],
                    'email' => $user['email'],
                    'password' => Hash::make('password'),
                    'role_id' => $roleId,
                    'telepon' => $user['telepon'],
                    'alamat' => $user['alamat'],
                    'status_aktif' => true,
                    'dibuat_oleh' => $adminId,
                    'diubah_oleh' => $adminId,
                    'login_terakhir' => $now->copy()->subDays(2),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
