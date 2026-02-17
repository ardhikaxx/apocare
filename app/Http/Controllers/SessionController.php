<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Pengguna;

class SessionController extends Controller
{
    public function index()
    {
        $sessions = Pengguna::where('is_online', true)
            ->where('last_login_at', '>', now()->subMinutes(30))
            ->orderBy('last_login_at', 'desc')
            ->get();

        return view('session.index', compact('sessions'));
    }

    public function refresh()
    {
        $user = Auth::user();
        
        $sessions = Pengguna::where('is_online', true)
            ->where('last_login_at', '>', now()->subMinutes(30))
            ->orderBy('last_login_at', 'desc')
            ->get();

        return view('session.partials.session-list', compact('sessions'))->render();
    }

    public function forceLogout(Pengguna $pengguna)
    {
        if ($pengguna->id === Auth::id()) {
            return redirect()->back()->with('error', 'Tidak dapat logout sesi sendiri.');
        }

        $pengguna->update(['is_online' => false]);
        
        return redirect()->back()->with('success', 'User berhasil di-force logout.');
    }

    public function history()
    {
        $histories = Pengguna::whereNotNull('last_login_at')
            ->orderBy('last_login_at', 'desc')
            ->limit(50)
            ->get();

        return view('session.history', compact('histories'));
    }
}
