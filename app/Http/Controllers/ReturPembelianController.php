<?php

namespace App\Http\Controllers;

use App\Models\BatchProduk;
use App\Models\DetailReturPembelian;
use App\Models\Pembelian;
use App\Models\PergerakanStok;
use App\Models\ReturPembelian;
use App\Models\StokProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReturPembelianController extends Controller
{
    public function create()
    {
        $pembelian = Pembelian::with(['pemasok', 'details.produk'])
            ->latest('tanggal_pembelian')
            ->get();

        return view('pages.transaksi.retur.pembelian.create', compact('pembelian'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pembelian_id' => 'required|exists:pembelian,id',
            'tanggal_retur' => 'required|date',
            'status' => 'required|in:PENDING,DISETUJUI,DITOLAK,SELESAI',
            'metode_refund' => 'nullable|in:TUNAI,TRANSFER,NOTA_KREDIT',
            'alasan' => 'nullable|string',
            'catatan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.detail_pembelian_id' => 'nullable|exists:detail_pembelian,id',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.batch_id' => 'nullable|exists:batch_produk,id',
            'items.*.jumlah' => 'required|numeric|min:0.01',
            'items.*.harga_satuan' => 'required|numeric|min:0',
            'items.*.persentase_pajak' => 'nullable|numeric|min:0',
            'items.*.alasan' => 'nullable|string',
        ]);

        $items = $request->input('items', []);

        DB::transaction(function () use ($request, $items) {
            $pembelian = Pembelian::with('pemasok')->findOrFail($request->pembelian_id);
            $nomor = 'RB' . date('Ymd') . str_pad(ReturPembelian::withTrashed()->count() + 1, 4, '0', STR_PAD_LEFT);

            $subtotal = 0;
            $jumlahPajak = 0;

            foreach ($items as $item) {
                $lineSubtotal = (float) $item['jumlah'] * (float) $item['harga_satuan'];
                $linePajak = $lineSubtotal * ((float) ($item['persentase_pajak'] ?? 0) / 100);
                $subtotal += $lineSubtotal;
                $jumlahPajak += $linePajak;
            }

            $total = $subtotal + $jumlahPajak;

            $retur = ReturPembelian::create([
                'nomor_retur' => $nomor,
                'pembelian_id' => $pembelian->id,
                'pemasok_id' => $pembelian->pemasok_id,
                'tanggal_retur' => $request->tanggal_retur,
                'alasan' => $request->alasan,
                'status' => $request->status,
                'subtotal' => $subtotal,
                'jumlah_pajak' => $jumlahPajak,
                'total' => $total,
                'metode_refund' => $request->metode_refund,
                'jumlah_refund' => $total,
                'catatan' => $request->catatan,
                'dibuat_oleh' => auth()->id(),
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

                DetailReturPembelian::create([
                    'retur_id' => $retur->id,
                    'detail_pembelian_id' => $item['detail_pembelian_id'] ?? null,
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

                if ($stok->jumlah < $jumlah) {
                    throw ValidationException::withMessages([
                        'items' => 'Stok tidak mencukupi untuk retur pembelian.'
                    ]);
                }

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
                    'jenis_pergerakan' => 'RETUR',
                    'tipe_referensi' => 'ReturPembelian',
                    'id_referensi' => $retur->id,
                    'jumlah' => $jumlah,
                    'jumlah_sebelum' => $jumlahSebelum,
                    'jumlah_sesudah' => $stok->jumlah,
                    'harga_satuan' => $harga,
                    'catatan' => 'Retur pembelian',
                    'dibuat_oleh' => auth()->id(),
                ]);
            }
        });

        return redirect()->route('transaksi.retur.index')->with('success', 'Retur pembelian berhasil dibuat');
    }

    public function show(ReturPembelian $returPembelian)
    {
        $returPembelian->load(['pemasok', 'pembelian', 'details.produk']);

        return view('pages.transaksi.retur.pembelian.show', compact('returPembelian'));
    }

    public function destroy(ReturPembelian $returPembelian)
    {
        $returPembelian->update(['deleted_at' => now()]);

        return redirect()->route('transaksi.retur.index')->with('success', 'Retur pembelian berhasil dihapus');
    }
}

