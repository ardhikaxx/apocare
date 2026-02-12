<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Pemasok;
use Illuminate\Http\Request;

class PembelianController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembelian::with('pemasok');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $pembelian = $query->latest()->paginate(10);
        return view('pages.transaksi.pembelian.index', compact('pembelian'));
    }

    public function create()
    {
        $pemasok = Pemasok::where('status_aktif', true)->get();
        return view('pages.transaksi.pembelian.create', compact('pemasok'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pemasok_id' => 'required|exists:pemasok,id',
        ]);

        $nomor = 'PO' . date('Ymd') . str_pad(Pembelian::count() + 1, 4, '0', STR_PAD_LEFT);

        Pembelian::create([
            'nomor_pembelian' => $nomor,
            'pemasok_id' => $request->pemasok_id,
            'tanggal_pembelian' => now(),
            'tanggal_jatuh_tempo' => now()->addDays(30),
            'status' => 'DRAFT',
            'status_pembayaran' => 'BELUM_BAYAR',
            'subtotal' => 0,
            'jenis_diskon' => 'PERSENTASE',
            'nilai_diskon' => 0,
            'jumlah_diskon' => 0,
            'jumlah_pajak' => 0,
            'biaya_kirim' => 0,
            'biaya_lain' => 0,
            'total_akhir' => 0,
            'jumlah_bayar' => 0,
            'sisa_bayar' => 0,
            'catatan' => $request->catatan,
            'dibuat_oleh' => auth()->id(),
        ]);

        return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil dibuat');
    }

    public function show(Pembelian $pembelian)
    {
        $pembelian->load(['pemasok', 'details']);
        return view('pages.transaksi.pembelian.show', compact('pembelian'));
    }

    public function destroy(Pembelian $pembelian)
    {
        $pembelian->update(['deleted_at' => now()]);
        return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil dihapus');
    }
}
