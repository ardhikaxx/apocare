<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\Pembelian;
use App\Models\Pelanggan;
use App\Models\DetailPenjualan;
use App\Models\BatchProduk;
use App\Models\PergerakanStok;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $startDate = now()->subDays(6)->startOfDay();

        $totalProduk = Produk::count();
        $totalPelanggan = Pelanggan::count();
        $totalPemasok = \App\Models\Pemasok::count();

        $penjualanBulanIni = Penjualan::whereMonth('tanggal_penjualan', now()->month)
            ->whereYear('tanggal_penjualan', now()->year)
            ->sum('total_akhir');

        $pembelianBulanIni = Pembelian::whereMonth('tanggal_pembelian', now()->month)
            ->whereYear('tanggal_pembelian', now()->year)
            ->sum('total_akhir');

        $transaksiBulanIni = Penjualan::whereMonth('tanggal_penjualan', now()->month)
            ->whereYear('tanggal_penjualan', now()->year)
            ->count();

        $profitBulanIni = DetailPenjualan::query()
            ->join('penjualan', 'detail_penjualan.penjualan_id', '=', 'penjualan.id')
            ->join('produk', 'detail_penjualan.produk_id', '=', 'produk.id')
            ->whereMonth('penjualan.tanggal_penjualan', now()->month)
            ->whereYear('penjualan.tanggal_penjualan', now()->year)
            ->sum(DB::raw('(detail_penjualan.total) - (detail_penjualan.jumlah * COALESCE(produk.harga_beli, 0))'));

        $stokMenipis = Produk::whereRaw('stok_minimum > 0')
            ->whereHas('stokProduk', function ($q) {
                $q->whereRaw('jumlah_tersedia <= (SELECT stok_minimum FROM produk WHERE id = produk_id)');
            })->count();

        $batchDekatKadaluarsa = BatchProduk::whereNotNull('tanggal_kadaluarsa')
            ->whereDate('tanggal_kadaluarsa', '<=', now()->addDays(30))
            ->where('jumlah', '>', 0)
            ->count();

        $hutangJatuhTempo = Pembelian::whereIn('status_pembayaran', ['BELUM_BAYAR', 'SEBAGIAN'])
            ->whereNotNull('tanggal_jatuh_tempo')
            ->whereDate('tanggal_jatuh_tempo', '<=', now()->addDays(7))
            ->sum('sisa_bayar');

        $salesRaw = Penjualan::selectRaw('DATE(tanggal_penjualan) as tanggal, SUM(total_akhir) as total')
            ->whereDate('tanggal_penjualan', '>=', $startDate)
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->keyBy('tanggal');

        $salesLabels = [];
        $salesData = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i)->toDateString();
            $salesLabels[] = Carbon::parse($date)->translatedFormat('D');
            $salesData[] = (float) ($salesRaw[$date]->total ?? 0);
        }

        $kategoriTerlaris = DetailPenjualan::query()
            ->join('produk', 'detail_penjualan.produk_id', '=', 'produk.id')
            ->leftJoin('kategori', 'produk.kategori_id', '=', 'kategori.id')
            ->selectRaw('COALESCE(kategori.nama, "Tanpa Kategori") as nama, SUM(detail_penjualan.total) as total')
            ->groupBy('nama')
            ->orderByDesc('total')
            ->limit(4)
            ->get();

        $paymentSummary = Penjualan::selectRaw('COALESCE(metode_pembayaran, "-" ) as metode, COUNT(*) as total')
            ->groupBy('metode')
            ->orderByDesc('total')
            ->get();

        $penjualanTerakhir = Penjualan::with('pelanggan')
            ->latest('tanggal_penjualan')
            ->limit(5)
            ->get();

        $aktivitas = collect()
            ->merge(Penjualan::latest('tanggal_penjualan')->limit(3)->get()->map(function ($item) {
                return [
                    'label' => 'Penjualan ' . $item->nomor_penjualan,
                    'detail' => 'Total Rp ' . number_format($item->total_akhir, 0, ',', '.'),
                    'waktu' => $item->tanggal_penjualan,
                ];
            }))
            ->merge(Pembelian::latest('tanggal_pembelian')->limit(3)->get()->map(function ($item) {
                return [
                    'label' => 'Pembelian ' . $item->nomor_pembelian,
                    'detail' => 'Status ' . $item->status,
                    'waktu' => $item->tanggal_pembelian,
                ];
            }))
            ->merge(PergerakanStok::latest()->limit(3)->get()->map(function ($item) {
                return [
                    'label' => 'Pergerakan Stok',
                    'detail' => $item->jenis_pergerakan . ' - ' . number_format($item->jumlah, 2, ',', '.'),
                    'waktu' => $item->created_at,
                ];
            }))
            ->sortByDesc('waktu')
            ->values()
            ->take(6);

        return view('pages.dashboard.index', compact(
            'totalProduk', 'totalPelanggan', 'totalPemasok',
            'penjualanBulanIni', 'pembelianBulanIni', 'profitBulanIni',
            'transaksiBulanIni', 'stokMenipis', 'batchDekatKadaluarsa', 'hutangJatuhTempo',
            'salesLabels', 'salesData', 'kategoriTerlaris', 'paymentSummary',
            'penjualanTerakhir', 'aktivitas'
        ));
    }
}
