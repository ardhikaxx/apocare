<?php

namespace App\Http\Controllers;

use App\Models\BatchProduk;
use App\Models\DetailPenjualan;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\PergerakanStok;
use App\Models\Produk;
use App\Models\StokProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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
        $request->validate([
            'pelanggan_id' => 'nullable|exists:pelanggan,id',
            'metode_pembayaran' => 'required|in:TUNAI,DEBIT,KREDIT,TRANSFER,EWALLET,QRIS',
            'jenis_diskon' => 'nullable|in:PERSENTASE,NOMINAL',
            'nilai_diskon' => 'nullable|numeric|min:0',
            'pajak_transaksi' => 'nullable|numeric|min:0',
            'jumlah_bayar' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.jumlah' => 'required|numeric|min:0.01',
            'items.*.harga_satuan' => 'required|numeric|min:0',
            'items.*.persentase_diskon' => 'nullable|numeric|min:0',
            'items.*.persentase_pajak' => 'nullable|numeric|min:0',
            'items.*.batch_id' => 'nullable|exists:batch_produk,id',
        ]);

        $items = $request->input('items', []);

        DB::transaction(function () use ($request, $items) {
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

            $jenisDiskon = $request->jenis_diskon ?: 'PERSENTASE';
            $nilaiDiskon = (float) ($request->nilai_diskon ?? 0);
            $diskonGlobal = $jenisDiskon === 'PERSENTASE'
                ? ($subtotal * ($nilaiDiskon / 100))
                : $nilaiDiskon;

            $pajakTransaksi = (float) ($request->pajak_transaksi ?? 0);
            $pajakGlobal = ($subtotal - $diskonItem - $diskonGlobal) * ($pajakTransaksi / 100);

            $jumlahDiskon = $diskonItem + $diskonGlobal;
            $jumlahPajak = $pajakItem + $pajakGlobal;
            $totalAkhir = ($subtotal - $jumlahDiskon) + $jumlahPajak;

            $jumlahBayar = (float) $request->jumlah_bayar;
            $statusPembayaran = 'BELUM_BAYAR';
            if ($jumlahBayar >= $totalAkhir && $totalAkhir > 0) {
                $statusPembayaran = 'LUNAS';
            } elseif ($jumlahBayar > 0 && $jumlahBayar < $totalAkhir) {
                $statusPembayaran = 'SEBAGIAN';
            }

            $penjualan = Penjualan::create([
                'nomor_penjualan' => $nomor,
                'pelanggan_id' => $request->pelanggan_id,
                'tanggal_penjualan' => now(),
                'jenis_penjualan' => 'RETAIL',
                'status_pembayaran' => $statusPembayaran,
                'metode_pembayaran' => $request->metode_pembayaran,
                'subtotal' => $subtotal,
                'jenis_diskon' => $jenisDiskon,
                'nilai_diskon' => $nilaiDiskon,
                'jumlah_diskon' => $jumlahDiskon,
                'jumlah_pajak' => $jumlahPajak,
                'total_akhir' => $totalAkhir,
                'jumlah_bayar' => $jumlahBayar,
                'jumlah_kembalian' => max(0, $jumlahBayar - $totalAkhir),
                'catatan' => $request->catatan,
                'dilayani_oleh' => Auth::id(),
                'dibuat_oleh' => Auth::id(),
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
                        'items' => 'Stok produk tidak mencukupi untuk salah satu item.'
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
                    'dibuat_oleh' => Auth::id(),
                ]);
            }
        });

        return redirect()->route('transaksi.penjualan.index')->with('success', 'Penjualan berhasil disimpan');
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
}
