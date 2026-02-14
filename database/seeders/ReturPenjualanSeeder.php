<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReturPenjualanSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $adminId = DB::table('pengguna')->where('username', 'admin')->value('id');

        $penjualanIds = DB::table('penjualan')
            ->where('status_pembayaran', 'LUNAS')
            ->pluck('id');

        if ($penjualanIds->isEmpty()) {
            $this->command->warn('Tidak ada penjualan yang selesai. Skip seeding retur penjualan.');
            return;
        }

        $details = DB::table('detail_penjualan')
            ->whereIn('penjualan_id', $penjualanIds)
            ->get();

        if ($details->isEmpty()) {
            $this->command->warn('Tidak ada detail penjualan. Skip seeding retur penjualan.');
            return;
        }

        $penjualanMap = DB::table('penjualan')
            ->whereIn('id', $penjualanIds)
            ->get()->keyBy('id');

        $returs = [
            [
                'tanggal_retur' => $now->copy()->subDays(14)->toDateString(),
                'alasan' => 'Obat tidak sesuai resep',
                'status' => 'DISETUJUI',
                'metode_refund' => 'TUNAI',
                'jumlah_retur' => 1,
            ],
            [
                'tanggal_retur' => $now->copy()->subDays(11)->toDateString(),
                'alasan' => 'Expired terlalu dekat',
                'status' => 'DISETUJUI',
                'metode_refund' => 'TRANSFER',
                'jumlah_retur' => 2,
            ],
            [
                'tanggal_retur' => $now->copy()->subDays(9)->toDateString(),
                'alasan' => 'Kesalahan pasien',
                'status' => 'DISETUJUI',
                'metode_refund' => 'TUNAI',
                'jumlah_retur' => 1,
            ],
            [
                'tanggal_retur' => $now->copy()->subDays(7)->toDateString(),
                'alasan' => 'Kemasan rusak',
                'status' => 'PENDING',
                'metode_refund' => 'TRANSFER',
                'jumlah_retur' => 1,
            ],
            [
                'tanggal_retur' => $now->copy()->subDays(5)->toDateString(),
                'alasan' => 'Sudah diminum tapi tidak cocok',
                'status' => 'DITOLAK',
                'metode_refund' => null,
                'jumlah_retur' => 3,
            ],
            [
                'tanggal_retur' => $now->copy()->subDays(3)->toDateString(),
                'alasan' => 'Obat expired',
                'status' => 'DISETUJUI',
                'metode_refund' => 'TUNAI',
                'jumlah_retur' => 2,
            ],
            [
                'tanggal_retur' => $now->copy()->subDays(2)->toDateString(),
                'alasan' => 'Salah ambil obat',
                'status' => 'DISETUJUI',
                'metode_refund' => 'TRANSFER',
                'jumlah_retur' => 1,
            ],
            [
                'tanggal_retur' => $now->copy()->subDays(1)->toDateString(),
                'alasan' => 'Dosis tidak sesuai',
                'status' => 'PENDING',
                'metode_refund' => 'TRANSFER',
                'jumlah_retur' => 1,
            ],
        ];

        $nomor = 1;
        foreach ($details as $detail) {
            if ($nomor > 8) break;

            $retur = $returs[$nomor - 1];
            $jumlah = min($retur['jumlah_retur'], (float) $detail->jumlah);
            if ($jumlah <= 0) continue;

            $penjualan = $penjualanMap->get($detail->penjualan_id);
            if (!$penjualan) continue;

            $harga = (float) $detail->harga_satuan;
            $pajakPersen = (float) $detail->persentase_pajak;
            $lineSubtotal = $jumlah * $harga;
            $linePajak = $lineSubtotal * ($pajakPersen / 100);
            $lineTotal = $lineSubtotal + $linePajak;

            $refund = $retur['status'] === 'DISETUJUI' ? $lineTotal : 0;

            $returId = DB::table('retur_penjualan')->insertGetId([
                'nomor_retur' => 'RJ' . $now->format('Ymd') . str_pad($nomor, 2, '0', STR_PAD_LEFT),
                'penjualan_id' => $detail->penjualan_id,
                'pelanggan_id' => $penjualan->pelanggan_id,
                'tanggal_retur' => $retur['tanggal_retur'],
                'alasan' => $retur['alasan'],
                'status' => $retur['status'],
                'subtotal' => $lineSubtotal,
                'jumlah_pajak' => $linePajak,
                'total' => $lineTotal,
                'metode_refund' => $retur['metode_refund'],
                'jumlah_refund' => $refund,
                'catatan' => 'Retur penjualan seeding #' . $nomor,
                'disetujui_oleh' => $retur['status'] === 'DISETUJUI' ? $adminId : null,
                'waktu_persetujuan' => $retur['status'] === 'DISETUJUI' ? $now : null,
                'dibuat_oleh' => $adminId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('detail_retur_penjualan')->insert([
                'retur_id' => $returId,
                'detail_penjualan_id' => $detail->id,
                'produk_id' => $detail->produk_id,
                'batch_id' => $detail->batch_id,
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
                    $jumlahSesudah = $jumlahSebelum + $jumlah;

                    DB::table('stok_produk')->where('produk_id', $detail->produk_id)->update([
                        'jumlah' => $jumlahSesudah,
                        'jumlah_tersedia' => $jumlahSesudah - (float) $stok->jumlah_reservasi,
                        'terakhir_diubah' => $now,
                        'updated_at' => $now,
                    ]);

                    if ($detail->batch_id) {
                        $batch = DB::table('batch_produk')->where('id', $detail->batch_id)->first();
                        if ($batch) {
                            DB::table('batch_produk')->where('id', $detail->batch_id)->update([
                                'jumlah' => (float) $batch->jumlah + $jumlah,
                                'updated_at' => $now,
                            ]);
                        }
                    }

                    DB::table('pergerakan_stok')->insert([
                        'produk_id' => $detail->produk_id,
                        'batch_id' => $detail->batch_id,
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

            $nomor++;
        }
    }
}
