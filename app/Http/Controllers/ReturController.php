<?php

namespace App\Http\Controllers;

use App\Models\ReturPembelian;
use App\Models\ReturPenjualan;
use Illuminate\Http\Request;

class ReturController extends Controller
{
    public function index(Request $request)
    {
        $returPembelian = ReturPembelian::with(['pembelian', 'pemasok'])
            ->latest('tanggal_retur')
            ->get();
        $returPenjualan = ReturPenjualan::with(['penjualan', 'pelanggan'])
            ->latest('tanggal_retur')
            ->get();

        return view('pages.transaksi.retur.index', compact('returPembelian', 'returPenjualan'));
    }
}
