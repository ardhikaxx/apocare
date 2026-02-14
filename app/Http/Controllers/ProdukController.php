<?php

namespace App\Http\Controllers;

use App\Exports\ProdukExport;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $produk = Produk::with(['kategori', 'satuan'])->orderBy('nama')->get();
        return view('pages.master.produk.index', compact('produk'));
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
            'kode' => 'nullable|string|max:30|unique:produk,kode',
            'barcode' => 'nullable|string|max:50|unique:produk,barcode',
            'kategori_id' => 'required|exists:kategori,id',
            'satuan_id' => 'required|exists:satuan,id',
        ]);

        $kode = $request->kode ?: ('PRD-' . str_pad((Produk::withTrashed()->max('id') ?? 0) + 1, 6, '0', STR_PAD_LEFT));

        Produk::create([
            'kode' => $kode,
            'barcode' => $request->barcode,
            'nama' => $request->nama,
            'nama_generik' => $request->nama_generik,
            'kategori_id' => $request->kategori_id,
            'satuan_id' => $request->satuan_id,
            'produsen' => $request->produsen,
            'keterangan' => $request->keterangan,
            'jenis_produk' => $request->jenis_produk ?? 'Obat',
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

        return redirect()->route('master.produk.index')->with('success', 'Produk berhasil ditambahkan');
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
            'barcode' => 'nullable|string|max:50|unique:produk,barcode,' . $produk->id,
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
            'jenis_produk' => $request->jenis_produk ?? $produk->jenis_produk,
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

        return redirect()->route('master.produk.index')->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy(Produk $produk)
    {
        $produk->update(['diubah_oleh' => auth()->id()]);
        $produk->delete();
        return redirect()->route('master.produk.index')->with('success', 'Produk berhasil dihapus');
    }

    public function exportExcel()
    {
        return Excel::download(new ProdukExport(), 'produk.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new ProdukExport(), 'produk.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPdf()
    {
        $produk = Produk::with(['kategori', 'satuan'])->orderBy('nama')->get();
        $pdf = Pdf::loadView('print.produk', compact('produk'));
        return $pdf->download('produk.pdf');
    }
}
