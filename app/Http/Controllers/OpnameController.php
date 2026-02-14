<?php

namespace App\Http\Controllers;

use App\Models\DetailStokOpname;
use App\Models\Kategori;
use App\Models\PergerakanStok;
use App\Models\Produk;
use App\Models\StokOpname;
use App\Models\StokProduk;
use App\Models\BatchProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OpnameController extends Controller
{
    public function index(Request $request)
    {
        $query = StokOpname::with('detail');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal_opname', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        $opname = $query->latest('tanggal_opname')->get();

        return view('pages.persediaan.opname.index', compact('opname'));
    }

    public function create()
    {
        $kategori = Kategori::where('status_aktif', true)->get();
        $produk = Produk::with('stokProduk')->where('status_aktif', true)->orderBy('nama')->get();

        return view('pages.persediaan.opname.create', compact('kategori', 'produk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_opname' => 'required|date',
            'status' => 'required|in:DRAFT,PROSES,SELESAI,DISETUJUI',
            'kategori_id' => 'nullable|exists:kategori,id',
            'catatan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.jumlah_sistem' => 'required|numeric',
            'items.*.jumlah_hitung' => 'required|numeric',
            'items.*.harga_satuan' => 'required|numeric|min:0',
            'items.*.batch_id' => 'nullable|exists:batch_produk,id',
        ]);

        $items = $request->input('items', []);

        DB::transaction(function () use ($request, $items) {
            $nomor = 'OP' . date('Ymd') . str_pad(StokOpname::withTrashed()->count() + 1, 4, '0', STR_PAD_LEFT);

            $totalItem = count($items);
            $totalCocok = 0;
            $totalSelisih = 0;
            $totalNilaiSelisih = 0;

            foreach ($items as $item) {
                $selisih = (float) $item['jumlah_hitung'] - (float) $item['jumlah_sistem'];
                if ($selisih == 0) {
                    $totalCocok++;
                } else {
                    $totalSelisih++;
                }
                $totalNilaiSelisih += $selisih * (float) $item['harga_satuan'];
            }

            $opname = StokOpname::create([
                'nomor_opname' => $nomor,
                'tanggal_opname' => $request->tanggal_opname,
                'status' => $request->status,
                'kategori_id' => $request->kategori_id,
                'total_item_dihitung' => $totalItem,
                'total_item_cocok' => $totalCocok,
                'total_item_selisih' => $totalSelisih,
                'total_nilai_selisih' => $totalNilaiSelisih,
                'catatan' => $request->catatan,
                'dibuat_oleh' => Auth::id(),
            ]);

            foreach ($items as $item) {
                $produkId = (int) $item['produk_id'];
                $jumlahSistem = (float) $item['jumlah_sistem'];
                $jumlahHitung = (float) $item['jumlah_hitung'];
                $selisih = $jumlahHitung - $jumlahSistem;
                $harga = (float) $item['harga_satuan'];
                $totalNilai = $selisih * $harga;
                $batchId = $item['batch_id'] ?? null;

                $statusItem = 'COCOK';
                if ($selisih > 0) {
                    $statusItem = 'LEBIH';
                } elseif ($selisih < 0) {
                    $statusItem = 'KURANG';
                }

                DetailStokOpname::create([
                    'opname_id' => $opname->id,
                    'produk_id' => $produkId,
                    'batch_id' => $batchId,
                    'jumlah_sistem' => $jumlahSistem,
                    'jumlah_hitung' => $jumlahHitung,
                    'selisih' => $selisih,
                    'harga_satuan' => $harga,
                    'total_nilai_selisih' => $totalNilai,
                    'status' => $statusItem,
                    'dihitung_oleh' => Auth::id(),
                    'waktu_hitung' => now(),
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
                            'items' => 'Stok tidak mencukupi untuk opname.'
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

                    PergerakanStok::create([
                        'produk_id' => $produkId,
                        'batch_id' => $batchId,
                        'jenis_pergerakan' => 'PENYESUAIAN',
                        'tipe_referensi' => 'StokOpname',
                        'id_referensi' => $opname->id,
                        'jumlah' => abs($selisih),
                        'jumlah_sebelum' => $jumlahSebelum,
                        'jumlah_sesudah' => $stok->jumlah,
                        'harga_satuan' => $harga,
                        'catatan' => 'Stok opname',
                        'dibuat_oleh' => Auth::id(),
                    ]);
                }
            }

            if ($request->status === 'DISETUJUI') {
                $opname->update([
                    'disetujui_oleh' => Auth::id(),
                    'waktu_persetujuan' => now(),
                ]);
            }
        });

        return redirect()->route('persediaan.opname.index')->with('success', 'Stok opname berhasil disimpan');
    }

    public function show(StokOpname $opname)
    {
        $opname->load(['detail.produk']);

        return view('pages.persediaan.opname.show', compact('opname'));
    }

    public function destroy(StokOpname $opname)
    {
        $opname->update(['deleted_at' => now()]);

        return redirect()->route('persediaan.opname.index')->with('success', 'Stok opname berhasil dihapus');
    }
}
