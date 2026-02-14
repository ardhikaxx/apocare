<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('penjualan')->count() > 0) {
            return;
        }

        $now = now();
        $adminId = DB::table('pengguna')->where('username', 'admin')->value('id');
        $produkList = DB::table('produk')->orderBy('id')->get()->values();
        $pelangganIds = DB::table('pelanggan')->orderBy('id')->pluck('id')->values();

        if ($produkList->isEmpty()) {
            return;
        }

        $pelangganPool = [];
        foreach ($pelangganIds as $id) {
            $pelangganPool[] = $id;
            $pelangganPool[] = $id; // maksimal 2 transaksi per pelanggan
        }

        $totalTransaksi = 15;
        $metodeList = ['TUNAI', 'QRIS', 'DEBIT', 'TRANSFER', 'EWALLET'];

        for ($i = 1; $i <= $totalTransaksi; $i++) {
            $pelangganId = $i <= count($pelangganPool) ? $pelangganPool[$i - 1] : null;
            $tanggal = $now->copy()->subDays($totalTransaksi - $i + 1)->setTime(10 + ($i % 8), 15, 0);
            $metode = $metodeList[$i % count($metodeList)];
            $itemsCount = 2 + ($i % 2);

            $subtotal = 0;
            $diskonItem = 0;
            $pajakItem = 0;
            $detailLines = [];

            for ($j = 0; $j < $itemsCount; $j++) {
                $index = (($i - 1) * 3 + $j) % $produkList->count();
                $product = $produkList[$index];

                $stok = DB::table('stok_produk')->where('produk_id', $product->id)->first();
                $available = $stok ? (float) $stok->jumlah : 0;
                if ($available <= 0) {
                    continue;
                }

                $jumlah = min(3 + ($j % 3), $available);
                $harga = (float) $product->harga_jual;
                $diskonPersen = ($i % 4 === 0) ? 5 : 0;
                $pajakPersen = 11;

                $lineSubtotal = $jumlah * $harga;
                $lineDiskon = $lineSubtotal * ($diskonPersen / 100);
                $linePajak = ($lineSubtotal - $lineDiskon) * ($pajakPersen / 100);

                $subtotal += $lineSubtotal;
                $diskonItem += $lineDiskon;
                $pajakItem += $linePajak;

                $detailLines[] = [
                    'produk_id' => (int) $product->id,
                    'jumlah' => $jumlah,
                    'harga' => $harga,
                    'diskon' => $diskonPersen,
                    'pajak' => $pajakPersen,
                ];
            }

            if (empty($detailLines)) {
                continue;
            }

            $totalAkhir = ($subtotal - $diskonItem) + $pajakItem;
            $jumlahBayar = $totalAkhir;
            $statusPembayaran = 'LUNAS';

            $nomor = 'SL' . $tanggal->format('Ymd') . str_pad($i, 3, '0', STR_PAD_LEFT);

            $penjualanId = DB::table('penjualan')->insertGetId([
                'nomor_penjualan' => $nomor,
                'pelanggan_id' => $pelangganId,
                'tanggal_penjualan' => $tanggal,
                'jenis_penjualan' => $pelangganId ? 'RETAIL' : 'ONLINE',
                'status_pembayaran' => $statusPembayaran,
                'metode_pembayaran' => $metode,
                'subtotal' => $subtotal,
                'jenis_diskon' => 'PERSENTASE',
                'nilai_diskon' => 0,
                'jumlah_diskon' => $diskonItem,
                'jumlah_pajak' => $pajakItem,
                'total_akhir' => $totalAkhir,
                'jumlah_bayar' => $jumlahBayar,
                'jumlah_kembalian' => 0,
                'catatan' => 'Penjualan seeding',
                'dilayani_oleh' => $adminId,
                'dibuat_oleh' => $adminId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            foreach ($detailLines as $line) {
                $produkId = $line['produk_id'];
                $jumlah = (float) $line['jumlah'];
                $harga = (float) $line['harga'];
                $diskonPersen = (float) $line['diskon'];
                $pajakPersen = (float) $line['pajak'];

                $batch = DB::table('batch_produk')
                    ->where('produk_id', $produkId)
                    ->where('jumlah', '>', 0)
                    ->orderBy('tanggal_kadaluarsa')
                    ->first();

                $batchId = $batch ? (int) $batch->id : null;

                $lineSubtotal = $jumlah * $harga;
                $lineDiskon = $lineSubtotal * ($diskonPersen / 100);
                $linePajak = ($lineSubtotal - $lineDiskon) * ($pajakPersen / 100);
                $lineTotal = $lineSubtotal - $lineDiskon + $linePajak;

                DB::table('detail_penjualan')->insert([
                    'penjualan_id' => $penjualanId,
                    'produk_id' => $produkId,
                    'satuan_produk_id' => DB::table('satuan_produk')->where('produk_id', $produkId)->where('default_penjualan', true)->value('id'),
                    'batch_id' => $batchId,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $harga,
                    'persentase_diskon' => $diskonPersen,
                    'jumlah_diskon' => $lineDiskon,
                    'persentase_pajak' => $pajakPersen,
                    'jumlah_pajak' => $linePajak,
                    'subtotal' => $lineSubtotal,
                    'total' => $lineTotal,
                    'catatan' => 'Detail penjualan seeding',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $stok = DB::table('stok_produk')->where('produk_id', $produkId)->first();
                if ($stok) {
                    $jumlahSebelum = (float) $stok->jumlah;
                    $jumlahSesudah = max(0, $jumlahSebelum - $jumlah);

                    DB::table('stok_produk')->where('produk_id', $produkId)->update([
                        'jumlah' => $jumlahSesudah,
                        'jumlah_tersedia' => $jumlahSesudah - (float) $stok->jumlah_reservasi,
                        'terakhir_diubah' => $now,
                        'updated_at' => $now,
                    ]);

                    if ($batchId) {
                        DB::table('batch_produk')->where('id', $batchId)->update([
                            'jumlah' => max(0, (float) $batch->jumlah - $jumlah),
                            'updated_at' => $now,
                        ]);
                    }

                    DB::table('pergerakan_stok')->insert([
                        'produk_id' => $produkId,
                        'batch_id' => $batchId,
                        'jenis_pergerakan' => 'KELUAR',
                        'tipe_referensi' => 'Penjualan',
                        'id_referensi' => $penjualanId,
                        'jumlah' => $jumlah,
                        'jumlah_sebelum' => $jumlahSebelum,
                        'jumlah_sesudah' => $jumlahSesudah,
                        'harga_satuan' => $harga,
                        'catatan' => 'Penjualan POS (seeding)',
                        'dibuat_oleh' => $adminId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }
    }
}
