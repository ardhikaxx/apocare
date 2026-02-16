<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Pelanggan;
use App\Models\Dokter;
use App\Models\Resep;
use App\Models\Pemasok;
use App\Models\Penjualan;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $results = [
            'produk' => $this->searchProduk($query),
            'pelanggan' => $this->searchPelanggan($query),
            'dokter' => $this->searchDokter($query),
            'karyawan' => $this->searchKaryawan($query),
            'resep' => $this->searchResep($query),
            'pemasok' => $this->searchPemasok($query),
            'penjualan' => $this->searchPenjualan($query),
            'pembelian' => $this->searchPembelian($query),
        ];

        $results = array_filter($results, function ($items) {
            return count($items) > 0;
        });

        return response()->json($results);
    }

    private function searchProduk($query)
    {
        return Produk::where('kode', 'like', "%{$query}%")
            ->orWhere('nama', 'like', "%{$query}%")
            ->orWhere('barcode', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $stok = $item->stokProduk()->sum('jumlah_tersedia') ?? 0;
                return [
                    'id' => $item->id,
                    'kode' => $item->kode,
                    'nama' => $item->nama,
                    'harga' => $item->harga_jual,
                    'stok' => $stok,
                    'url' => route('master.produk.index'),
                ];
            });
    }

    private function searchPelanggan($query)
    {
        return Pelanggan::where('kode', 'like', "%{$query}%")
            ->orWhere('nama', 'like', "%{$query}%")
            ->orWhere('telepon', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'kode' => $item->kode,
                    'nama' => $item->nama,
                    'telepon' => $item->telepon,
                    'url' => route('pelanggan.index'),
                ];
            });
    }

    private function searchDokter($query)
    {
        return Dokter::where('kode', 'like', "%{$query}%")
            ->orWhere('nama', 'like', "%{$query}%")
            ->orWhere('nomor_sip', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'kode' => $item->kode,
                    'nama' => $item->nama,
                    'nomor_sip' => $item->nomor_sip,
                    'url' => route('dokter.index'),
                ];
            });
    }

    private function searchKaryawan($query)
    {
        return DB::table('karyawan')
            ->join('pengguna', 'karyawan.pengguna_id', '=', 'pengguna.id')
            ->where('karyawan.nomor_karyawan', 'like', "%{$query}%")
            ->orWhere('pengguna.nama', 'like', "%{$query}%")
            ->select('karyawan.id', 'karyawan.nomor_karyawan as kode', 'pengguna.nama', 'karyawan.jabatan')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'kode' => $item->kode,
                    'nama' => $item->nama,
                    'jabatan' => $item->jabatan,
                    'url' => route('karyawan.index'),
                ];
            });
    }

    private function searchResep($query)
    {
        return Resep::where('nomor_resep', 'like', "%{$query}%")
            ->with(['pelanggan'])
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'kode' => $item->nomor_resep,
                    'nama_pasien' => $item->pelanggan?->nama ?? '-',
                    'status' => $item->status,
                    'url' => route('resep.show', $item->id),
                ];
            });
    }

    private function searchPemasok($query)
    {
        return Pemasok::where('kode', 'like', "%{$query}%")
            ->orWhere('nama', 'like', "%{$query}%")
            ->orWhere('telepon', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'kode' => $item->kode,
                    'nama' => $item->nama,
                    'url' => route('master.pemasok.index'),
                ];
            });
    }

    private function searchPenjualan($query)
    {
        return Penjualan::where('nomor_penjualan', 'like', "%{$query}%")
            ->with(['pelanggan'])
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'kode' => $item->nomor_penjualan,
                    'pelanggan' => $item->pelanggan?->nama ?? '-',
                    'total' => $item->total_akhir,
                    'tanggal' => $item->tanggal_penjualan,
                    'url' => route('transaksi.penjualan.show', $item->id),
                ];
            });
    }

    private function searchPembelian($query)
    {
        return Pembelian::where('nomor_pembelian', 'like', "%{$query}%")
            ->with(['pemasok'])
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'kode' => $item->nomor_pembelian,
                    'pemasok' => $item->pemasok?->nama ?? '-',
                    'total' => $item->total_akhir,
                    'tanggal' => $item->tanggal_pembelian,
                    'url' => route('transaksi.pembelian.show', $item->id),
                ];
            });
    }
}
