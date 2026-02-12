<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('peran')->insert([
            ['id' => 1, 'nama' => 'Admin', 'keterangan' => 'Administrator Sistem'],
            ['id' => 2, 'nama' => 'Apoteker', 'keterangan' => 'Apoteker'],
        ]);

        DB::table('pengguna')->insert([
            'nama' => 'Administrator',
            'email' => 'admin@apocare.com',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role_id' => 1,
            'status_aktif' => true,
            'created_at' => now(),
        ]);

        DB::table('satuan')->insert([
            ['kode' => 'PCS', 'nama' => 'Pieces'],
            ['kode' => 'BOX', 'nama' => 'Box'],
            ['kode' => 'STRIP', 'nama' => 'Strip'],
            ['kode' => 'BOTOL', 'nama' => 'Botol'],
        ]);

        DB::table('kategori')->insert([
            ['kode' => 'OBT', 'nama' => 'Obat-obatan'],
            ['kode' => 'VIT', 'nama' => 'Vitamin'],
            ['kode' => 'ALKES', 'nama' => 'Alat Kesehatan'],
            ['kode' => 'KOS', 'nama' => 'Kosmetik'],
        ]);
    }
}
