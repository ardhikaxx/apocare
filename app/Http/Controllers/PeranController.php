<?php

namespace App\Http\Controllers;

use App\Models\Peran;
use Illuminate\Http\Request;

class PeranController extends Controller
{
    public function index()
    {
        $peran = Peran::withCount('pengguna')->orderBy('nama')->get();
        return view('pages.pengguna.peran', compact('peran'));
    }

    public function create()
    {
        return view('pages.pengguna.peran-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50|unique:peran,nama',
            'keterangan' => 'nullable|string',
        ]);

        Peran::create([
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('pengguna.peran.index')->with('success', 'Peran berhasil ditambahkan');
    }

    public function edit(Peran $peran)
    {
        return view('pages.pengguna.peran-edit', compact('peran'));
    }

    public function update(Request $request, Peran $peran)
    {
        $request->validate([
            'nama' => 'required|string|max:50|unique:peran,nama,' . $peran->id,
            'keterangan' => 'nullable|string',
        ]);

        $peran->update([
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('pengguna.peran.index')->with('success', 'Peran berhasil diperbarui');
    }

    public function destroy(Peran $peran)
    {
        if ($peran->pengguna()->exists()) {
            return redirect()->route('pengguna.peran.index')
                ->withErrors(['peran' => 'Peran tidak dapat dihapus karena masih digunakan.']);
        }

        $peran->delete();
        return redirect()->route('pengguna.peran.index')->with('success', 'Peran berhasil dihapus');
    }
}
