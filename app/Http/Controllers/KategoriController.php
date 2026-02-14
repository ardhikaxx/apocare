<?php

namespace App\Http\Controllers;

use App\Exports\KategoriExport;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::with('parent')->orderBy('kode')->get();
        return view('pages.master.kategori.index', compact('kategori'));
    }

    public function create()
    {
        $parents = Kategori::whereNull('parent_id')->where('status_aktif', true)->get();
        return view('pages.master.kategori.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'kode' => 'nullable|string|max:20|unique:kategori,kode',
            'parent_id' => 'nullable|exists:kategori,id',
        ]);

        $kode = $request->kode ?: ('KTG-' . str_pad((Kategori::withTrashed()->max('id') ?? 0) + 1, 4, '0', STR_PAD_LEFT));

        Kategori::create([
            'nama' => $request->nama,
            'kode' => $kode,
            'parent_id' => $request->parent_id,
            'keterangan' => $request->keterangan,
            'ikon' => $request->ikon,
            'status_aktif' => $request->status_aktif ?? true,
            'dibuat_oleh' => auth()->id(),
        ]);

        return redirect()->route('master.kategori.index')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function show(Kategori $kategori)
    {
        return view('pages.master.kategori.show', compact('kategori'));
    }

    public function edit(Kategori $kategori)
    {
        $parents = Kategori::whereNull('parent_id')->where('id', '!=', $kategori->id)->where('status_aktif', true)->get();
        return view('pages.master.kategori.edit', compact('kategori', 'parents'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'kode' => 'required|string|max:20|unique:kategori,kode,' . $kategori->id,
        ]);

        $kategori->update([
            'nama' => $request->nama,
            'kode' => $request->kode,
            'parent_id' => $request->parent_id,
            'keterangan' => $request->keterangan,
            'ikon' => $request->ikon,
            'status_aktif' => $request->status_aktif ?? true,
            'diubah_oleh' => auth()->id(),
        ]);

        return redirect()->route('master.kategori.index')->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->update(['diubah_oleh' => auth()->id()]);
        $kategori->delete();
        return redirect()->route('master.kategori.index')->with('success', 'Kategori berhasil dihapus');
    }

    public function exportExcel()
    {
        return Excel::download(new KategoriExport(), 'kategori.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new KategoriExport(), 'kategori.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPdf()
    {
        $kategori = Kategori::with('parent')->orderBy('kode')->get();
        $pdf = Pdf::loadView('print.kategori', compact('kategori'));
        return $pdf->download('kategori.pdf');
    }
}
