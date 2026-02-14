<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenyesuaianSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('penyesuaian_stok')->count() > 0) {
            return;
        }

        $now = now();
        $adminId = DB::table('pengguna')->where('username', 'admin')->value('id');

        $stok = DB::table('stok_produk')->orderBy('id')->first();
        if (!$stok) {
            return;
        }

        $produkId = $stok->produk_id;
        $batch = DB::table('batch_produk')->where('produk_id', $produkId)->orderBy('id')->first();
        $batchId = $batch ? (int) $batch->id : null;
        $jumlahSistem = (float) $stok->jumlah;
        $jumlahAktual = $jumlahSistem > 5 ? $jumlahSistem - 3 : $jumlahSistem + 2;
        $selisih = $jumlahAktual - $jumlahSistem;
        $harga = (float) (DB::table('produk')->where('id', $produkId)->value('harga_beli') ?? 0);
        $totalNilai = $selisih * $harga;
        $jenisPenyesuaian = $selisih < 0 ? 'RUSAK' : 'PENAMBAHAN';

        $penyesuaianId = DB::table('penyesuaian_stok')->insertGetId([
            'nomor_penyesuaian' => 'ADJ' . $now->format('Ymd') . '01',
            'tanggal_penyesuaian' => $now->copy()->subDays(1)->toDateString(),
            'jenis_penyesuaian' => $jenisPenyesuaian,
            'status' => 'DISETUJUI',
            'total_item' => 1,
            'catatan' => 'Penyesuaian stok seeding',
            'disetujui_oleh' => $adminId,
            'waktu_persetujuan' => $now,
            'dibuat_oleh' => $adminId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('detail_penyesuaian_stok')->insert([
            'penyesuaian_id' => $penyesuaianId,
            'produk_id' => $produkId,
            'batch_id' => $batchId,
            'jumlah_sistem' => $jumlahSistem,
            'jumlah_aktual' => $jumlahAktual,
            'selisih' => $selisih,
            'harga_satuan' => $harga,
            'total_nilai' => $totalNilai,
            'catatan' => 'Barang rusak di rak',
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
                'jenis_pergerakan' => $jenisPenyesuaian === 'RUSAK' ? 'RUSAK' : 'PENYESUAIAN',
                'tipe_referensi' => 'PenyesuaianStok',
                'id_referensi' => $penyesuaianId,
                'jumlah' => abs($selisih),
                'jumlah_sebelum' => $jumlahSebelum,
                'jumlah_sesudah' => $jumlahSesudah,
                'harga_satuan' => $harga,
                'catatan' => 'Penyesuaian stok (seeding)',
                'dibuat_oleh' => $adminId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
