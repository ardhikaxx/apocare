<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawan = Karyawan::with('pengguna')->orderBy('nomor_karyawan')->get();
        return view('pages.karyawan.index', compact('karyawan'));
    }

    public function create()
    {
        $pengguna = Pengguna::orderBy('nama')->get();
        return view('pages.karyawan.create', compact('pengguna'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pengguna_id' => 'required|exists:pengguna,id',
            'nomor_karyawan' => 'required|string|max:20|unique:karyawan,nomor_karyawan',
        ]);

        Karyawan::create([
            'pengguna_id' => $request->pengguna_id,
            'nomor_karyawan' => $request->nomor_karyawan,
            'jabatan' => $request->jabatan,
            'departemen' => $request->departemen,
            'status_kepegawaian' => $request->status_kepegawaian,
            'tanggal_bergabung' => $request->tanggal_bergabung,
            'status_aktif' => $request->status_aktif ?? true,
            'catatan' => $request->catatan,
            'dibuat_oleh' => Auth::id(),
        ]);

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil ditambahkan');
    }

    public function edit(Karyawan $karyawan)
    {
        $pengguna = Pengguna::orderBy('nama')->get();
        return view('pages.karyawan.edit', compact('karyawan', 'pengguna'));
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'pengguna_id' => 'required|exists:pengguna,id',
            'nomor_karyawan' => 'required|string|max:20|unique:karyawan,nomor_karyawan,' . $karyawan->id,
        ]);

        $karyawan->update([
            'pengguna_id' => $request->pengguna_id,
            'nomor_karyawan' => $request->nomor_karyawan,
            'jabatan' => $request->jabatan,
            'departemen' => $request->departemen,
            'status_kepegawaian' => $request->status_kepegawaian,
            'tanggal_bergabung' => $request->tanggal_bergabung,
            'status_aktif' => $request->status_aktif ?? true,
            'catatan' => $request->catatan,
            'diubah_oleh' => Auth::id(),
        ]);

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil diperbarui');
    }

    public function destroy(Karyawan $karyawan)
    {
        $karyawan->update(['diubah_oleh' => Auth::id()]);
        $karyawan->delete();
        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil dihapus');
    }
}
