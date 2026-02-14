<?php

namespace App\Http\Controllers;

use App\Models\BatchProduk;
use App\Models\Kategori;
use App\Models\StokProduk;
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function index(Request $request)
    {
        $query = StokProduk::with(['produk.kategori', 'produk.batchProduk']);

        if ($request->filled('kategori')) {
            $query->whereHas('produk', function ($q) use ($request) {
                $q->where('kategori_id', $request->kategori);
            });
        }

        if ($request->filled('lokasi_rak')) {
            $query->whereHas('produk', function ($q) use ($request) {
                $q->where('lokasi_rak', 'like', '%' . $request->lokasi_rak . '%');
            });
        }

        $stok = $query->get();
        $kategori = Kategori::where('status_aktif', true)->get();

        $totalItem = $stok->count();
        $stokMinimum = $stok->filter(function ($item) {
            $min = (float) ($item->produk->stok_minimum ?? 0);
            return $min > 0 && (float) $item->jumlah_tersedia <= $min;
        })->count();
        $totalReserved = $stok->sum('jumlah_reservasi');

        $batchExpired = BatchProduk::whereNotNull('tanggal_kadaluarsa')
            ->whereDate('tanggal_kadaluarsa', '<', today())
            ->where('jumlah', '>', 0)
            ->count();

        return view('pages.persediaan.stok.index', compact(
            'stok', 'kategori', 'totalItem', 'stokMinimum', 'totalReserved', 'batchExpired'
        ));
    }

    public function show($id)
    {
        $stok = StokProduk::with(['produk.kategori', 'produk.batchProduk'])->findOrFail($id);

        return view('pages.persediaan.stok.show', compact('stok'));
    }
}
