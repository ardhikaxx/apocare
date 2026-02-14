<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PeranSeeder::class,
            HakAksesSeeder::class,
            PenggunaSeeder::class,
            KaryawanSeeder::class,
            MasterDataSeeder::class,
            PelangganSeeder::class,
            DokterSeeder::class,
            StokSeeder::class,
            PembelianSeeder::class,
            PenjualanSeeder::class,
            ReturSeeder::class,
            PenyesuaianSeeder::class,
            OpnameSeeder::class,
            ResepSeeder::class,
        ]);
    }
}
