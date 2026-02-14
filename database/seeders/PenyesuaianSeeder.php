<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenyesuaianSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $adminId = DB::table('pengguna')->where('username', 'admin')->value('id');

        $stokProduk = DB::table('stok_produk')
            ->where('jumlah', '>', 0)
            ->get();

        if ($stokProduk->isEmpty()) {
            $this->command->warn('Tidak ada stok produk. Skip seeding penyesuaian.');
            return;
        }

        $batchMap = [];
        foreach (DB::table('batch_produk')->get() as $batch) {
            $batchMap[$batch->produk_id] = $batch;
        }

        $penyesuaianData = [
            [
                'tanggal' => $now->copy()->subDays(20)->toDateString(),
                'jenis' => 'RUSAK',
                'status' => 'DISETUJUI',
                'alasan' => 'Barang rusak di rak',
                'items' => [
                    ['stok_index' => 0, 'selisih' => -5],
                ],
            ],
            [
                'tanggal' => $now->copy()->subDays(18)->toDateString(),
                'jenis' => 'PENAMBAHAN',
                'status' => 'DISETUJUI',
                'alasan' => 'Barang temuan',
                'items' => [
                    ['stok_index' => 1, 'selisih' => 3],
                ],
            ],
            [
                'tanggal' => $now->copy()->subDays(15)->toDateString(),
                'jenis' => 'RUSAK',
                'status' => 'DISETUJUI',
                'alasan' => 'Expired',
                'items' => [
                    ['stok_index' => 2, 'selisih' => -8],
                ],
            ],
            [
                'tanggal' => $now->copy()->subDays(12)->toDateString(),
                'jenis' => 'KOREKSI',
                'status' => 'DISETUJUI',
                'alasan' => ' Koreksi hitungan',
                'items' => [
                    ['stok_index' => 3, 'selisih' => 2],
                ],
            ],
            [
                'tanggal' => $now->copy()->subDays(10)->toDateString(),
                'jenis' => 'RUSAK',
                'status' => 'DISETUJUI',
                'alasan' => 'Kemasan rusak',
                'items' => [
                    ['stok_index' => 4, 'selisih' => -3],
                ],
            ],
            [
                'tanggal' => $now->copy()->subDays(8)->toDateString(),
                'jenis' => 'PENAMBAHAN',
                'status' => 'DISETUJUI',
                'alasan' => 'Barang retur dari pelanggan',
                'items' => [
                    ['stok_index' => 5, 'selisih' => 4],
                ],
            ],
            [
                'tanggal' => $now->copy()->subDays(5)->toDateString(),
                'jenis' => 'KOREKSI',
                'status' => 'DRAFT',
                'alasan' => 'Menunggu konfirmasi',
                'items' => [
                    ['stok_index' => 6, 'selisih' => -2],
                ],
            ],
            [
                'tanggal' => $now->copy()->subDays(3)->toDateString(),
                'jenis' => 'RUSAK',
                'status' => 'DISETUJUI',
                'alasan' => 'Barang pecah',
                'items' => [
                    ['stok_index' => 7, 'selisih' => -6],
                ],
            ],
            [
                'tanggal' => $now->copy()->subDays(1)->toDateString(),
                'jenis' => 'PENAMBAHAN',
                'status' => 'DISETUJUI',
                'alasan' => 'Stock opname temuan',
                'items' => [
                    ['stok_index' => 8, 'selisih' => 1],
                ],
            ],
            [
                'tanggal' => $now->copy()->subDays(0)->toDateString(),
                'jenis' => 'KOREKSI',
                'status' => 'DITOLAK',
                'alasan' => 'Ditolak - data tidak valid',
                'items' => [
                    ['stok_index' => 9, 'selisih' => 10],
                ],
            ],
        ];

        $nomor = 1;
        foreach ($penyesuaianData as $data) {
            $items = [];
            $totalNilai = 0;

            foreach ($data['items'] as $item) {
                $stok = $stokProduk->get($item['stok_index']);
                if (!$stok) continue;

                $selisih = $item['selisih'];
                $harga = (float) DB::table('produk')->where('id', $stok->produk_id)->value('harga_beli') ?? 0;
                $batch = $batchMap[$stok->produk_id] ?? null;
                $batchId = $batch ? (int) $batch->id : null;

                $items[] = [
                    'produk_id' => $stok->produk_id,
                    'batch_id' => $batchId,
                    'jumlah_sistem' => (float) $stok->jumlah,
                    'jumlah_aktual' => (float) $stok->jumlah + $selisih,
                    'selisih' => $selisih,
                    'harga_satuan' => $harga,
                    'total_nilai' => $selisih * $harga,
                    'catatan' => $data['alasan'],
                ];

                $totalNilai += $selisih * $harga;
            }

            if (empty($items)) continue;

            $penyesuaianId = DB::table('penyesuaian_stok')->insertGetId([
                'nomor_penyesuaian' => 'ADJ' . $now->format('Ymd') . str_pad($nomor, 2, '0', STR_PAD_LEFT),
                'tanggal_penyesuaian' => $data['tanggal'],
                'jenis_penyesuaian' => $data['jenis'],
                'status' => $data['status'],
                'total_item' => count($items),
                'catatan' => 'Penyesuaian seeding #' . $nomor,
                'disetujui_oleh' => $data['status'] === 'DISETUJUI' ? $adminId : null,
                'waktu_persetujuan' => $data['status'] === 'DISETUJUI' ? $now : null,
                'dibuat_oleh' => $adminId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            foreach ($items as $item) {
                DB::table('detail_penyesuaian_stok')->insert([
                    'penyesuaian_id' => $penyesuaianId,
                    'produk_id' => $item['produk_id'],
                    'batch_id' => $item['batch_id'],
                    'jumlah_sistem' => $item['jumlah_sistem'],
                    'jumlah_aktual' => $item['jumlah_aktual'],
                    'selisih' => $item['selisih'],
                    'harga_satuan' => $item['harga_satuan'],
                    'total_nilai' => $item['total_nilai'],
                    'catatan' => $item['catatan'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                if ($data['status'] === 'DISETUJUI' && $item['selisih'] != 0) {
                    $stok = DB::table('stok_produk')->where('produk_id', $item['produk_id'])->first();
                    if ($stok) {
                        $jumlahSebelum = (float) $stok->jumlah;
                        $jumlahSesudah = $jumlahSebelum + $item['selisih'];

                        DB::table('stok_produk')->where('produk_id', $item['produk_id'])->update([
                            'jumlah' => $jumlahSesudah,
                            'jumlah_tersedia' => $jumlahSesudah - (float) $stok->jumlah_reservasi,
                            'terakhir_diubah' => $now,
                            'updated_at' => $now,
                        ]);

                        if ($item['batch_id']) {
                            $batch = $batchMap[$item['produk_id']] ?? null;
                            if ($batch) {
                                DB::table('batch_produk')->where('id', $item['batch_id'])->update([
                                    'jumlah' => max(0, (float) $batch->jumlah + $item['selisih']),
                                    'updated_at' => $now,
                                ]);
                            }
                        }

                        $jenisPergerakan = $data['jenis'] === 'RUSAK' ? 'RUSAK' : 'PENYESUAIAN';
                        DB::table('pergerakan_stok')->insert([
                            'produk_id' => $item['produk_id'],
                            'batch_id' => $item['batch_id'],
                            'jenis_pergerakan' => $jenisPergerakan,
                            'tipe_referensi' => 'PenyesuaianStok',
                            'id_referensi' => $penyesuaianId,
                            'jumlah' => abs($item['selisih']),
                            'jumlah_sebelum' => $jumlahSebelum,
                            'jumlah_sesudah' => $jumlahSesudah,
                            'harga_satuan' => $item['harga_satuan'],
                            'catatan' => 'Penyesuaian stok (seeding)',
                            'dibuat_oleh' => $adminId,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    }
                }
            }

            $nomor++;
        }
    }
}
