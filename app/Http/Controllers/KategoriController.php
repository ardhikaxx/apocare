<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::with('parent')->orderBy('kode')->paginate(10);
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
            'kode' => 'required|string|max:20|unique:kategori',
            'parent_id' => 'nullable|exists:kategori,id',
        ]);

        Kategori::create([
            'nama' => $request->nama,
            'kode' => $request->kode,
            'parent_id' => $request->parent_id,
            'keterangan' => $request->keterangan,
            'ikon' => $request->ikon,
            'status_aktif' => $request->status_aktif ?? true,
            'dibuat_oleh' => auth()->id(),
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan');
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

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->update(['deleted_at' => now(), 'diubah_oleh' => auth()->id()]);
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus');
    }
}
