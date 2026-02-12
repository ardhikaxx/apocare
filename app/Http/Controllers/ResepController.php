<?php

namespace App\Http\Controllers;

use App\Models\Resep;
use App\Models\Pelanggan;
use App\Models\Dokter;
use Illuminate\Http\Request;

class ResepController extends Controller
{
    public function index(Request $request)
    {
        $query = Resep::with(['pelanggan', 'dokter']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $resep = $query->latest()->paginate(10);
        return view('pages.resep.index', compact('resep'));
    }

    public function create()
    {
        $pelanggan = Pelanggan::where('status_aktif', true)->get();
        $dokter = Dokter::where('status_aktif', true)->get();
        return view('pages.resep.create', compact('pelanggan', 'dokter'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'dokter_id' => 'required|exists:dokter,id',
        ]);

        $nomor = 'RSP' . date('Ymd') . str_pad(Resep::count() + 1, 4, '0', STR_PAD_LEFT);

        Resep::create([
            'nomor_resep' => $nomor,
            'tanggal_resep' => now(),
            'pelanggan_id' => $request->pelanggan_id,
            'dokter_id' => $request->dokter_id,
            'diagnosa' => $request->diagnosa,
            'status' => 'PENDING',
            'total_item' => 0,
            'total_harga' => 0,
            'catatan' => $request->catatan,
            'dibuat_oleh' => auth()->id(),
        ]);

        return redirect()->route('resep.index')->with('success', 'Resep berhasil dibuat');
    }

    public function show(Resep $resep)
    {
        $resep->load(['pelanggan', 'dokter', 'details']);
        return view('pages.resep.show', compact('resep'));
    }

    public function destroy(Resep $resep)
    {
        $resep->update(['deleted_at' => now()]);
        return redirect()->route('resep.index')->with('success', 'Resep berhasil dihapus');
    }
}
