<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Pelanggan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        $query = Penjualan::with('pelanggan');

        if ($request->has('tanggal_mulai') && $request->has('tanggal_selesai')) {
            $query->whereBetween('tanggal_penjualan', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        $penjualan = $query->latest()->paginate(10);
        return view('pages.transaksi.penjualan.index', compact('penjualan'));
    }

    public function create()
    {
        $pelanggan = Pelanggan::where('status_aktif', true)->get();
        $produk = Produk::with('kategori')->where('status_aktif', true)->get();
        return view('pages.transaksi.penjualan.create', compact('pelanggan', 'produk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'nullable|exists:pelanggan,id',
        ]);

        $nomor = 'SL' . date('Ymd') . str_pad(Penjualan::count() + 1, 4, '0', STR_PAD_LEFT);

        $subtotal = 0;
        $jumlahPajak = 0;
        $jumlahDiskon = 0;
        $totalAkhir = 0;

        Penjualan::create([
            'nomor_penjualan' => $nomor,
            'pelanggan_id' => $request->pelanggan_id,
            'tanggal_penjualan' => now(),
            'jenis_penjualan' => 'RETAIL',
            'status_pembayaran' => 'LUNAS',
            'metode_pembayaran' => $request->metode_pembayaran ?? 'TUNAI',
            'subtotal' => $subtotal,
            'jenis_diskon' => 'PERSENTASE',
            'nilai_diskon' => 0,
            'jumlah_diskon' => 0,
            'jumlah_pajak' => $jumlahPajak,
            'total_akhir' => $totalAkhir,
            'jumlah_bayar' => $request->jumlah_bayar,
            'jumlah_kembalian' => $request->jumlah_bayar - $totalAkhir,
            'catatan' => $request->catatan,
            'dibuat_oleh' => auth()->id(),
        ]);

        return redirect()->route('penjualan.index')->with('success', 'Penjualan berhasil disimpan');
    }

    public function show(Penjualan $penjualan)
    {
        $penjualan->load(['pelanggan', 'details.produk']);
        return view('pages.transaksi.penjualan.show', compact('penjualan'));
    }

    public function destroy(Penjualan $penjualan)
    {
        $penjualan->update(['deleted_at' => now()]);
        return redirect()->route('penjualan.index')->with('success', 'Penjualan berhasil dihapus');
    }
}
