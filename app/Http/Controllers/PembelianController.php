<?php

namespace App\Http\Controllers;

use App\Models\BatchProduk;
use App\Models\DetailPembelian;
use App\Models\Pembelian;
use App\Models\Pemasok;
use App\Models\PergerakanStok;
use App\Models\Produk;
use App\Models\StokProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PembelianController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembelian::with('pemasok')->withCount('details');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pembelian = $query->latest('tanggal_pembelian')->get();

        return view('pages.transaksi.pembelian.index', compact('pembelian'));
    }

    public function create()
    {
        $pemasok = Pemasok::where('status_aktif', true)->orderBy('nama')->get();
        $produk = Produk::with(['satuan', 'satuanProduk'])->where('status_aktif', true)->orderBy('nama')->get();

        return view('pages.transaksi.pembelian.create', compact('pemasok', 'produk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pemasok_id' => 'required|exists:pemasok,id',
            'nomor_po' => 'nullable|string|max:30',
            'tanggal_pembelian' => 'required|date',
            'tanggal_jatuh_tempo' => 'nullable|date',
            'status' => 'required|in:DRAFT,DIPESAN,SEBAGIAN,DITERIMA,SELESAI,BATAL',
            'metode_pembayaran' => 'nullable|in:TUNAI,TRANSFER,KREDIT,GIRO',
            'jenis_diskon' => 'nullable|in:PERSENTASE,NOMINAL',
            'nilai_diskon' => 'nullable|numeric|min:0',
            'pajak_transaksi' => 'nullable|numeric|min:0',
            'biaya_kirim' => 'nullable|numeric|min:0',
            'biaya_lain' => 'nullable|numeric|min:0',
            'jumlah_bayar' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.jumlah_pesan' => 'required|numeric|min:0.01',
            'items.*.jumlah_terima' => 'nullable|numeric|min:0',
            'items.*.harga_satuan' => 'required|numeric|min:0',
            'items.*.persentase_diskon' => 'nullable|numeric|min:0',
            'items.*.persentase_pajak' => 'nullable|numeric|min:0',
            'items.*.nomor_batch' => 'nullable|string|max:50',
            'items.*.tanggal_produksi' => 'nullable|date',
            'items.*.tanggal_kadaluarsa' => 'nullable|date',
        ]);

        $items = $request->input('items', []);

        DB::transaction(function () use ($request, $items) {
            $nomor = 'PO' . date('Ymd') . str_pad(Pembelian::withTrashed()->count() + 1, 4, '0', STR_PAD_LEFT);

            $subtotal = 0;
            $diskonItem = 0;
            $pajakItem = 0;

            foreach ($items as $item) {
                $lineSubtotal = (float) $item['jumlah_pesan'] * (float) $item['harga_satuan'];
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
            $biayaKirim = (float) ($request->biaya_kirim ?? 0);
            $biayaLain = (float) ($request->biaya_lain ?? 0);
            $totalAkhir = ($subtotal - $jumlahDiskon) + $jumlahPajak + $biayaKirim + $biayaLain;

            $jumlahBayar = (float) ($request->jumlah_bayar ?? 0);
            $statusPembayaran = 'BELUM_BAYAR';
            if ($jumlahBayar >= $totalAkhir && $totalAkhir > 0) {
                $statusPembayaran = 'LUNAS';
            } elseif ($jumlahBayar > 0 && $jumlahBayar < $totalAkhir) {
                $statusPembayaran = 'SEBAGIAN';
            }

            $pembelian = Pembelian::create([
                'nomor_pembelian' => $nomor,
                'nomor_po' => $request->nomor_po,
                'pemasok_id' => $request->pemasok_id,
                'tanggal_pembelian' => $request->tanggal_pembelian,
                'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo ?? now()->addDays(30),
                'status' => $request->status,
                'status_pembayaran' => $statusPembayaran,
                'metode_pembayaran' => $request->metode_pembayaran,
                'subtotal' => $subtotal,
                'jenis_diskon' => $jenisDiskon,
                'nilai_diskon' => $nilaiDiskon,
                'jumlah_diskon' => $jumlahDiskon,
                'jumlah_pajak' => $jumlahPajak,
                'biaya_kirim' => $biayaKirim,
                'biaya_lain' => $biayaLain,
                'total_akhir' => $totalAkhir,
                'jumlah_bayar' => $jumlahBayar,
                'sisa_bayar' => max(0, $totalAkhir - $jumlahBayar),
                'catatan' => $request->catatan,
                'dibuat_oleh' => auth()->id(),
            ]);

            foreach ($items as $item) {
                $produkId = (int) $item['produk_id'];
                $jumlahPesan = (float) $item['jumlah_pesan'];
                $jumlahTerima = (float) ($item['jumlah_terima'] ?? 0);
                $harga = (float) $item['harga_satuan'];
                $diskonPersen = (float) ($item['persentase_diskon'] ?? 0);
                $pajakPersen = (float) ($item['persentase_pajak'] ?? 0);

                if ($jumlahTerima > 0 && empty($item['nomor_batch'])) {
                    throw ValidationException::withMessages([
                        'items' => 'Nomor batch wajib diisi untuk item yang diterima.'
                    ]);
                }

                $lineSubtotal = $jumlahPesan * $harga;
                $lineDiskon = $lineSubtotal * ($diskonPersen / 100);
                $linePajak = ($lineSubtotal - $lineDiskon) * ($pajakPersen / 100);
                $lineTotal = $lineSubtotal - $lineDiskon + $linePajak;

                $detail = DetailPembelian::create([
                    'pembelian_id' => $pembelian->id,
                    'produk_id' => $produkId,
                    'satuan_produk_id' => $item['satuan_produk_id'] ?? null,
                    'nomor_batch' => $item['nomor_batch'] ?? null,
                    'tanggal_produksi' => $item['tanggal_produksi'] ?? null,
                    'tanggal_kadaluarsa' => $item['tanggal_kadaluarsa'] ?? null,
                    'jumlah_pesan' => $jumlahPesan,
                    'jumlah_terima' => $jumlahTerima,
                    'harga_satuan' => $harga,
                    'persentase_diskon' => $diskonPersen,
                    'jumlah_diskon' => $lineDiskon,
                    'persentase_pajak' => $pajakPersen,
                    'jumlah_pajak' => $linePajak,
                    'subtotal' => $lineSubtotal,
                    'total' => $lineTotal,
                    'catatan' => $item['catatan'] ?? null,
                ]);

                if ($jumlahTerima > 0) {
                    $stok = StokProduk::firstOrNew(['produk_id' => $produkId]);
                    $stok->jumlah = (float) ($stok->jumlah ?? 0);
                    $stok->jumlah_reservasi = (float) ($stok->jumlah_reservasi ?? 0);

                    $jumlahSebelum = $stok->jumlah;
                    $stok->jumlah = $stok->jumlah + $jumlahTerima;
                    $stok->jumlah_tersedia = $stok->jumlah - $stok->jumlah_reservasi;
                    $stok->harga_beli_terakhir = $harga;
                    $stok->terakhir_diubah = now();
                    $stok->save();

                    $batch = BatchProduk::firstOrNew([
                        'produk_id' => $produkId,
                        'nomor_batch' => $detail->nomor_batch,
                        'pembelian_id' => $pembelian->id,
                    ]);
                    $batch->pemasok_id = $pembelian->pemasok_id;
                    $batch->tanggal_produksi = $detail->tanggal_produksi;
                    $batch->tanggal_kadaluarsa = $detail->tanggal_kadaluarsa;
                    $batch->harga_beli = $harga;
                    $batch->jumlah = (float) ($batch->jumlah ?? 0) + $jumlahTerima;
                    $batch->save();

                    PergerakanStok::create([
                        'produk_id' => $produkId,
                        'batch_id' => $batch->id,
                        'jenis_pergerakan' => 'MASUK',
                        'tipe_referensi' => 'Pembelian',
                        'id_referensi' => $pembelian->id,
                        'jumlah' => $jumlahTerima,
                        'jumlah_sebelum' => $jumlahSebelum,
                        'jumlah_sesudah' => $stok->jumlah,
                        'harga_satuan' => $harga,
                        'catatan' => 'Penerimaan barang',
                        'dibuat_oleh' => auth()->id(),
                    ]);
                }
            }
        });

        return redirect()->route('transaksi.pembelian.index')->with('success', 'Pembelian berhasil dibuat');
    }

    public function show(Pembelian $pembelian)
    {
        $pembelian->load(['pemasok', 'details.produk']);

        return view('pages.transaksi.pembelian.show', compact('pembelian'));
    }

    public function destroy(Pembelian $pembelian)
    {
        $pembelian->update(['deleted_at' => now()]);

        return redirect()->route('transaksi.pembelian.index')->with('success', 'Pembelian berhasil dihapus');
    }
}

