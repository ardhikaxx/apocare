<?php

namespace App\Http\Controllers;

use App\Models\DetailPenyesuaianStok;
use App\Models\PenyesuaianStok;
use App\Models\PergerakanStok;
use App\Models\Produk;
use App\Models\StokProduk;
use App\Models\BatchProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PenyesuaianController extends Controller
{
    public function index(Request $request)
    {
        $query = PenyesuaianStok::with('detail');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal_penyesuaian', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        $penyesuaian = $query->latest('tanggal_penyesuaian')->get();

        return view('pages.persediaan.penyesuaian.index', compact('penyesuaian'));
    }

    public function create()
    {
        $produk = Produk::with(['stokProduk'])->where('status_aktif', true)->orderBy('nama')->get();

        return view('pages.persediaan.penyesuaian.create', compact('produk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_penyesuaian' => 'required|date',
            'jenis_penyesuaian' => 'required|in:PENAMBAHAN,PENGURANGAN,RUSAK,KADALUARSA,KOREKSI',
            'status' => 'required|in:DRAFT,DISETUJUI,DITOLAK',
            'catatan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.jumlah_sistem' => 'required|numeric',
            'items.*.jumlah_aktual' => 'required|numeric',
            'items.*.harga_satuan' => 'required|numeric|min:0',
            'items.*.batch_id' => 'nullable|exists:batch_produk,id',
        ]);

        $items = $request->input('items', []);

        DB::transaction(function () use ($request, $items) {
            $nomor = 'ADJ' . date('Ymd') . str_pad(PenyesuaianStok::withTrashed()->count() + 1, 4, '0', STR_PAD_LEFT);

            $penyesuaian = PenyesuaianStok::create([
                'nomor_penyesuaian' => $nomor,
                'tanggal_penyesuaian' => $request->tanggal_penyesuaian,
                'jenis_penyesuaian' => $request->jenis_penyesuaian,
                'status' => $request->status,
                'total_item' => count($items),
                'catatan' => $request->catatan,
                'dibuat_oleh' => Auth::id(),
            ]);

            foreach ($items as $item) {
                $produkId = (int) $item['produk_id'];
                $jumlahSistem = (float) $item['jumlah_sistem'];
                $jumlahAktual = (float) $item['jumlah_aktual'];
                $selisih = $jumlahAktual - $jumlahSistem;
                $harga = (float) $item['harga_satuan'];
                $totalNilai = $selisih * $harga;
                $batchId = $item['batch_id'] ?? null;

                DetailPenyesuaianStok::create([
                    'penyesuaian_id' => $penyesuaian->id,
                    'produk_id' => $produkId,
                    'batch_id' => $batchId,
                    'jumlah_sistem' => $jumlahSistem,
                    'jumlah_aktual' => $jumlahAktual,
                    'selisih' => $selisih,
                    'harga_satuan' => $harga,
                    'total_nilai' => $totalNilai,
                    'catatan' => $item['catatan'] ?? null,
                ]);

                if ($request->status === 'DISETUJUI' && $selisih != 0) {
                    $stok = StokProduk::firstOrNew(['produk_id' => $produkId]);
                    $stok->jumlah = (float) ($stok->jumlah ?? 0);
                    $stok->jumlah_reservasi = (float) ($stok->jumlah_reservasi ?? 0);

                    $jumlahSebelum = $stok->jumlah;
                    $jumlahSesudah = $stok->jumlah + $selisih;

                    if ($jumlahSesudah < 0) {
                        throw ValidationException::withMessages([
                            'items' => 'Stok tidak mencukupi untuk penyesuaian.'
                        ]);
                    }

                    $stok->jumlah = $jumlahSesudah;
                    $stok->jumlah_tersedia = $stok->jumlah - $stok->jumlah_reservasi;
                    $stok->terakhir_diubah = now();
                    $stok->save();

                    if ($batchId) {
                        $batch = BatchProduk::find($batchId);
                        if ($batch) {
                            $batch->jumlah = max(0, (float) $batch->jumlah + $selisih);
                            $batch->save();
                        }
                    }

                    $jenisPergerakan = 'PENYESUAIAN';
                    if (in_array($request->jenis_penyesuaian, ['RUSAK', 'KADALUARSA'], true)) {
                        $jenisPergerakan = $request->jenis_penyesuaian;
                    }

                    PergerakanStok::create([
                        'produk_id' => $produkId,
                        'batch_id' => $batchId,
                        'jenis_pergerakan' => $jenisPergerakan,
                        'tipe_referensi' => 'PenyesuaianStok',
                        'id_referensi' => $penyesuaian->id,
                        'jumlah' => abs($selisih),
                        'jumlah_sebelum' => $jumlahSebelum,
                        'jumlah_sesudah' => $stok->jumlah,
                        'harga_satuan' => $harga,
                        'catatan' => 'Penyesuaian stok',
                        'dibuat_oleh' => Auth::id(),
                    ]);
                }
            }

            if ($request->status === 'DISETUJUI') {
                $penyesuaian->update([
                    'disetujui_oleh' => Auth::id(),
                    'waktu_persetujuan' => now(),
                ]);
            }
        });

        return redirect()->route('persediaan.penyesuaian.index')->with('success', 'Penyesuaian stok berhasil disimpan');
    }

    public function show(PenyesuaianStok $penyesuaian)
    {
        $penyesuaian->load(['detail.produk']);

        return view('pages.persediaan.penyesuaian.show', compact('penyesuaian'));
    }

    public function destroy(PenyesuaianStok $penyesuaian)
    {
        $penyesuaian->update(['deleted_at' => now()]);

        return redirect()->route('persediaan.penyesuaian.index')->with('success', 'Penyesuaian stok berhasil dihapus');
    }
}
