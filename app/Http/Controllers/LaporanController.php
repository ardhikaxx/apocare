<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Pembelian;
use App\Models\Produk;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function penjualan(Request $request)
    {
        $query = Penjualan::with('pelanggan');

        if ($request->has('tanggal_mulai') && $request->has('tanggal_selesai')) {
            $query->whereBetween('tanggal_penjualan', [$request->tanggal_mulai, $request->tanggal_selesai]);
        } else {
            $query->whereMonth('tanggal_penjualan', now()->month)
                  ->whereYear('tanggal_penjualan', now()->year);
        }

        $penjualan = $query->orderBy('tanggal_penjualan', 'desc')->get();
        $totalPenjualan = $penjualan->sum('total_akhir');
        $totalTransaksi = $penjualan->count();

        return view('pages.laporan.penjualan', compact('penjualan', 'totalPenjualan', 'totalTransaksi'));
    }

    public function produk(Request $request)
    {
        $query = Produk::with('kategori');

        if ($request->has('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        $produk = $query->orderBy('nama')->get();
        $kategori = \App\Models\Kategori::where('status_aktif', true)->get();

        return view('pages.laporan.produk', compact('produk', 'kategori'));
    }

    public function stok(Request $request)
    {
        $stok = \App\Models\StokProduk::with(['produk.kategori'])->get();
        return view('pages.laporan.stok', compact('stok'));
    }
}
