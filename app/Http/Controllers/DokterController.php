<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use Illuminate\Http\Request;

class DokterController extends Controller
{
    public function index(Request $request)
    {
        $query = Dokter::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('kode', 'like', "%$search%");
            });
        }

        $dokter = $query->orderBy('nama')->paginate(10);
        return view('pages.dokter.index', compact('dokter'));
    }

    public function create()
    {
        return view('pages.dokter.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'spesialisasi' => 'required|string|max:100',
        ]);

        $kode = 'DKT' . str_pad(Dokter::count() + 1, 4, '0', STR_PAD_LEFT);

        Dokter::create([
            'kode' => $kode,
            'nama' => $request->nama,
            'spesialisasi' => $request->spesialisasi,
            'nomor_sip' => $request->nomor_sip,
            'telepon' => $request->telepon,
            'email' => $request->email,
            'rumah_sakit' => $request->rumah_sakit,
            'alamat' => $request->alamat,
            'status_aktif' => $request->status_aktif ?? true,
            'catatan' => $request->catatan,
            'dibuat_oleh' => auth()->id(),
        ]);

        return redirect()->route('dokter.index')->with('success', 'Dokter berhasil ditambahkan');
    }

    public function edit(Dokter $dokter)
    {
        return view('pages.dokter.edit', compact('dokter'));
    }

    public function update(Request $request, Dokter $dokter)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
        ]);

        $dokter->update([
            'nama' => $request->nama,
            'spesialisasi' => $request->spesialisasi,
            'nomor_sip' => $request->nomor_sip,
            'telepon' => $request->telepon,
            'email' => $request->email,
            'rumah_sakit' => $request->rumah_sakit,
            'alamat' => $request->alamat,
            'status_aktif' => $request->status_aktif ?? true,
            'catatan' => $request->catatan,
            'diubah_oleh' => auth()->id(),
        ]);

        return redirect()->route('dokter.index')->with('success', 'Dokter berhasil diperbarui');
    }

    public function destroy(Dokter $dokter)
    {
        $dokter->update(['deleted_at' => now(), 'diubah_oleh' => auth()->id()]);
        return redirect()->route('dokter.index')->with('success', 'Dokter berhasil dihapus');
    }
}
