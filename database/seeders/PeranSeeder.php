<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeranSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $roles = [
            ['nama' => 'Admin', 'keterangan' => 'Administrator Sistem'],
            ['nama' => 'Apoteker', 'keterangan' => 'Apoteker'],
            ['nama' => 'Kasir', 'keterangan' => 'Kasir'],
            ['nama' => 'Gudang', 'keterangan' => 'Staf Gudang'],
        ];

        foreach ($roles as $role) {
            DB::table('peran')->updateOrInsert(
                ['nama' => $role['nama']],
                [
                    'keterangan' => $role['keterangan'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
