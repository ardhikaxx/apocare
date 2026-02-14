<?php

namespace App\Http\Controllers;

use App\Models\HakAkses;
use Illuminate\Http\Request;

class HakAksesController extends Controller
{
    public function index()
    {
        $hakAkses = HakAkses::orderBy('modul')->orderBy('nama')->get();
        return view('pages.pengguna.hak-akses', compact('hakAkses'));
    }

    public function create()
    {
        return view('pages.pengguna.hak-akses-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'kode' => 'required|string|max:50|unique:hak_akses,kode',
            'modul' => 'required|string|max:50',
        ]);

        HakAkses::create([
            'nama' => $request->nama,
            'kode' => $request->kode,
            'modul' => $request->modul,
        ]);

        return redirect()->route('pengguna.hak-akses.index')->with('success', 'Hak akses berhasil ditambahkan');
    }

    public function edit(HakAkses $hak_akse)
    {
        return view('pages.pengguna.hak-akses-edit', ['hakAkses' => $hak_akse]);
    }

    public function update(Request $request, HakAkses $hak_akse)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'kode' => 'required|string|max:50|unique:hak_akses,kode,' . $hak_akse->id,
            'modul' => 'required|string|max:50',
        ]);

        $hak_akse->update([
            'nama' => $request->nama,
            'kode' => $request->kode,
            'modul' => $request->modul,
        ]);

        return redirect()->route('pengguna.hak-akses.index')->with('success', 'Hak akses berhasil diperbarui');
    }

    public function destroy(HakAkses $hak_akse)
    {
        $hak_akse->delete();
        return redirect()->route('pengguna.hak-akses.index')->with('success', 'Hak akses berhasil dihapus');
    }
}
