<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\Pembelian;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProduk = Produk::count();
        $totalPelanggan = Pelanggan::count();
        $totalPemasok = \App\Models\Pemasok::count();

        $penjualanHariIni = Penjualan::whereDate('tanggal_penjualan', today())->sum('total_akhir');
        $penjualanBulanIni = Penjualan::whereMonth('tanggal_penjualan', now()->month)
            ->whereYear('tanggal_penjualan', now()->year)
            ->sum('total_akhir');

        $transaksiHariIni = Penjualan::whereDate('tanggal_penjualan', today())->count();

        $stokMenipis = Produk::whereRaw('stok_minimum > 0')
            ->whereHas('stokProduk', function($q) {
                $q->whereRaw('jumlah_tersedia <= (SELECT stok_minimum FROM produk WHERE id = produk_id)');
            })->count();

        $penjualanTerakhir = Penjualan::with('pelanggan')
            ->latest()
            ->limit(5)
            ->get();

        return view('pages.dashboard.index', compact(
            'totalProduk', 'totalPelanggan', 'totalPemasok',
            'penjualanHariIni', 'penjualanBulanIni', 'transaksiHariIni',
            'stokMenipis', 'penjualanTerakhir'
        ));
    }
}
