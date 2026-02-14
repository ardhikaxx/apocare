<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReturSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $adminId = DB::table('pengguna')->where('username', 'admin')->value('id');

        if (DB::table('retur_pembelian')->count() === 0) {
            $detailPembelian = DB::table('detail_pembelian')
                ->where('jumlah_terima', '>', 0)
                ->orderBy('id')
                ->first();

            if ($detailPembelian) {
                $pembelian = DB::table('pembelian')->where('id', $detailPembelian->pembelian_id)->first();
                if ($pembelian) {
                    $jumlah = min(5, (float) $detailPembelian->jumlah_terima);
                    $harga = (float) $detailPembelian->harga_satuan;
                    $pajakPersen = (float) $detailPembelian->persentase_pajak;
                    $lineSubtotal = $jumlah * $harga;
                    $linePajak = $lineSubtotal * ($pajakPersen / 100);
                    $lineTotal = $lineSubtotal + $linePajak;

                    $returId = DB::table('retur_pembelian')->insertGetId([
                        'nomor_retur' => 'RB' . $now->format('Ymd') . '01',
                        'pembelian_id' => $pembelian->id,
                        'pemasok_id' => $pembelian->pemasok_id,
                        'tanggal_retur' => $now->copy()->subDays(1)->toDateString(),
                        'alasan' => 'Barang rusak',
                        'status' => 'DISETUJUI',
                        'subtotal' => $lineSubtotal,
                        'jumlah_pajak' => $linePajak,
                        'total' => $lineTotal,
                        'metode_refund' => 'NOTA_KREDIT',
                        'jumlah_refund' => $lineTotal,
                        'catatan' => 'Retur pembelian seeding',
                        'dibuat_oleh' => $adminId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    $batch = DB::table('batch_produk')
                        ->where('pembelian_id', $pembelian->id)
                        ->where('produk_id', $detailPembelian->produk_id)
                        ->where('nomor_batch', $detailPembelian->nomor_batch)
                        ->first();
                    $batchId = $batch ? (int) $batch->id : null;

                    DB::table('detail_retur_pembelian')->insert([
                        'retur_id' => $returId,
                        'detail_pembelian_id' => $detailPembelian->id,
                        'produk_id' => $detailPembelian->produk_id,
                        'batch_id' => $batchId,
                        'jumlah' => $jumlah,
                        'harga_satuan' => $harga,
                        'persentase_pajak' => $pajakPersen,
                        'jumlah_pajak' => $linePajak,
                        'subtotal' => $lineSubtotal,
                        'total' => $lineTotal,
                        'alasan' => 'Barang rusak',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    $stok = DB::table('stok_produk')->where('produk_id', $detailPembelian->produk_id)->first();
                    if ($stok) {
                        $jumlahSebelum = (float) $stok->jumlah;
                        $jumlahSesudah = max(0, $jumlahSebelum - $jumlah);

                        DB::table('stok_produk')->where('produk_id', $detailPembelian->produk_id)->update([
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
                            'produk_id' => $detailPembelian->produk_id,
                            'batch_id' => $batchId,
                            'jenis_pergerakan' => 'RETUR',
                            'tipe_referensi' => 'ReturPembelian',
                            'id_referensi' => $returId,
                            'jumlah' => $jumlah,
                            'jumlah_sebelum' => $jumlahSebelum,
                            'jumlah_sesudah' => $jumlahSesudah,
                            'harga_satuan' => $harga,
                            'catatan' => 'Retur pembelian (seeding)',
                            'dibuat_oleh' => $adminId,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    }
                }
            }
        }

        if (DB::table('retur_penjualan')->count() === 0) {
            $detailPenjualan = DB::table('detail_penjualan')->orderBy('id')->first();
            if ($detailPenjualan) {
                $penjualan = DB::table('penjualan')->where('id', $detailPenjualan->penjualan_id)->first();
                if ($penjualan) {
                    $jumlah = min(1, (float) $detailPenjualan->jumlah);
                    $harga = (float) $detailPenjualan->harga_satuan;
                    $pajakPersen = (float) $detailPenjualan->persentase_pajak;
                    $lineSubtotal = $jumlah * $harga;
                    $linePajak = $lineSubtotal * ($pajakPersen / 100);
                    $lineTotal = $lineSubtotal + $linePajak;

                    $returId = DB::table('retur_penjualan')->insertGetId([
                        'nomor_retur' => 'RJ' . $now->format('Ymd') . '01',
                        'penjualan_id' => $penjualan->id,
                        'pelanggan_id' => $penjualan->pelanggan_id,
                        'tanggal_retur' => $now->copy()->subDays(1),
                        'alasan' => 'Kesalahan pembelian',
                        'status' => 'DISETUJUI',
                        'subtotal' => $lineSubtotal,
                        'jumlah_pajak' => $linePajak,
                        'total' => $lineTotal,
                        'metode_refund' => 'TUNAI',
                        'jumlah_refund' => $lineTotal,
                        'catatan' => 'Retur penjualan seeding',
                        'disetujui_oleh' => $adminId,
                        'waktu_persetujuan' => $now,
                        'dibuat_oleh' => $adminId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    DB::table('detail_retur_penjualan')->insert([
                        'retur_id' => $returId,
                        'detail_penjualan_id' => $detailPenjualan->id,
                        'produk_id' => $detailPenjualan->produk_id,
                        'batch_id' => $detailPenjualan->batch_id,
                        'jumlah' => $jumlah,
                        'harga_satuan' => $harga,
                        'persentase_pajak' => $pajakPersen,
                        'jumlah_pajak' => $linePajak,
                        'subtotal' => $lineSubtotal,
                        'total' => $lineTotal,
                        'alasan' => 'Kesalahan pembelian',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    $stok = DB::table('stok_produk')->where('produk_id', $detailPenjualan->produk_id)->first();
                    if ($stok) {
                        $jumlahSebelum = (float) $stok->jumlah;
                        $jumlahSesudah = $jumlahSebelum + $jumlah;

                        DB::table('stok_produk')->where('produk_id', $detailPenjualan->produk_id)->update([
                            'jumlah' => $jumlahSesudah,
                            'jumlah_tersedia' => $jumlahSesudah - (float) $stok->jumlah_reservasi,
                            'terakhir_diubah' => $now,
                            'updated_at' => $now,
                        ]);

                        if ($detailPenjualan->batch_id) {
                            $batch = DB::table('batch_produk')->where('id', $detailPenjualan->batch_id)->first();
                            if ($batch) {
                                DB::table('batch_produk')->where('id', $detailPenjualan->batch_id)->update([
                                    'jumlah' => (float) $batch->jumlah + $jumlah,
                                    'updated_at' => $now,
                                ]);
                            }
                        }

                        DB::table('pergerakan_stok')->insert([
                            'produk_id' => $detailPenjualan->produk_id,
                            'batch_id' => $detailPenjualan->batch_id,
                            'jenis_pergerakan' => 'RETUR',
                            'tipe_referensi' => 'ReturPenjualan',
                            'id_referensi' => $returId,
                            'jumlah' => $jumlah,
                            'jumlah_sebelum' => $jumlahSebelum,
                            'jumlah_sesudah' => $jumlahSesudah,
                            'harga_satuan' => $harga,
                            'catatan' => 'Retur penjualan (seeding)',
                            'dibuat_oleh' => $adminId,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    }
                }
            }
        }
    }
}
