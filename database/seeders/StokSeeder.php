<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StokSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('stok_produk')->count() > 0) {
            return;
        }

        $now = now();
        $adminId = DB::table('pengguna')->where('username', 'admin')->value('id');
        $pemasokId = DB::table('pemasok')->orderBy('id')->value('id');

        $produkList = DB::table('produk')->orderBy('id')->get();
        if ($produkList->isEmpty()) {
            return;
        }

        $index = 0;
        foreach ($produkList as $produk) {
            $index++;
            $hargaBeli = (float) ($produk->harga_beli ?? 0);
            $jumlah = 150 + ($index % 10) * 10;
            $kadaluarsa = $now->copy()->addMonths(12 + ($index % 12))->toDateString();

            DB::table('stok_produk')->insert([
                'produk_id' => $produk->id,
                'jumlah' => $jumlah,
                'jumlah_reservasi' => 0,
                'jumlah_tersedia' => $jumlah,
                'harga_beli_terakhir' => $hargaBeli,
                'harga_beli_rata' => $hargaBeli,
                'terakhir_diubah' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $batchId = DB::table('batch_produk')->insertGetId([
                'produk_id' => $produk->id,
                'nomor_batch' => 'INIT-' . $produk->kode,
                'tanggal_produksi' => $now->copy()->subMonths(2)->toDateString(),
                'tanggal_kadaluarsa' => $kadaluarsa,
                'jumlah' => $jumlah,
                'harga_beli' => $hargaBeli,
                'pemasok_id' => $pemasokId,
                'pembelian_id' => null,
                'sudah_kadaluarsa' => false,
                'catatan' => 'Stok awal',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('pergerakan_stok')->insert([
                'produk_id' => $produk->id,
                'batch_id' => $batchId,
                'jenis_pergerakan' => 'MASUK',
                'tipe_referensi' => 'StokAwal',
                'id_referensi' => null,
                'jumlah' => $jumlah,
                'jumlah_sebelum' => 0,
                'jumlah_sesudah' => $jumlah,
                'harga_satuan' => $hargaBeli,
                'catatan' => 'Stok awal',
                'dibuat_oleh' => $adminId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
