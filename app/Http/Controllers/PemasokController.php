<?php

namespace App\Http\Controllers;

use App\Models\Pemasok;
use Illuminate\Http\Request;

class PemasokController extends Controller
{
    public function index(Request $request)
    {
        $query = Pemasok::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('kode', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        $pemasok = $query->orderBy('nama')->paginate(10);
        return view('pages.master.pemasok.index', compact('pemasok'));
    }

    public function create()
    {
        return view('pages.master.pemasok.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:pemasok',
            'telepon' => 'required|string',
        ]);

        $kode = 'SUP' . str_pad(Pemasok::count() + 1, 4, '0', STR_PAD_LEFT);

        Pemasok::create([
            'kode' => $kode,
            'nama' => $request->nama,
            'kontak_person' => $request->kontak_person,
            'telepon' => $request->telepon,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'kota' => $request->kota,
            'provinsi' => $request->provinsi,
            'kode_pos' => $request->kode_pos,
            'npwp' => $request->npwp,
            'termin_pembayaran' => $request->termin_pembayaran ?? 0,
            'limit_kredit' => $request->limit_kredit ?? 0,
            'status_aktif' => $request->status_aktif ?? true,
            'catatan' => $request->catatan,
            'dibuat_oleh' => auth()->id(),
        ]);

        return redirect()->route('pemasok.index')->with('success', 'Pemasok berhasil ditambahkan');
    }

    public function edit(Pemasok $pemasok)
    {
        return view('pages.master.pemasok.edit', compact('pemasok'));
    }

    public function update(Request $request, Pemasok $pemasok)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:pemasok,email,' . $pemasok->id,
        ]);

        $pemasok->update([
            'nama' => $request->nama,
            'kontak_person' => $request->kontak_person,
            'telepon' => $request->telepon,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'kota' => $request->kota,
            'provinsi' => $request->provinsi,
            'kode_pos' => $request->kode_pos,
            'npwp' => $request->npwp,
            'termin_pembayaran' => $request->termin_pembayaran ?? 0,
            'limit_kredit' => $request->limit_kredit ?? 0,
            'status_aktif' => $request->status_aktif ?? true,
            'catatan' => $request->catatan,
            'diubah_oleh' => auth()->id(),
        ]);

        return redirect()->route('pemasok.index')->with('success', 'Pemasok berhasil diperbarui');
    }

    public function destroy(Pemasok $pemasok)
    {
        $pemasok->update(['deleted_at' => now(), 'diubah_oleh' => auth()->id()]);
        return redirect()->route('pemasok.index')->with('success', 'Pemasok berhasil dihapus');
    }
}
