<?php

namespace App\Http\Controllers;

use App\Models\StokProduk;
use App\Models\Produk;
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function index(Request $request)
    {
        $query = StokProduk::with('produk.kategori');

        if ($request->has('kategori')) {
            $query->whereHas('produk', function($q) use ($request) {
                $q->where('kategori_id', $request->kategori);
            });
        }

        $stok = $query->paginate(10);
        $kategori = \App\Models\Kategori::where('status_aktif', true)->get();
        return view('pages.persediaan.stok.index', compact('stok', 'kategori'));
    }

    public function show($id)
    {
        $stok = StokProduk::with(['produk.kategori', 'produk.batchProduk'])->findOrFail($id);
        return view('pages.persediaan.stok.show', compact('stok'));
    }
}
