<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OpnameSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('stok_opname')->count() > 0) {
            return;
        }

        $now = now();
        $adminId = DB::table('pengguna')->where('username', 'admin')->value('id');

        $stok = DB::table('stok_produk')->orderBy('id', 'desc')->first();
        if (!$stok) {
            return;
        }

        $produkId = $stok->produk_id;
        $batch = DB::table('batch_produk')->where('produk_id', $produkId)->orderBy('id')->first();
        $batchId = $batch ? (int) $batch->id : null;
        $jumlahSistem = (float) $stok->jumlah;
        $jumlahHitung = $jumlahSistem + 2;
        $selisih = $jumlahHitung - $jumlahSistem;
        $harga = (float) (DB::table('produk')->where('id', $produkId)->value('harga_beli') ?? 0);
        $totalNilai = $selisih * $harga;

        $statusItem = $selisih > 0 ? 'LEBIH' : ($selisih < 0 ? 'KURANG' : 'COCOK');

        $opnameId = DB::table('stok_opname')->insertGetId([
            'nomor_opname' => 'OP' . $now->format('Ymd') . '01',
            'tanggal_opname' => $now->copy()->subDays(2)->toDateString(),
            'status' => 'DISETUJUI',
            'kategori_id' => DB::table('produk')->where('id', $produkId)->value('kategori_id'),
            'total_item_dihitung' => 1,
            'total_item_cocok' => $selisih == 0 ? 1 : 0,
            'total_item_selisih' => $selisih == 0 ? 0 : 1,
            'total_nilai_selisih' => $totalNilai,
            'catatan' => 'Stok opname seeding',
            'disetujui_oleh' => $adminId,
            'waktu_persetujuan' => $now,
            'dibuat_oleh' => $adminId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('detail_stok_opname')->insert([
            'opname_id' => $opnameId,
            'produk_id' => $produkId,
            'batch_id' => $batchId,
            'jumlah_sistem' => $jumlahSistem,
            'jumlah_hitung' => $jumlahHitung,
            'selisih' => $selisih,
            'harga_satuan' => $harga,
            'total_nilai_selisih' => $totalNilai,
            'status' => $statusItem,
            'dihitung_oleh' => $adminId,
            'waktu_hitung' => $now,
            'catatan' => 'Hasil opname',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        if ($selisih != 0) {
            $jumlahSebelum = $jumlahSistem;
            $jumlahSesudah = $jumlahSistem + $selisih;

            DB::table('stok_produk')->where('id', $stok->id)->update([
                'jumlah' => $jumlahSesudah,
                'jumlah_tersedia' => $jumlahSesudah - (float) $stok->jumlah_reservasi,
                'terakhir_diubah' => $now,
                'updated_at' => $now,
            ]);

            if ($batchId) {
                DB::table('batch_produk')->where('id', $batchId)->update([
                    'jumlah' => max(0, (float) $batch->jumlah + $selisih),
                    'updated_at' => $now,
                ]);
            }

            DB::table('pergerakan_stok')->insert([
                'produk_id' => $produkId,
                'batch_id' => $batchId,
                'jenis_pergerakan' => 'PENYESUAIAN',
                'tipe_referensi' => 'StokOpname',
                'id_referensi' => $opnameId,
                'jumlah' => abs($selisih),
                'jumlah_sebelum' => $jumlahSebelum,
                'jumlah_sesudah' => $jumlahSesudah,
                'harga_satuan' => $harga,
                'catatan' => 'Stok opname (seeding)',
                'dibuat_oleh' => $adminId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
