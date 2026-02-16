<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualan;
use App\Models\DetailResep;
use App\Models\Dokter;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\PergerakanStok;
use App\Models\Produk;
use App\Models\Resep;
use App\Models\StokProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ResepController extends Controller
{
    public function index(Request $request)
    {
        $query = Resep::with(['pelanggan', 'dokter']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tahap_antrian')) {
            $query->where('tahap_antrian', $request->tahap_antrian);
        }

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal_resep', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        $resep = $query->latest('tanggal_resep')->get();

        $ringkasanTahap = [
            Resep::TAHAP_DITERIMA => Resep::where('tahap_antrian', Resep::TAHAP_DITERIMA)->count(),
            Resep::TAHAP_DIRACIK => Resep::where('tahap_antrian', Resep::TAHAP_DIRACIK)->count(),
            Resep::TAHAP_DIVERIFIKASI => Resep::where('tahap_antrian', Resep::TAHAP_DIVERIFIKASI)->count(),
            Resep::TAHAP_DISERAHKAN => Resep::where('tahap_antrian', Resep::TAHAP_DISERAHKAN)->count(),
        ];

        return view('pages.resep.index', compact('resep', 'ringkasanTahap'));
    }

    public function create()
    {
        $pelanggan = Pelanggan::where('status_aktif', true)->get();
        $dokter = Dokter::where('status_aktif', true)->get();
        $produk = Produk::where('status_aktif', true)->orderBy('nama')->get();

        return view('pages.resep.create', compact('pelanggan', 'dokter', 'produk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'dokter_id' => 'required|exists:dokter,id',
            'tanggal_resep' => 'required|date',
            'diagnosa' => 'nullable|string',
            'catatan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.dosis' => 'nullable|string',
            'items.*.frekuensi' => 'nullable|string',
            'items.*.durasi' => 'nullable|string',
            'items.*.cara_pakai' => 'nullable|string',
            'items.*.jumlah_resep' => 'required|numeric|min:0.01',
            'items.*.harga_satuan' => 'required|numeric|min:0',
            'items.*.instruksi' => 'nullable|string',
            'items.*.catatan' => 'nullable|string',
        ]);

        $items = $request->input('items', []);

        DB::transaction(function () use ($request, $items) {
            $nomor = 'RSP' . date('Ymd') . str_pad(Resep::withTrashed()->count() + 1, 4, '0', STR_PAD_LEFT);

            $totalHarga = 0;
            foreach ($items as $item) {
                $totalHarga += (float) $item['jumlah_resep'] * (float) $item['harga_satuan'];
            }

            $resep = Resep::create([
                'nomor_resep' => $nomor,
                'tanggal_resep' => $request->tanggal_resep,
                'pelanggan_id' => $request->pelanggan_id,
                'dokter_id' => $request->dokter_id,
                'diagnosa' => $request->diagnosa,
                'status' => 'PENDING',
                'tahap_antrian' => Resep::TAHAP_DITERIMA,
                'total_item' => count($items),
                'total_harga' => $totalHarga,
                'catatan' => $request->catatan,
                'apoteker_id' => Auth::id(),
                'waktu_diterima' => now(),
                'dibuat_oleh' => Auth::id(),
            ]);

            foreach ($items as $item) {
                $jumlah = (float) $item['jumlah_resep'];
                $harga = (float) $item['harga_satuan'];

                DetailResep::create([
                    'resep_id' => $resep->id,
                    'produk_id' => $item['produk_id'],
                    'dosis' => $item['dosis'] ?? null,
                    'frekuensi' => $item['frekuensi'] ?? null,
                    'durasi' => $item['durasi'] ?? null,
                    'cara_pakai' => $item['cara_pakai'] ?? null,
                    'jumlah_resep' => $jumlah,
                    'jumlah_diberikan' => 0,
                    'harga_satuan' => $harga,
                    'total' => $jumlah * $harga,
                    'instruksi' => $item['instruksi'] ?? null,
                    'catatan' => $item['catatan'] ?? null,
                ]);
            }
        });

        return redirect()->route('resep.index')->with('success', 'Resep berhasil dibuat dan masuk antrian.');
    }

    public function show(Resep $resep)
    {
        $resep->load(['pelanggan', 'dokter', 'details.produk']);

        return view('pages.resep.show', compact('resep'));
    }

    public function destroy(Resep $resep)
    {
        $resep->update(['deleted_at' => now()]);

        return redirect()->route('resep.index')->with('success', 'Resep berhasil dihapus');
    }

    public function updateTahap(Request $request, Resep $resep)
    {
        $respondError = function (string $message, int $status = 422) use ($request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], $status);
            }

            return back()->withErrors(['tahap_antrian' => $message]);
        };

        $request->validate([
            'tahap_antrian' => 'required|in:DITERIMA,DIRACIK,DIVERIFIKASI,DISERAHKAN',
        ]);

        if (in_array($resep->status, ['BATAL', 'SELESAI'], true)) {
            return $respondError('Resep dengan status ini tidak bisa diubah tahapnya.');
        }

        $targetTahap = $request->input('tahap_antrian');
        $currentIndex = array_search($resep->tahap_antrian, Resep::TAHAP_URUT, true);
        $targetIndex = array_search($targetTahap, Resep::TAHAP_URUT, true);

        if ($currentIndex === false || $targetIndex === false) {
            return $respondError('Tahap antrian tidak valid.');
        }

        if ($targetIndex !== ($currentIndex + 1)) {
            return $respondError('Perpindahan tahap harus berurutan.');
        }

        $updateData = [
            'tahap_antrian' => $targetTahap,
            'diubah_oleh' => Auth::id(),
        ];

        if ($targetTahap === Resep::TAHAP_DIRACIK) {
            $updateData['waktu_diracik'] = now();
        }

        if ($targetTahap === Resep::TAHAP_DIVERIFIKASI) {
            $updateData['waktu_verifikasi'] = now();
            $updateData['status'] = 'SEBAGIAN';
        }

        if ($targetTahap === Resep::TAHAP_DISERAHKAN) {
            $updateData['waktu_diserahkan'] = now();
            $updateData['status'] = 'SELESAI';
        }

        $resep->update($updateData);
        $resep->refresh();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Tahap resep berhasil diubah ke {$targetTahap}.",
                'data' => [
                    'id' => $resep->id,
                    'tahap_antrian' => $resep->tahap_antrian,
                    'status' => $resep->status,
                ],
            ]);
        }

        return back()->with('success', "Tahap resep berhasil diubah ke {$targetTahap}.");
    }

    public function createPenjualan(Resep $resep)
    {
        if ($resep->status === 'SELESAI') {
            return redirect()->route('resep.index')->withErrors([
                'resep' => 'Resep ini sudah dibuatkan penjualan.',
            ]);
        }

        if (! in_array($resep->tahap_antrian, [Resep::TAHAP_DIVERIFIKASI, Resep::TAHAP_DISERAHKAN], true)) {
            return redirect()->route('resep.index')->withErrors([
                'resep' => 'Resep hanya bisa diproses penjualan setelah tahap DIVERIFIKASI.',
            ]);
        }

        $resep->load(['pelanggan', 'dokter', 'details.produk']);

        return view('pages.resep.penjualan', compact('resep'));
    }

    public function storePenjualan(Request $request, Resep $resep)
    {
        if ($resep->status === 'SELESAI') {
            return back()->withErrors(['resep' => 'Resep ini sudah diproses ke penjualan.']);
        }

        $request->validate([
            'metode_pembayaran' => 'required|in:TUNAI,DEBIT,KREDIT,TRANSFER,EWALLET,QRIS',
            'jumlah_bayar' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        if (! in_array($resep->tahap_antrian, [Resep::TAHAP_DIVERIFIKASI, Resep::TAHAP_DISERAHKAN], true)) {
            return back()->withErrors(['resep' => 'Resep belum siap diproses ke penjualan.']);
        }

        $resep->load(['details']);

        DB::transaction(function () use ($request, $resep) {
            $nomor = 'SL' . date('Ymd') . str_pad(Penjualan::withTrashed()->count() + 1, 4, '0', STR_PAD_LEFT);

            $subtotal = $resep->details->sum('total');
            $totalAkhir = $subtotal;

            $jumlahBayar = (float) $request->jumlah_bayar;
            $statusPembayaran = 'BELUM_BAYAR';
            if ($jumlahBayar >= $totalAkhir && $totalAkhir > 0) {
                $statusPembayaran = 'LUNAS';
            } elseif ($jumlahBayar > 0 && $jumlahBayar < $totalAkhir) {
                $statusPembayaran = 'SEBAGIAN';
            }

            $penjualan = Penjualan::create([
                'nomor_penjualan' => $nomor,
                'pelanggan_id' => $resep->pelanggan_id,
                'resep_id' => $resep->id,
                'tanggal_penjualan' => now(),
                'jenis_penjualan' => 'RESEP',
                'status_pembayaran' => $statusPembayaran,
                'metode_pembayaran' => $request->metode_pembayaran,
                'subtotal' => $subtotal,
                'jenis_diskon' => 'NOMINAL',
                'nilai_diskon' => 0,
                'jumlah_diskon' => 0,
                'jumlah_pajak' => 0,
                'total_akhir' => $totalAkhir,
                'jumlah_bayar' => $jumlahBayar,
                'jumlah_kembalian' => max(0, $jumlahBayar - $totalAkhir),
                'catatan' => $request->catatan,
                'dilayani_oleh' => Auth::id(),
                'dibuat_oleh' => Auth::id(),
            ]);

            foreach ($resep->details as $detail) {
                $produkId = $detail->produk_id;
                $jumlah = (float) $detail->jumlah_resep;
                $harga = (float) $detail->harga_satuan;

                $stok = StokProduk::firstOrNew(['produk_id' => $produkId]);
                $stok->jumlah = (float) ($stok->jumlah ?? 0);
                $stok->jumlah_reservasi = (float) ($stok->jumlah_reservasi ?? 0);

                if ($stok->jumlah < $jumlah) {
                    throw ValidationException::withMessages([
                        'items' => 'Stok produk tidak mencukupi untuk penjualan resep.'
                    ]);
                }

                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'produk_id' => $produkId,
                    'satuan_produk_id' => null,
                    'batch_id' => null,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $harga,
                    'persentase_diskon' => 0,
                    'jumlah_diskon' => 0,
                    'persentase_pajak' => 0,
                    'jumlah_pajak' => 0,
                    'subtotal' => $detail->total,
                    'total' => $detail->total,
                    'catatan' => $detail->catatan,
                ]);

                $jumlahSebelum = $stok->jumlah;
                $stok->jumlah = $stok->jumlah - $jumlah;
                $stok->jumlah_tersedia = $stok->jumlah - $stok->jumlah_reservasi;
                $stok->terakhir_diubah = now();
                $stok->save();

                PergerakanStok::create([
                    'produk_id' => $produkId,
                    'batch_id' => null,
                    'jenis_pergerakan' => 'KELUAR',
                    'tipe_referensi' => 'Penjualan',
                    'id_referensi' => $penjualan->id,
                    'jumlah' => $jumlah,
                    'jumlah_sebelum' => $jumlahSebelum,
                    'jumlah_sesudah' => $stok->jumlah,
                    'harga_satuan' => $harga,
                    'catatan' => 'Penjualan resep',
                    'dibuat_oleh' => Auth::id(),
                ]);
            }

            $resep->update([
                'status' => 'SELESAI',
                'tahap_antrian' => Resep::TAHAP_DISERAHKAN,
                'waktu_diserahkan' => now(),
                'total_harga' => $subtotal,
            ]);
        });

        return redirect()->route('resep.index')->with('success', 'Penjualan resep berhasil dibuat.');
    }
}