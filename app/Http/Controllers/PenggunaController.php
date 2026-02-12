<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Models\Peran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengguna::with('role');

        if ($request->has('role')) {
            $query->where('role_id', $request->role);
        }

        $pengguna = $query->orderBy('nama')->paginate(10);
        $peran = Peran::all();
        return view('pages.pengguna.index', compact('pengguna', 'peran'));
    }

    public function create()
    {
        $peran = Peran::all();
        return view('pages.pengguna.create', compact('peran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:pengguna',
            'username' => 'required|string|unique:pengguna',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:peran,id',
        ]);

        Pengguna::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'status_aktif' => $request->status_aktif ?? true,
            'dibuat_oleh' => auth()->id(),
        ]);

        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil ditambahkan');
    }

    public function edit(Pengguna $pengguna)
    {
        $peran = Peran::all();
        return view('pages.pengguna.edit', compact('pengguna', 'peran'));
    }

    public function update(Request $request, Pengguna $pengguna)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:pengguna,email,' . $pengguna->id,
            'username' => 'required|string|unique:pengguna,username,' . $pengguna->id,
        ]);

        $data = [
            'nama' => $request->nama,
            'email' => $request->email,
            'username' => $request->username,
            'role_id' => $request->role_id,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'status_aktif' => $request->status_aktif ?? true,
            'diubah_oleh' => auth()->id(),
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $pengguna->update($data);

        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil diperbarui');
    }

    public function destroy(Pengguna $pengguna)
    {
        $pengguna->update(['deleted_at' => now(), 'diubah_oleh' => auth()->id()]);
        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil dihapus');
    }
}
