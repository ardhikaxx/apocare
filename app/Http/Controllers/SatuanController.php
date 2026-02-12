<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    public function index()
    {
        $satuan = Satuan::orderBy('kode')->paginate(10);
        return view('pages.master.satuan.index', compact('satuan'));
    }

    public function create()
    {
        return view('pages.master.satuan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'kode' => 'required|string|max:10|unique:satuan',
        ]);

        Satuan::create([
            'nama' => $request->nama,
            'kode' => $request->kode,
            'keterangan' => $request->keterangan,
            'status_aktif' => $request->status_aktif ?? true,
        ]);

        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil ditambahkan');
    }

    public function edit(Satuan $satuan)
    {
        return view('pages.master.satuan.edit', compact('satuan'));
    }

    public function update(Request $request, Satuan $satuan)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'kode' => 'required|string|max:10|unique:satuan,kode,' . $satuan->id,
        ]);

        $satuan->update([
            'nama' => $request->nama,
            'kode' => $request->kode,
            'keterangan' => $request->keterangan,
            'status_aktif' => $request->status_aktif ?? true,
        ]);

        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil diperbarui');
    }

    public function destroy(Satuan $satuan)
    {
        $satuan->delete();
        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil dihapus');
    }
}
