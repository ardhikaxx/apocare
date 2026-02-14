<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OpnameSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $adminId = DB::table('pengguna')->where('username', 'admin')->value('id');

        $stokProduk = DB::table('stok_produk')
            ->where('jumlah', '>', 0)
            ->get();

        if ($stokProduk->isEmpty()) {
            $this->command->warn('Tidak ada stok produk. Skip seeding opname.');
            return;
        }

        $batchMap = [];
        foreach (DB::table('batch_produk')->get() as $batch) {
            $batchMap[$batch->produk_id] = $batch;
        }

        $opnameData = [
            [
                'tanggal' => $now->copy()->subDays(25)->toDateString(),
                'status' => 'DISETUJUI',
                'items' => [
                    ['stok_index' => 0, 'selisih' => -3, 'status_item' => 'KURANG'],
                    ['stok_index' => 1, 'selisih' => 0, 'status_item' => 'COCOK'],
                ],
            ],
            [
                'tanggal' => $now->copy()->subDays(22)->toDateString(),
                'status' => 'DISETUJUI',
                'items' => [
                    ['stok_index' => 2, 'selisih' => 5, 'status_item' => 'LEBIH'],
                    ['stok_index' => 3, 'selisih' => -1, 'status_item' => 'KURANG'],
                ],
            ],
            [
                'tanggal' => $now->copy()->subDays(18)->toDateString(),
                'status' => 'DISETUJUI',
                'items' => [
                    ['stok_index' => 4, 'selisih' => 2, 'status_item' => 'LEBIH'],
                    ['stok_index' => 5, 'selisih' => 0, 'status_item' => 'COCOK'],
                    ['stok_index' => 6, 'selisih' => -2, 'status_item' => 'KURANG'],
                ],
            ],
            [
                'tanggal' => $now->copy()->subDays(15)->toDateString(),
                'status' => 'DISETUJUI',
                'items' => [
                    ['stok_index' => 7, 'selisih' => 0, 'status_item' => 'COCOK'],
                    ['stok_index' => 8, 'selisih' => 1, 'status_item' => 'LEBIH'],
                ],
            ],
            [
                'tanggal' => $now->copy()->subDays(12)->toDateString(),
                'status' => 'PROSES',
                'items' => [
                    ['stok_index' => 9, 'selisih' => -4, 'status_item' => 'KURANG'],
                    ['stok_index' => 10, 'selisih' => 3, 'status_item' => 'LEBIH'],
                ],
            ],
            [
                'tanggal' => $now->copy()->subDays(10)->toDateString(),
                'status' => 'DISETUJUI',
                'items' => [
                    ['stok_index' => 11, 'selisih' => -2, 'status_item' => 'KURANG'],
                    ['stok_index' => 12, 'selisih' => 0, 'status_item' => 'COCOK'],
                    ['stok_index' => 13, 'selisih' => 4, 'status_item' => 'LEBIH'],
                ],
            ],
            [
                'tanggal' => $now->copy()->subDays(7)->toDateString(),
                'status' => 'DISETUJUI',
                'items' => [
                    ['stok_index' => 14, 'selisih' => 1, 'status_item' => 'LEBIH'],
                    ['stok_index' => 15, 'selisih' => -1, 'status_item' => 'KURANG'],
                ],
            ],
            [
                'tanggal' => $now->copy()->subDays(5)->toDateString(),
                'status' => 'SELESAI',
                'items' => [
                    ['stok_index' => 16, 'selisih' => 10, 'status_item' => 'LEBIH'],
                ],
            ],
            [
                'tanggal' => $now->copy()->subDays(3)->toDateString(),
                'status' => 'DISETUJUI',
                'items' => [
                    ['stok_index' => 17, 'selisih' => 0, 'status_item' => 'COCOK'],
                    ['stok_index' => 18, 'selisih' => -3, 'status_item' => 'KURANG'],
                    ['stok_index' => 19, 'selisih' => 2, 'status_item' => 'LEBIH'],
                ],
            ],
            [
                'tanggal' => $now->copy()->subDays(1)->toDateString(),
                'status' => 'DISETUJUI',
                'items' => [
                    ['stok_index' => 20, 'selisih' => 1, 'status_item' => 'LEBIH'],
                    ['stok_index' => 21, 'selisih' => 0, 'status_item' => 'COCOK'],
                ],
            ],
        ];

        $nomor = 1;
        foreach ($opnameData as $data) {
            $items = [];
            $totalItemCocok = 0;
            $totalItemSelisih = 0;
            $totalNilaiSelisih = 0;
            $kategoriId = null;

            foreach ($data['items'] as $item) {
                $stok = $stokProduk->get($item['stok_index']);
                if (!$stok) continue;

                $selisih = $item['selisih'];
                $statusItem = $item['status_item'];
                $harga = (float) DB::table('produk')->where('id', $stok->produk_id)->value('harga_beli') ?? 0;
                $batch = $batchMap[$stok->produk_id] ?? null;
                $batchId = $batch ? (int) $batch->id : null;

                if (!$kategoriId) {
                    $kategoriId = DB::table('produk')->where('id', $stok->produk_id)->value('kategori_id');
                }

                $items[] = [
                    'produk_id' => $stok->produk_id,
                    'batch_id' => $batchId,
                    'jumlah_sistem' => (float) $stok->jumlah,
                    'jumlah_hitung' => (float) $stok->jumlah + $selisih,
                    'selisih' => $selisih,
                    'harga_satuan' => $harga,
                    'total_nilai_selisih' => $selisih * $harga,
                    'status' => $statusItem,
                    'dihitung_oleh' => $adminId,
                    'waktu_hitung' => $now,
                    'catatan' => 'Hasil opname seeding',
                ];

                if ($statusItem === 'COCOK') {
                    $totalItemCocok++;
                } else {
                    $totalItemSelisih++;
                }
                $totalNilaiSelisih += $selisih * $harga;
            }

            if (empty($items)) continue;

            $opnameId = DB::table('stok_opname')->insertGetId([
                'nomor_opname' => 'OP' . $now->format('Ymd') . str_pad($nomor, 2, '0', STR_PAD_LEFT),
                'tanggal_opname' => $data['tanggal'],
                'status' => $data['status'],
                'kategori_id' => $kategoriId,
                'total_item_dihitung' => count($items),
                'total_item_cocok' => $totalItemCocok,
                'total_item_selisih' => $totalItemSelisih,
                'total_nilai_selisih' => $totalNilaiSelisih,
                'catatan' => 'Stok opname seeding #' . $nomor,
                'disetujui_oleh' => $data['status'] === 'DISETUJUI' ? $adminId : null,
                'waktu_persetujuan' => $data['status'] === 'DISETUJUI' ? $now : null,
                'dibuat_oleh' => $adminId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            foreach ($items as $item) {
                DB::table('detail_stok_opname')->insert([
                    'opname_id' => $opnameId,
                    'produk_id' => $item['produk_id'],
                    'batch_id' => $item['batch_id'],
                    'jumlah_sistem' => $item['jumlah_sistem'],
                    'jumlah_hitung' => $item['jumlah_hitung'],
                    'selisih' => $item['selisih'],
                    'harga_satuan' => $item['harga_satuan'],
                    'total_nilai_selisih' => $item['total_nilai_selisih'],
                    'status' => $item['status'],
                    'dihitung_oleh' => $item['dihitung_oleh'],
                    'waktu_hitung' => $item['waktu_hitung'],
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

                        DB::table('pergerakan_stok')->insert([
                            'produk_id' => $item['produk_id'],
                            'batch_id' => $item['batch_id'],
                            'jenis_pergerakan' => 'PENYESUAIAN',
                            'tipe_referensi' => 'StokOpname',
                            'id_referensi' => $opnameId,
                            'jumlah' => abs($item['selisih']),
                            'jumlah_sebelum' => $jumlahSebelum,
                            'jumlah_sesudah' => $jumlahSesudah,
                            'harga_satuan' => $item['harga_satuan'],
                            'catatan' => 'Stok opname (seeding)',
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
