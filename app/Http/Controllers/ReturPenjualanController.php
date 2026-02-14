<?php

namespace App\Http\Controllers;

use App\Models\BatchProduk;
use App\Models\DetailReturPenjualan;
use App\Models\Penjualan;
use App\Models\PergerakanStok;
use App\Models\ReturPenjualan;
use App\Models\StokProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturPenjualanController extends Controller
{
    public function create()
    {
        $penjualan = Penjualan::with(['pelanggan', 'details.produk'])
            ->latest('tanggal_penjualan')
            ->get();

        return view('pages.transaksi.retur.penjualan.create', compact('penjualan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'penjualan_id' => 'required|exists:penjualan,id',
            'tanggal_retur' => 'required|date',
            'status' => 'required|in:PENDING,DISETUJUI,DITOLAK,SELESAI',
            'metode_refund' => 'nullable|in:TUNAI,TRANSFER,NOTA_KREDIT',
            'alasan' => 'nullable|string',
            'catatan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.detail_penjualan_id' => 'nullable|exists:detail_penjualan,id',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.batch_id' => 'nullable|exists:batch_produk,id',
            'items.*.jumlah' => 'required|numeric|min:0.01',
            'items.*.harga_satuan' => 'required|numeric|min:0',
            'items.*.persentase_pajak' => 'nullable|numeric|min:0',
            'items.*.alasan' => 'nullable|string',
        ]);

        $items = $request->input('items', []);

        DB::transaction(function () use ($request, $items) {
            $penjualan = Penjualan::with('pelanggan')->findOrFail($request->penjualan_id);
            $nomor = 'RJ' . date('Ymd') . str_pad(ReturPenjualan::withTrashed()->count() + 1, 4, '0', STR_PAD_LEFT);

            $subtotal = 0;
            $jumlahPajak = 0;

            foreach ($items as $item) {
                $lineSubtotal = (float) $item['jumlah'] * (float) $item['harga_satuan'];
                $linePajak = $lineSubtotal * ((float) ($item['persentase_pajak'] ?? 0) / 100);
                $subtotal += $lineSubtotal;
                $jumlahPajak += $linePajak;
            }

            $total = $subtotal + $jumlahPajak;

            $retur = ReturPenjualan::create([
                'nomor_retur' => $nomor,
                'penjualan_id' => $penjualan->id,
                'pelanggan_id' => $penjualan->pelanggan_id,
                'tanggal_retur' => $request->tanggal_retur,
                'alasan' => $request->alasan,
                'status' => $request->status,
                'subtotal' => $subtotal,
                'jumlah_pajak' => $jumlahPajak,
                'total' => $total,
                'metode_refund' => $request->metode_refund,
                'jumlah_refund' => $total,
                'catatan' => $request->catatan,
                'dibuat_oleh' => Auth::id(),
            ]);

            foreach ($items as $item) {
                $produkId = (int) $item['produk_id'];
                $jumlah = (float) $item['jumlah'];
                $harga = (float) $item['harga_satuan'];
                $pajakPersen = (float) ($item['persentase_pajak'] ?? 0);
                $batchId = $item['batch_id'] ?? null;

                $lineSubtotal = $jumlah * $harga;
                $linePajak = $lineSubtotal * ($pajakPersen / 100);
                $lineTotal = $lineSubtotal + $linePajak;

                DetailReturPenjualan::create([
                    'retur_id' => $retur->id,
                    'detail_penjualan_id' => $item['detail_penjualan_id'] ?? null,
                    'produk_id' => $produkId,
                    'batch_id' => $batchId,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $harga,
                    'persentase_pajak' => $pajakPersen,
                    'jumlah_pajak' => $linePajak,
                    'subtotal' => $lineSubtotal,
                    'total' => $lineTotal,
                    'alasan' => $item['alasan'] ?? null,
                ]);

                $stok = StokProduk::firstOrNew(['produk_id' => $produkId]);
                $stok->jumlah = (float) ($stok->jumlah ?? 0);
                $stok->jumlah_reservasi = (float) ($stok->jumlah_reservasi ?? 0);

                $jumlahSebelum = $stok->jumlah;
                $stok->jumlah = $stok->jumlah + $jumlah;
                $stok->jumlah_tersedia = $stok->jumlah - $stok->jumlah_reservasi;
                $stok->terakhir_diubah = now();
                $stok->save();

                if ($batchId) {
                    $batch = BatchProduk::find($batchId);
                    if ($batch) {
                        $batch->jumlah = (float) $batch->jumlah + $jumlah;
                        $batch->save();
                    }
                }

                PergerakanStok::create([
                    'produk_id' => $produkId,
                    'batch_id' => $batchId,
                    'jenis_pergerakan' => 'RETUR',
                    'tipe_referensi' => 'ReturPenjualan',
                    'id_referensi' => $retur->id,
                    'jumlah' => $jumlah,
                    'jumlah_sebelum' => $jumlahSebelum,
                    'jumlah_sesudah' => $stok->jumlah,
                    'harga_satuan' => $harga,
                    'catatan' => 'Retur penjualan',
                    'dibuat_oleh' => Auth::id(),
                ]);
            }
        });

        return redirect()->route('transaksi.retur.index')->with('success', 'Retur penjualan berhasil dibuat');
    }

    public function show(ReturPenjualan $returPenjualan)
    {
        $returPenjualan->load(['pelanggan', 'penjualan', 'details.produk']);

        return view('pages.transaksi.retur.penjualan.show', compact('returPenjualan'));
    }

    public function destroy(ReturPenjualan $returPenjualan)
    {
        $returPenjualan->update(['deleted_at' => now()]);

        return redirect()->route('transaksi.retur.index')->with('success', 'Retur penjualan berhasil dihapus');
    }
}

