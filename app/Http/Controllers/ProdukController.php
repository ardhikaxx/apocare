<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with(['kategori', 'satuan']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('kode', 'like', "%$search%")
                  ->orWhere('barcode', 'like', "%$search%");
            });
        }

        if ($request->has('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        $produk = $query->orderBy('nama')->paginate(10);
        $kategori = Kategori::where('status_aktif', true)->get();
        return view('pages.master.produk.index', compact('produk', 'kategori'));
    }

    public function create()
    {
        $kategori = Kategori::where('status_aktif', true)->get();
        $satuan = Satuan::where('status_aktif', true)->get();
        return view('pages.master.produk.create', compact('kategori', 'satuan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:200',
            'kode' => 'required|string|max:30|unique:produk',
            'barcode' => 'required|string|max:50|unique:produk',
            'kategori_id' => 'required|exists:kategori,id',
            'satuan_id' => 'required|exists:satuan,id',
        ]);

        $kode = 'PR' . str_pad(Produk::count() + 1, 6, '0', STR_PAD_LEFT);

        Produk::create([
            'kode' => $kode,
            'barcode' => $request->barcode,
            'nama' => $request->nama,
            'nama_generik' => $request->nama_generik,
            'kategori_id' => $request->kategori_id,
            'satuan_id' => $request->satuan_id,
            'produsen' => $request->produsen,
            'keterangan' => $request->keterangan,
            'jenis_produk' => $request->jenis_produk,
            'golongan_obat' => $request->golongan_obat,
            'perlu_resep' => $request->perlu_resep ?? false,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'stok_minimum' => $request->stok_minimum ?? 0,
            'stok_maksimum' => $request->stok_maksimum ?? 0,
            'titik_pesan_ulang' => $request->titik_pesan_ulang ?? 0,
            'lokasi_rak' => $request->lokasi_rak,
            'kondisi_penyimpanan' => $request->kondisi_penyimpanan,
            'status_aktif' => $request->status_aktif ?? true,
            'persentase_pajak' => $request->persentase_pajak ?? 0,
            'dibuat_oleh' => auth()->id(),
        ]);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit(Produk $produk)
    {
        $kategori = Kategori::where('status_aktif', true)->get();
        $satuan = Satuan::where('status_aktif', true)->get();
        return view('pages.master.produk.edit', compact('produk', 'kategori', 'satuan'));
    }

    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'nama' => 'required|string|max:200',
            'kode' => 'required|string|max:30|unique:produk,kode,' . $produk->id,
            'barcode' => 'required|string|max:50|unique:produk,barcode,' . $produk->id,
            'kategori_id' => 'required|exists:kategori,id',
            'satuan_id' => 'required|exists:satuan,id',
        ]);

        $produk->update([
            'nama' => $request->nama,
            'kode' => $request->kode,
            'barcode' => $request->barcode,
            'nama_generik' => $request->nama_generik,
            'kategori_id' => $request->kategori_id,
            'satuan_id' => $request->satuan_id,
            'produsen' => $request->produsen,
            'keterangan' => $request->keterangan,
            'jenis_produk' => $request->jenis_produk,
            'golongan_obat' => $request->golongan_obat,
            'perlu_resep' => $request->perlu_resep ?? false,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'stok_minimum' => $request->stok_minimum ?? 0,
            'stok_maksimum' => $request->stok_maksimum ?? 0,
            'titik_pesan_ulang' => $request->titik_pesan_ulang ?? 0,
            'lokasi_rak' => $request->lokasi_rak,
            'kondisi_penyimpanan' => $request->kondisi_penyimpanan,
            'status_aktif' => $request->status_aktif ?? true,
            'persentase_pajak' => $request->persentase_pajak ?? 0,
            'diubah_oleh' => auth()->id(),
        ]);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy(Produk $produk)
    {
        $produk->update(['deleted_at' => now(), 'diubah_oleh' => auth()->id()]);
        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus');
    }
}
