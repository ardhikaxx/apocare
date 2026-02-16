<?php

namespace App\Http\Controllers;

use App\Models\BatchProduk;
use App\Models\DetailPenjualan;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\PergerakanStok;
use App\Models\Produk;
use App\Models\StokProduk;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        $query = Penjualan::with('pelanggan')->withCount('details');

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal_penjualan', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        $penjualan = $query->latest('tanggal_penjualan')->get();

        return view('pages.transaksi.penjualan.index', compact('penjualan'));
    }

    public function create()
    {
        $pelanggan = Pelanggan::where('status_aktif', true)->get();
        $produk = Produk::with(['kategori', 'satuan', 'stokProduk'])
            ->where('status_aktif', true)
            ->orderBy('nama')
            ->get();

        return view('pages.transaksi.penjualan.create', compact('pelanggan', 'produk'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatePenjualanPayload($request->all());
        $clientReference = $request->input('client_reference');

        if (is_string($clientReference) && $clientReference !== '') {
            $existing = Penjualan::where('client_reference', $clientReference)->first();
            if ($existing) {
                return redirect()->route('transaksi.penjualan.index')->with('success', 'Transaksi sudah pernah diproses.');
            }
        }

        $this->prosesSimpanPenjualan($validated, Auth::id(), $clientReference);

        return redirect()->route('transaksi.penjualan.index')->with('success', 'Penjualan berhasil disimpan');
    }

    public function syncOffline(Request $request): JsonResponse
    {
        $request->validate([
            'transactions' => 'required|array|min:1',
        ]);

        $results = [];
        $syncedCount = 0;

        foreach ($request->input('transactions', []) as $index => $transaction) {
            try {
                if (! is_array($transaction)) {
                    throw ValidationException::withMessages([
                        "transactions.$index" => 'Format transaksi tidak valid.',
                    ]);
                }

                $validated = $this->validatePenjualanPayload($transaction);
                $clientReference = $transaction['client_reference'] ?? null;

                if (is_string($clientReference) && $clientReference !== '') {
                    $existing = Penjualan::where('client_reference', $clientReference)->first();
                    if ($existing) {
                        $results[] = [
                            'client_reference' => $clientReference,
                            'status' => 'duplicate',
                            'penjualan_id' => $existing->id,
                            'nomor_penjualan' => $existing->nomor_penjualan,
                            'message' => 'Transaksi sudah ada.',
                        ];
                        continue;
                    }
                }

                $penjualan = $this->prosesSimpanPenjualan($validated, Auth::id(), $clientReference);

                $results[] = [
                    'client_reference' => $clientReference,
                    'status' => 'synced',
                    'penjualan_id' => $penjualan->id,
                    'nomor_penjualan' => $penjualan->nomor_penjualan,
                ];
                $syncedCount++;
            } catch (ValidationException $e) {
                $results[] = [
                    'client_reference' => $transaction['client_reference'] ?? null,
                    'status' => 'failed',
                    'message' => $e->getMessage(),
                    'errors' => $e->errors(),
                ];
            } catch (Throwable $e) {
                $results[] = [
                    'client_reference' => $transaction['client_reference'] ?? null,
                    'status' => 'failed',
                    'message' => $e->getMessage(),
                ];
            }
        }

        return response()->json([
            'success' => true,
            'synced_count' => $syncedCount,
            'results' => $results,
        ]);
    }

    public function show(Penjualan $penjualan)
    {
        $penjualan->load(['pelanggan', 'details.produk']);

        return view('pages.transaksi.penjualan.show', compact('penjualan'));
    }

    public function destroy(Penjualan $penjualan)
    {
        $penjualan->update(['deleted_at' => now()]);

        return redirect()->route('transaksi.penjualan.index')->with('success', 'Penjualan berhasil dihapus');
    }

    private function validatePenjualanPayload(array $payload): array
    {
        $validator = Validator::make($payload, [
            'pelanggan_id' => 'nullable|exists:pelanggan,id',
            'metode_pembayaran' => ['required', Rule::in(['TUNAI', 'DEBIT', 'KREDIT', 'TRANSFER', 'EWALLET', 'QRIS'])],
            'jenis_diskon' => ['nullable', Rule::in(['PERSENTASE', 'NOMINAL'])],
            'nilai_diskon' => 'nullable|numeric|min:0',
            'pajak_transaksi' => 'nullable|numeric|min:0',
            'jumlah_bayar' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
            'client_reference' => 'nullable|string|max:100',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.jumlah' => 'required|numeric|min:0.01',
            'items.*.harga_satuan' => 'required|numeric|min:0',
            'items.*.persentase_diskon' => 'nullable|numeric|min:0',
            'items.*.persentase_pajak' => 'nullable|numeric|min:0',
            'items.*.batch_id' => 'nullable|exists:batch_produk,id',
            'items.*.satuan_produk_id' => 'nullable|exists:satuan_produk,id',
            'items.*.catatan' => 'nullable|string',
        ]);

        return $validator->validate();
    }

    private function prosesSimpanPenjualan(array $data, ?int $userId, ?string $clientReference = null): Penjualan
    {
        $items = $data['items'];

        return DB::transaction(function () use ($data, $items, $userId, $clientReference) {
            $nomor = 'SL' . date('Ymd') . str_pad(Penjualan::withTrashed()->count() + 1, 4, '0', STR_PAD_LEFT);

            $subtotal = 0;
            $diskonItem = 0;
            $pajakItem = 0;

            foreach ($items as $item) {
                $lineSubtotal = (float) $item['jumlah'] * (float) $item['harga_satuan'];
                $lineDiskon = $lineSubtotal * ((float) ($item['persentase_diskon'] ?? 0) / 100);
                $linePajak = ($lineSubtotal - $lineDiskon) * ((float) ($item['persentase_pajak'] ?? 0) / 100);

                $subtotal += $lineSubtotal;
                $diskonItem += $lineDiskon;
                $pajakItem += $linePajak;
            }

            $jenisDiskon = $data['jenis_diskon'] ?? 'PERSENTASE';
            $nilaiDiskon = (float) ($data['nilai_diskon'] ?? 0);
            $diskonGlobal = $jenisDiskon === 'PERSENTASE'
                ? ($subtotal * ($nilaiDiskon / 100))
                : $nilaiDiskon;

            $pajakTransaksi = (float) ($data['pajak_transaksi'] ?? 0);
            $pajakGlobal = ($subtotal - $diskonItem - $diskonGlobal) * ($pajakTransaksi / 100);

            $jumlahDiskon = $diskonItem + $diskonGlobal;
            $jumlahPajak = $pajakItem + $pajakGlobal;
            $totalAkhir = ($subtotal - $jumlahDiskon) + $jumlahPajak;

            $jumlahBayar = (float) ($data['jumlah_bayar'] ?? 0);
            $statusPembayaran = 'BELUM_BAYAR';
            if ($jumlahBayar >= $totalAkhir && $totalAkhir > 0) {
                $statusPembayaran = 'LUNAS';
            } elseif ($jumlahBayar > 0 && $jumlahBayar < $totalAkhir) {
                $statusPembayaran = 'SEBAGIAN';
            }

            $penjualan = Penjualan::create([
                'nomor_penjualan' => $nomor,
                'client_reference' => $clientReference,
                'pelanggan_id' => $data['pelanggan_id'] ?? null,
                'tanggal_penjualan' => now(),
                'jenis_penjualan' => 'RETAIL',
                'status_pembayaran' => $statusPembayaran,
                'metode_pembayaran' => $data['metode_pembayaran'],
                'subtotal' => $subtotal,
                'jenis_diskon' => $jenisDiskon,
                'nilai_diskon' => $nilaiDiskon,
                'jumlah_diskon' => $jumlahDiskon,
                'jumlah_pajak' => $jumlahPajak,
                'total_akhir' => $totalAkhir,
                'jumlah_bayar' => $jumlahBayar,
                'jumlah_kembalian' => max(0, $jumlahBayar - $totalAkhir),
                'catatan' => $data['catatan'] ?? null,
                'dilayani_oleh' => $userId,
                'dibuat_oleh' => $userId,
            ]);

            foreach ($items as $item) {
                $produkId = (int) $item['produk_id'];
                $jumlah = (float) $item['jumlah'];
                $harga = (float) $item['harga_satuan'];
                $diskonPersen = (float) ($item['persentase_diskon'] ?? 0);
                $pajakPersen = (float) ($item['persentase_pajak'] ?? 0);
                $batchId = $item['batch_id'] ?? null;

                $lineSubtotal = $jumlah * $harga;
                $lineDiskon = $lineSubtotal * ($diskonPersen / 100);
                $linePajak = ($lineSubtotal - $lineDiskon) * ($pajakPersen / 100);
                $lineTotal = $lineSubtotal - $lineDiskon + $linePajak;

                $stok = StokProduk::firstOrNew(['produk_id' => $produkId]);
                $stok->jumlah = (float) ($stok->jumlah ?? 0);
                $stok->jumlah_reservasi = (float) ($stok->jumlah_reservasi ?? 0);

                if ($stok->jumlah < $jumlah) {
                    throw ValidationException::withMessages([
                        'items' => 'Stok produk tidak mencukupi untuk salah satu item.',
                    ]);
                }

                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'produk_id' => $produkId,
                    'satuan_produk_id' => $item['satuan_produk_id'] ?? null,
                    'batch_id' => $batchId,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $harga,
                    'persentase_diskon' => $diskonPersen,
                    'jumlah_diskon' => $lineDiskon,
                    'persentase_pajak' => $pajakPersen,
                    'jumlah_pajak' => $linePajak,
                    'subtotal' => $lineSubtotal,
                    'total' => $lineTotal,
                    'catatan' => $item['catatan'] ?? null,
                ]);

                $jumlahSebelum = $stok->jumlah;
                $stok->jumlah = $stok->jumlah - $jumlah;
                $stok->jumlah_tersedia = $stok->jumlah - $stok->jumlah_reservasi;
                $stok->terakhir_diubah = now();
                $stok->save();

                if ($batchId) {
                    $batch = BatchProduk::find($batchId);
                    if ($batch) {
                        $batch->jumlah = max(0, (float) $batch->jumlah - $jumlah);
                        $batch->save();
                    }
                }

                PergerakanStok::create([
                    'produk_id' => $produkId,
                    'batch_id' => $batchId,
                    'jenis_pergerakan' => 'KELUAR',
                    'tipe_referensi' => 'Penjualan',
                    'id_referensi' => $penjualan->id,
                    'jumlah' => $jumlah,
                    'jumlah_sebelum' => $jumlahSebelum,
                    'jumlah_sesudah' => $stok->jumlah,
                    'harga_satuan' => $harga,
                    'catatan' => 'Penjualan POS',
                    'dibuat_oleh' => $userId,
                ]);
            }

            return $penjualan;
        });
    }
}