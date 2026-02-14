<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReturPembelianSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $adminId = DB::table('pengguna')->where('username', 'admin')->value('id');

        $pembelianIds = DB::table('pembelian')
            ->where('status', 'DITERIMA')
            ->pluck('id');

        if ($pembelianIds->isEmpty()) {
            $this->command->warn('Tidak ada pembelian yang diterima. Skip seeding retur pembelian.');
            return;
        }

        $details = DB::table('detail_pembelian')
            ->whereIn('pembelian_id', $pembelianIds)
            ->where('jumlah_terima', '>', 0)
            ->get();

        if ($details->isEmpty()) {
            $this->command->warn('Tidak ada detail pembelian. Skip seeding retur pembelian.');
            return;
        }

        $pembelianMap = DB::table('pembelian')
            ->whereIn('id', $pembelianIds)
            ->get()->keyBy('id');

        $returs = [
            [
                'tanggal_retur' => $now->copy()->subDays(15)->toDateString(),
                'alasan' => 'Barang rusak',
                'status' => 'DISETUJUI',
                'metode_refund' => 'NOTA_KREDIT',
                'jumlah_retur' => 5,
            ],
            [
                'tanggal_retur' => $now->copy()->subDays(12)->toDateString(),
                'alasan' => 'Kedaluwarsa',
                'status' => 'DISETUJUI',
                'metode_refund' => 'TUNAI',
                'jumlah_retur' => 10,
            ],
            [
                'tanggal_retur' => $now->copy()->subDays(10)->toDateString(),
                'alasan' => 'Salah pengiriman',
                'status' => 'DISETUJUI',
                'metode_refund' => 'NOTA_KREDIT',
                'jumlah_retur' => 3,
            ],
            [
                'tanggal_retur' => $now->copy()->subDays(8)->toDateString(),
                'alasan' => 'Barang tidak sesuai pesanan',
                'status' => 'PENDING',
                'metode_refund' => 'TRANSFER',
                'jumlah_retur' => 2,
            ],
            [
                'tanggal_retur' => $now->copy()->subDays(5)->toDateString(),
                'alasan' => 'Kemasan rusak',
                'status' => 'DISETUJUI',
                'metode_refund' => 'NOTA_KREDIT',
                'jumlah_retur' => 6,
            ],
            [
                'tanggal_retur' => $now->copy()->subDays(3)->toDateString(),
                'alasan' => 'Barang berkualitas rendah',
                'status' => 'DITOLAK',
                'metode_refund' => null,
                'jumlah_retur' => 4,
            ],
            [
                'tanggal_retur' => $now->copy()->subDays(2)->toDateString(),
                'alasan' => 'Kedaluwarsa',
                'status' => 'DISETUJUI',
                'metode_refund' => 'TUNAI',
                'jumlah_retur' => 8,
            ],
            [
                'tanggal_retur' => $now->copy()->subDays(1)->toDateString(),
                'alasan' => 'Salah produk',
                'status' => 'PENDING',
                'metode_refund' => 'TRANSFER',
                'jumlah_retur' => 3,
            ],
        ];

        $nomor = 1;
        foreach ($details as $detail) {
            if ($nomor > 8) break;
            
            $retur = $returs[$nomor - 1];
            $jumlah = min($retur['jumlah_retur'], (float) $detail->jumlah_terima);
            if ($jumlah <= 0) continue;

            $pembelian = $pembelianMap->get($detail->pembelian_id);
            if (!$pembelian) continue;

            $harga = (float) $detail->harga_satuan;
            $pajakPersen = (float) $detail->persentase_pajak;
            $lineSubtotal = $jumlah * $harga;
            $linePajak = $lineSubtotal * ($pajakPersen / 100);
            $lineTotal = $lineSubtotal + $linePajak;

            $refund = $retur['status'] === 'DISETUJUI' ? $lineTotal : 0;

            $returId = DB::table('retur_pembelian')->insertGetId([
                'nomor_retur' => 'RB' . $now->format('Ymd') . str_pad($nomor, 2, '0', STR_PAD_LEFT),
                'pembelian_id' => $detail->pembelian_id,
                'pemasok_id' => $pembelian->pemasok_id,
                'tanggal_retur' => $retur['tanggal_retur'],
                'alasan' => $retur['alasan'],
                'status' => $retur['status'],
                'subtotal' => $lineSubtotal,
                'jumlah_pajak' => $linePajak,
                'total' => $lineTotal,
                'metode_refund' => $retur['metode_refund'],
                'jumlah_refund' => $refund,
                'catatan' => 'Retur pembelian seeding #' . $nomor,
                'dibuat_oleh' => $adminId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $batch = DB::table('batch_produk')
                ->where('pembelian_id', $detail->pembelian_id)
                ->where('produk_id', $detail->produk_id)
                ->first();
            $batchId = $batch ? (int) $batch->id : null;

            DB::table('detail_retur_pembelian')->insert([
                'retur_id' => $returId,
                'detail_pembelian_id' => $detail->id,
                'produk_id' => $detail->produk_id,
                'batch_id' => $batchId,
                'jumlah' => $jumlah,
                'harga_satuan' => $harga,
                'persentase_pajak' => $pajakPersen,
                'jumlah_pajak' => $linePajak,
                'subtotal' => $lineSubtotal,
                'total' => $lineTotal,
                'alasan' => $retur['alasan'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            if ($retur['status'] === 'DISETUJUI') {
                $stok = DB::table('stok_produk')->where('produk_id', $detail->produk_id)->first();
                if ($stok) {
                    $jumlahSebelum = (float) $stok->jumlah;
                    $jumlahSesudah = max(0, $jumlahSebelum - $jumlah);

                    DB::table('stok_produk')->where('produk_id', $detail->produk_id)->update([
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
                        'produk_id' => $detail->produk_id,
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

            $nomor++;
        }
    }
}
