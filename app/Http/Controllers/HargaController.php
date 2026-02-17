<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\PengaturanHarga;
use Illuminate\Support\Facades\Auth;

class HargaController extends Controller
{
    public function index(Request $request)
    {
        $kategori = Kategori::where('status_aktif', true)->get();
        
        $query = Produk::with(['kategori', 'satuan']);
        
        if ($request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }
        
        if ($request->search) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('kode', 'like', '%' . $request->search . '%');
        }
        
        $produk = $query->orderBy('nama')->get();
        
        $pengaturan = PengaturanHarga::first() ?? new PengaturanHarga([
            'persentase_markup_default' => 20,
            'status_aktif' => true
        ]);
        
        return view('harga.index', compact('produk', 'kategori', 'pengaturan'));
    }

    public function updatePersentase(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'persentase_markup' => 'required|numeric|min:0|max:100',
        ]);

        $produk = Produk::findOrFail($request->produk_id);
        $hargaBaru = $produk->harga_beli + ($produk->harga_beli * $request->persentase_markup / 100);
        
        $produk->update([
            'persentase_markup' => $request->persentase_markup,
            'harga_jual' => round($hargaBaru),
            'diubah_oleh' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Harga berhasil diperbarui',
            'harga_jual' => number_format($hargaBaru, 0, ',', '.'),
        ]);
    }

    public function updateSemua(Request $request)
    {
        $request->validate([
            'persentase_markup' => 'required|numeric|min:0|max:100',
            'kategori_id' => 'nullable|exists:kategori,id',
        ]);

        $query = Produk::query();
        
        if ($request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }
        
        $produk = $query->get();
        $count = 0;
        
        foreach ($produk as $item) {
            if ($item->harga_beli > 0) {
                $hargaBaru = $item->harga_beli + ($item->harga_beli * $request->persentase_markup / 100);
                $item->update([
                    'persentase_markup' => $request->persentase_markup,
                    'harga_jual' => round($hargaBaru),
                    'diubah_oleh' => Auth::id(),
                ]);
                $count++;
            }
        }

        return redirect()->back()->with('success', "Berhasil update {$count} produk dengan markup {$request->persentase_markup}%");
    }

    public function hitung(Request $request)
    {
        $hargaBeli = (float) $request->harga_beli;
        $persentase = (float) $request->persentase_markup;
        
        $hargaJual = $hargaBeli + ($hargaBeli * $persentase / 100);
        $keuntungan = $hargaJual - $hargaBeli;
        
        return response()->json([
            'harga_jual' => number_format($hargaJual, 0, ',', '.'),
            'harga_jual_raw' => $hargaJual,
            'keuntungan' => number_format($keuntungan, 0, ',', '.'),
            'keuntungan_raw' => $keuntungan,
        ]);
    }

    public function simpanPengaturan(Request $request)
    {
        $request->validate([
            'persentase_markup_default' => 'required|numeric|min:0|max:100',
        ]);

        $pengaturan = PengaturanHarga::first();
        
        if ($pengaturan) {
            $pengaturan->update([
                'persentase_markup_default' => $request->persentase_markup_default,
                'status_aktif' => $request->has('status_aktif'),
            ]);
        } else {
            PengaturanHarga::create([
                'persentase_markup_default' => $request->persentase_markup_default,
                'status_aktif' => $request->has('status_aktif'),
            ]);
        }

        return redirect()->back()->with('success', 'Pengaturan markup default berhasil disimpan');
    }
}
