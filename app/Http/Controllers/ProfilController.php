<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function edit()
    {
        $pengguna = auth()->user();
        return view('pages.pengguna.profil', compact('pengguna'));
    }

    public function update(Request $request)
    {
        $pengguna = auth()->user();

        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:pengguna,email,' . $pengguna->id,
            'username' => 'required|string|unique:pengguna,username,' . $pengguna->id,
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
        ]);

        $data = [
            'nama' => $request->nama,
            'email' => $request->email,
            'username' => $request->username,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'diubah_oleh' => auth()->id(),
        ];

        if ($request->password_lama && $request->password_baru) {
            if (!Hash::check($request->password_lama, $pengguna->password)) {
                return back()->withErrors(['password_lama' => 'Password lama tidak sesuai']);
            }

            $request->validate([
                'password_baru' => 'required|string|min:6|confirmed',
            ]);

            $data['password'] = Hash::make($request->password_baru);
        }

        $pengguna->update($data);

        return back()->with('success', 'Profil berhasil diperbarui');
    }
}
