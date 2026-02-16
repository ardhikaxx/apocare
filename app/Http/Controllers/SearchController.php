<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Pelanggan;
use App\Models\Dokter;
use App\Models\Karyawan;
use App\Models\Resep;
use App\Models\Pemasok;
use App\Models\Penjualan;
use App\Models\Pembelian;
use Illuminate\Http\Request;

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
        return Produk::where('kode_produk', 'like', "%{$query}%")
            ->orWhere('nama_produk', 'like', "%{$query}%")
            ->orWhere('barcode', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'kode' => $item->kode_produk,
                    'nama' => $item->nama_produk,
                    'harga' => $item->harga_jual,
                    'stok' => $item->stokProduk->sum('jumlah_sisa') ?? 0,
                    'url' => route('master.produk.show', $item->id),
                ];
            });
    }

    private function searchPelanggan($query)
    {
        return Pelanggan::where('kode_pelanggan', 'like', "%{$query}%")
            ->orWhere('nama_pelanggan', 'like', "%{$query}%")
            ->orWhere('no_telp', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'kode' => $item->kode_pelanggan,
                    'nama' => $item->nama_pelanggan,
                    'no_telp' => $item->no_telp,
                    'url' => route('pelanggan.show', $item->id),
                ];
            });
    }

    private function searchDokter($query)
    {
        return Dokter::where('kode_dokter', 'like', "%{$query}%")
            ->orWhere('nama_dokter', 'like', "%{$query}%")
            ->orWhere('no_sip', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'kode' => $item->kode_dokter,
                    'nama' => $item->nama_dokter,
                    'no_sip' => $item->no_sip,
                    'url' => route('dokter.show', $item->id),
                ];
            });
    }

    private function searchKaryawan($query)
    {
        return Karyawan::where('kode_karyawan', 'like', "%{$query}%")
            ->orWhere('nama_karyawan', 'like', "%{$query}%")
            ->orWhere('no_telp', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'kode' => $item->kode_karyawan,
                    'nama' => $item->nama_karyawan,
                    'jabatan' => $item->jabatan,
                    'url' => route('karyawan.show', $item->id),
                ];
            });
    }

    private function searchResep($query)
    {
        return Resep::where('kode_resep', 'like', "%{$query}%")
            ->orWhere('nama_pasien', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'kode' => $item->kode_resep,
                    'nama_pasien' => $item->nama_pasien,
                    'status' => $item->status,
                    'url' => route('resep.show', $item->id),
                ];
            });
    }

    private function searchPemasok($query)
    {
        return Pemasok::where('kode_pemasok', 'like', "%{$query}%")
            ->orWhere('nama_pemasok', 'like', "%{$query}%")
            ->orWhere('no_telp', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'kode' => $item->kode_pemasok,
                    'nama' => $item->nama_pemasok,
                    'url' => route('master.pemasok.show', $item->id),
                ];
            });
    }

    private function searchPenjualan($query)
    {
        return Penjualan::where('kode_penjualan', 'like', "%{$query}%")
            ->orWhere('no_invoice', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'kode' => $item->kode_penjualan,
                    'invoice' => $item->no_invoice,
                    'total' => $item->total_bayar,
                    'tanggal' => $item->tgl_penjualan,
                    'url' => route('transaksi.penjualan.show', $item->id),
                ];
            });
    }

    private function searchPembelian($query)
    {
        return Pembelian::where('kode_pembelian', 'like', "%{$query}%")
            ->orWhere('no_invoice', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'kode' => $item->kode_pembelian,
                    'invoice' => $item->no_invoice,
                    'total' => $item->total_bayar,
                    'tanggal' => $item->tgl_pembelian,
                    'url' => route('transaksi.pembelian.show', $item->id),
                ];
            });
    }
}
