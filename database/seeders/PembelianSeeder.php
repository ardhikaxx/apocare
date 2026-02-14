<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PembelianSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('pembelian')->count() > 0) {
            return;
        }

        $now = now();
        $adminId = DB::table('pengguna')->where('username', 'admin')->value('id');
        $pemasok = DB::table('pemasok')->orderBy('id')->get()->values();
        $produk = DB::table('produk')->get()->keyBy('kode');

        if ($pemasok->isEmpty() || $produk->isEmpty()) {
            return;
        }

        $getSatuanProdukId = function (int $produkId): ?int {
            $row = DB::table('satuan_produk')->where('produk_id', $produkId)->where('default_pembelian', true)->first();
            if (!$row) {
                $row = DB::table('satuan_produk')->where('produk_id', $produkId)->first();
            }
            return $row ? (int) $row->id : null;
        };

        $purchases = [
            [
                'nomor' => 'PO' . $now->format('Ymd') . '001',
                'pemasok_id' => $pemasok[0]->id,
                'tanggal' => $now->copy()->subDays(30)->toDateString(),
                'jatuh_tempo' => $now->copy()->subDays(30)->addDays(30)->toDateString(),
                'status' => 'DITERIMA',
                'metode' => 'TRANSFER',
                'bayar_ratio' => 1.0,
                'items' => [
                    ['kode' => 'PRD-OBT-001', 'pesan' => 100, 'terima' => 100, 'harga' => 1400, 'pajak' => 11, 'batch' => 'PB-001-A'],
                    ['kode' => 'PRD-VIT-003', 'pesan' => 80, 'terima' => 80, 'harga' => 1700, 'pajak' => 11, 'batch' => 'PB-001-B'],
                ],
            ],
            [
                'nomor' => 'PO' . $now->format('Ymd') . '002',
                'pemasok_id' => $pemasok[1]->id ?? $pemasok[0]->id,
                'tanggal' => $now->copy()->subDays(25)->toDateString(),
                'jatuh_tempo' => $now->copy()->subDays(25)->addDays(30)->toDateString(),
                'status' => 'DITERIMA',
                'metode' => 'TRANSFER',
                'bayar_ratio' => 1.0,
                'items' => [
                    ['kode' => 'PRD-OBT-002', 'pesan' => 60, 'terima' => 60, 'harga' => 2300, 'pajak' => 11, 'batch' => 'PB-002-A'],
                    ['kode' => 'PRD-ALK-004', 'pesan' => 30, 'terima' => 30, 'harga' => 14000, 'pajak' => 11, 'batch' => 'PB-002-B'],
                ],
            ],
            [
                'nomor' => 'PO' . $now->format('Ymd') . '003',
                'pemasok_id' => $pemasok[2]->id ?? $pemasok[0]->id,
                'tanggal' => $now->copy()->subDays(20)->toDateString(),
                'jatuh_tempo' => $now->copy()->subDays(20)->addDays(14)->toDateString(),
                'status' => 'SEBAGIAN',
                'metode' => 'KREDIT',
                'bayar_ratio' => 0.5,
                'items' => [
                    ['kode' => 'PRD-OBT-003', 'pesan' => 50, 'terima' => 35, 'harga' => 1800, 'pajak' => 11, 'batch' => 'PB-003-A'],
                    ['kode' => 'PRD-VIT-001', 'pesan' => 40, 'terima' => 40, 'harga' => 2200, 'pajak' => 11, 'batch' => 'PB-003-B'],
                ],
            ],
            [
                'nomor' => 'PO' . $now->format('Ymd') . '004',
                'pemasok_id' => $pemasok[0]->id,
                'tanggal' => $now->copy()->subDays(15)->toDateString(),
                'jatuh_tempo' => $now->copy()->subDays(15)->addDays(30)->toDateString(),
                'status' => 'DITERIMA',
                'metode' => 'TUNAI',
                'bayar_ratio' => 1.0,
                'items' => [
                    ['kode' => 'PRD-ALK-001', 'pesan' => 25, 'terima' => 25, 'harga' => 8500, 'pajak' => 11, 'batch' => 'PB-004-A'],
                    ['kode' => 'PRD-ALK-002', 'pesan' => 25, 'terima' => 25, 'harga' => 9200, 'pajak' => 11, 'batch' => 'PB-004-B'],
                    ['kode' => 'PRD-ALK-003', 'pesan' => 20, 'terima' => 20, 'harga' => 11000, 'pajak' => 11, 'batch' => 'PB-004-C'],
                ],
            ],
            [
                'nomor' => 'PO' . $now->format('Ymd') . '005',
                'pemasok_id' => $pemasok[1]->id ?? $pemasok[0]->id,
                'tanggal' => $now->copy()->subDays(12)->toDateString(),
                'jatuh_tempo' => $now->copy()->subDays(12)->addDays(30)->toDateString(),
                'status' => 'DITERIMA',
                'metode' => 'TRANSFER',
                'bayar_ratio' => 1.0,
                'items' => [
                    ['kode' => 'PRD-VIT-002', 'pesan' => 60, 'terima' => 60, 'harga' => 2500, 'pajak' => 11, 'batch' => 'PB-005-A'],
                    ['kode' => 'PRD-VIT-004', 'pesan' => 45, 'terima' => 45, 'harga' => 3100, 'pajak' => 11, 'batch' => 'PB-005-B'],
                ],
            ],
            [
                'nomor' => 'PO' . $now->format('Ymd') . '006',
                'pemasok_id' => $pemasok[2]->id ?? $pemasok[0]->id,
                'tanggal' => $now->copy()->subDays(10)->toDateString(),
                'jatuh_tempo' => $now->copy()->subDays(10)->addDays(14)->toDateString(),
                'status' => 'DITERIMA',
                'metode' => 'TRANSFER',
                'bayar_ratio' => 1.0,
                'items' => [
                    ['kode' => 'PRD-OBT-004', 'pesan' => 80, 'terima' => 80, 'harga' => 1600, 'pajak' => 11, 'batch' => 'PB-006-A'],
                    ['kode' => 'PRD-OBT-005', 'pesan' => 70, 'terima' => 70, 'harga' => 1950, 'pajak' => 11, 'batch' => 'PB-006-B'],
                ],
            ],
            [
                'nomor' => 'PO' . $now->format('Ymd') . '007',
                'pemasok_id' => $pemasok[0]->id,
                'tanggal' => $now->copy()->subDays(8)->toDateString(),
                'jatuh_tempo' => $now->copy()->subDays(8)->addDays(30)->toDateString(),
                'status' => 'DRAFT',
                'metode' => 'KREDIT',
                'bayar_ratio' => 0.0,
                'items' => [
                    ['kode' => 'PRD-ALK-005', 'pesan' => 15, 'terima' => 0, 'harga' => 16500, 'pajak' => 11, 'batch' => 'PB-007-A'],
                    ['kode' => 'PRD-VIT-005', 'pesan' => 30, 'terima' => 0, 'harga' => 4200, 'pajak' => 11, 'batch' => 'PB-007-B'],
                ],
            ],
            [
                'nomor' => 'PO' . $now->format('Ymd') . '008',
                'pemasok_id' => $pemasok[1]->id ?? $pemasok[0]->id,
                'tanggal' => $now->copy()->subDays(6)->toDateString(),
                'jatuh_tempo' => $now->copy()->subDays(6)->addDays(30)->toDateString(),
                'status' => 'DITERIMA',
                'metode' => 'TRANSFER',
                'bayar_ratio' => 1.0,
                'items' => [
                    ['kode' => 'PRD-OBT-006', 'pesan' => 90, 'terima' => 90, 'harga' => 1350, 'pajak' => 11, 'batch' => 'PB-008-A'],
                    ['kode' => 'PRD-OBT-007', 'pesan' => 65, 'terima' => 65, 'harga' => 2100, 'pajak' => 11, 'batch' => 'PB-008-B'],
                    ['kode' => 'PRD-VIT-006', 'pesan' => 50, 'terima' => 50, 'harga' => 2800, 'pajak' => 11, 'batch' => 'PB-008-C'],
                ],
            ],
            [
                'nomor' => 'PO' . $now->format('Ymd') . '009',
                'pemasok_id' => $pemasok[2]->id ?? $pemasok[0]->id,
                'tanggal' => $now->copy()->subDays(4)->toDateString(),
                'jatuh_tempo' => $now->copy()->subDays(4)->addDays(14)->toDateString(),
                'status' => 'DIPESAN',
                'metode' => 'KREDIT',
                'bayar_ratio' => 0.0,
                'items' => [
                    ['kode' => 'PRD-ALK-006', 'pesan' => 20, 'terima' => 0, 'harga' => 12800, 'pajak' => 11, 'batch' => 'PB-009-A'],
                    ['kode' => 'PRD-ALK-007', 'pesan' => 18, 'terima' => 0, 'harga' => 15500, 'pajak' => 11, 'batch' => 'PB-009-B'],
                ],
            ],
            [
                'nomor' => 'PO' . $now->format('Ymd') . '010',
                'pemasok_id' => $pemasok[0]->id,
                'tanggal' => $now->copy()->subDays(2)->toDateString(),
                'jatuh_tempo' => $now->copy()->subDays(2)->addDays(30)->toDateString(),
                'status' => 'DITERIMA',
                'metode' => 'TUNAI',
                'bayar_ratio' => 1.0,
                'items' => [
                    ['kode' => 'PRD-VIT-007', 'pesan' => 55, 'terima' => 55, 'harga' => 3300, 'pajak' => 11, 'batch' => 'PB-010-A'],
                    ['kode' => 'PRD-VIT-008', 'pesan' => 40, 'terima' => 40, 'harga' => 3900, 'pajak' => 11, 'batch' => 'PB-010-B'],
                    ['kode' => 'PRD-OBT-008', 'pesan' => 75, 'terima' => 75, 'harga' => 1750, 'pajak' => 11, 'batch' => 'PB-010-C'],
                ],
            ],
            [
                'nomor' => 'PO' . $now->format('Ymd') . '011',
                'pemasok_id' => $pemasok[1]->id ?? $pemasok[0]->id,
                'tanggal' => $now->copy()->subDays(1)->toDateString(),
                'jatuh_tempo' => $now->copy()->subDays(1)->addDays(30)->toDateString(),
                'status' => 'DITERIMA',
                'metode' => 'TRANSFER',
                'bayar_ratio' => 1.0,
                'items' => [
                    ['kode' => 'PRD-OBT-009', 'pesan' => 85, 'terima' => 85, 'harga' => 1480, 'pajak' => 11, 'batch' => 'PB-011-A'],
                    ['kode' => 'PRD-OBT-010', 'pesan' => 70, 'terima' => 70, 'harga' => 2020, 'pajak' => 11, 'batch' => 'PB-011-B'],
                ],
            ],
            [
                'nomor' => 'PO' . $now->format('Ymd') . '012',
                'pemasok_id' => $pemasok[0]->id,
                'tanggal' => $now->copy()->subDays(0)->toDateString(),
                'jatuh_tempo' => $now->copy()->addDays(30)->toDateString(),
                'status' => 'DRAFT',
                'metode' => 'KREDIT',
                'bayar_ratio' => 0.0,
                'items' => [
                    ['kode' => 'PRD-ALK-008', 'pesan' => 12, 'terima' => 0, 'harga' => 18200, 'pajak' => 11, 'batch' => 'PB-012-A'],
                    ['kode' => 'PRD-VIT-009', 'pesan' => 25, 'terima' => 0, 'harga' => 5100, 'pajak' => 11, 'batch' => 'PB-012-B'],
                    ['kode' => 'PRD-ALK-009', 'pesan' => 10, 'terima' => 0, 'harga' => 21000, 'pajak' => 11, 'batch' => 'PB-012-C'],
                ],
            ],
        ];

        foreach ($purchases as $purchase) {
            $subtotal = 0;
            $pajakItem = 0;

            foreach ($purchase['items'] as $item) {
                $lineSubtotal = (float) $item['pesan'] * (float) $item['harga'];
                $linePajak = $lineSubtotal * ((float) $item['pajak'] / 100);
                $subtotal += $lineSubtotal;
                $pajakItem += $linePajak;
            }

            $totalAkhir = $subtotal + $pajakItem;
            $jumlahBayar = $totalAkhir * (float) $purchase['bayar_ratio'];
            $statusPembayaran = $jumlahBayar >= $totalAkhir ? 'LUNAS' : ($jumlahBayar > 0 ? 'SEBAGIAN' : 'BELUM_BAYAR');

            $pembelianId = DB::table('pembelian')->insertGetId([
                'nomor_pembelian' => $purchase['nomor'],
                'nomor_po' => $purchase['nomor'],
                'pemasok_id' => $purchase['pemasok_id'],
                'tanggal_pembelian' => $purchase['tanggal'],
                'tanggal_jatuh_tempo' => $purchase['jatuh_tempo'],
                'status' => $purchase['status'],
                'status_pembayaran' => $statusPembayaran,
                'metode_pembayaran' => $purchase['metode'],
                'subtotal' => $subtotal,
                'jenis_diskon' => 'PERSENTASE',
                'nilai_diskon' => 0,
                'jumlah_diskon' => 0,
                'jumlah_pajak' => $pajakItem,
                'biaya_kirim' => 0,
                'biaya_lain' => 0,
                'total_akhir' => $totalAkhir,
                'jumlah_bayar' => $jumlahBayar,
                'sisa_bayar' => max(0, $totalAkhir - $jumlahBayar),
                'catatan' => 'Pembelian seeding',
                'dibuat_oleh' => $adminId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            foreach ($purchase['items'] as $item) {
                $product = $produk->get($item['kode']);
                if (!$product) {
                    continue;
                }

                $produkId = (int) $product->id;
                $satuanProdukId = $getSatuanProdukId($produkId);
                $jumlahPesan = (float) $item['pesan'];
                $jumlahTerima = (float) $item['terima'];
                $harga = (float) $item['harga'];
                $pajakPersen = (float) $item['pajak'];
                $lineSubtotal = $jumlahPesan * $harga;
                $linePajak = $lineSubtotal * ($pajakPersen / 100);
                $lineTotal = $lineSubtotal + $linePajak;

                DB::table('detail_pembelian')->insert([
                    'pembelian_id' => $pembelianId,
                    'produk_id' => $produkId,
                    'satuan_produk_id' => $satuanProdukId,
                    'nomor_batch' => $item['batch'],
                    'tanggal_produksi' => $now->copy()->subMonths(1)->toDateString(),
                    'tanggal_kadaluarsa' => $now->copy()->addMonths(12)->toDateString(),
                    'jumlah_pesan' => $jumlahPesan,
                    'jumlah_terima' => $jumlahTerima,
                    'harga_satuan' => $harga,
                    'persentase_diskon' => 0,
                    'jumlah_diskon' => 0,
                    'persentase_pajak' => $pajakPersen,
                    'jumlah_pajak' => $linePajak,
                    'subtotal' => $lineSubtotal,
                    'total' => $lineTotal,
                    'catatan' => 'Detail pembelian seeding',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                if ($jumlahTerima > 0) {
                    $stok = DB::table('stok_produk')->where('produk_id', $produkId)->first();
                    if (!$stok) {
                        DB::table('stok_produk')->insert([
                            'produk_id' => $produkId,
                            'jumlah' => 0,
                            'jumlah_reservasi' => 0,
                            'jumlah_tersedia' => 0,
                            'harga_beli_terakhir' => $harga,
                            'harga_beli_rata' => $harga,
                            'terakhir_diubah' => $now,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                        $stok = DB::table('stok_produk')->where('produk_id', $produkId)->first();
                    }

                    $jumlahSebelum = (float) $stok->jumlah;
                    $jumlahSesudah = $jumlahSebelum + $jumlahTerima;

                    DB::table('stok_produk')->where('produk_id', $produkId)->update([
                        'jumlah' => $jumlahSesudah,
                        'jumlah_tersedia' => $jumlahSesudah - (float) $stok->jumlah_reservasi,
                        'harga_beli_terakhir' => $harga,
                        'terakhir_diubah' => $now,
                        'updated_at' => $now,
                    ]);

                    $batchId = DB::table('batch_produk')->insertGetId([
                        'produk_id' => $produkId,
                        'nomor_batch' => $item['batch'],
                        'tanggal_produksi' => $now->copy()->subMonths(1)->toDateString(),
                        'tanggal_kadaluarsa' => $now->copy()->addMonths(12)->toDateString(),
                        'jumlah' => $jumlahTerima,
                        'harga_beli' => $harga,
                        'pemasok_id' => $purchase['pemasok_id'],
                        'pembelian_id' => $pembelianId,
                        'sudah_kadaluarsa' => false,
                        'catatan' => 'Batch dari pembelian',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    DB::table('pergerakan_stok')->insert([
                        'produk_id' => $produkId,
                        'batch_id' => $batchId,
                        'jenis_pergerakan' => 'MASUK',
                        'tipe_referensi' => 'Pembelian',
                        'id_referensi' => $pembelianId,
                        'jumlah' => $jumlahTerima,
                        'jumlah_sebelum' => $jumlahSebelum,
                        'jumlah_sesudah' => $jumlahSesudah,
                        'harga_satuan' => $harga,
                        'catatan' => 'Penerimaan barang (seeding)',
                        'dibuat_oleh' => $adminId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }
    }
}
