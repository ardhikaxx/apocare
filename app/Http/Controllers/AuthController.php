<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;
use App\Models\Peran;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        $credentials = filter_var($request->login, FILTER_VALIDATE_EMAIL)
            ? ['email' => $request->login, 'password' => $request->password, 'status_aktif' => true]
            : ['username' => $request->login, 'password' => $request->password, 'status_aktif' => true];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user instanceof Pengguna) {
                $user->update([
                    'login_terakhir' => now(),
                    'last_login_at' => now(),
                    'last_login_ip' => $request->ip(),
                    'is_online' => true,
                ]);
                return redirect()->route($this->resolveRedirectByRole($user));
            }

            return redirect()->route('dashboard');
        }

        return back()->withErrors(['login' => 'Email/username atau password salah']);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:pengguna',
            'username' => 'required|string|unique:pengguna',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $defaultRole = Peran::firstOrCreate(
            ['nama' => 'Admin'],
            ['keterangan' => 'Administrator Sistem']
        );

        Pengguna::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role_id' => $defaultRole->id,
            'status_aktif' => true,
        ]);

        $credentials = ['username' => $request->username, 'password' => $request->password];
        Auth::attempt($credentials);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    private function resolveRedirectByRole(Pengguna $user): string
    {
        $role = $this->normalizeRoleName(optional($user->role)->nama);

        return match ($role) {
            'admin' => 'dashboard',
            'apoteker' => 'resep.index',
            'kasir' => 'transaksi.penjualan.index',
            'gudang' => 'persediaan.stok.index',
            default => 'dashboard',
        };
    }

    private function normalizeRoleName(?string $roleName): string
    {
        if (! is_string($roleName)) {
            return '';
        }

        $normalized = strtolower(trim(preg_replace('/\s+/', ' ', $roleName) ?? ''));

        return match ($normalized) {
            'staf gudang' => 'gudang',
            default => $normalized,
        };
    }
}
