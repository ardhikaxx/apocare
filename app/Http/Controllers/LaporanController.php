<?php

namespace App\Http\Controllers;

use App\Exports\LaporanPembelianExport;
use App\Exports\LaporanPenjualanExport;
use App\Exports\LaporanPersediaanExport;
use App\Exports\LaporanKeuanganExport;
use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\StokProduk;
use App\Models\Kategori;
use App\Models\DetailPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    private function penjualanQuery(Request $request)
    {
        $query = Penjualan::with('pelanggan');

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal_penjualan', [$request->tanggal_mulai, $request->tanggal_selesai]);
        } else {
            $query->whereMonth('tanggal_penjualan', now()->month)
                ->whereYear('tanggal_penjualan', now()->year);
        }

        return $query;
    }

    private function pembelianQuery(Request $request)
    {
        $query = Pembelian::with('pemasok');

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal_pembelian', [$request->tanggal_mulai, $request->tanggal_selesai]);
        } else {
            $query->whereMonth('tanggal_pembelian', now()->month)
                ->whereYear('tanggal_pembelian', now()->year);
        }

        return $query;
    }

    private function keuanganSummary(Request $request): array
    {
        $penjualan = $this->penjualanQuery($request)->get();
        $pembelian = $this->pembelianQuery($request)->get();

        $totalPenjualan = $penjualan->sum('total_akhir');
        $totalPembelian = $pembelian->sum('total_akhir');

        $profit = DetailPenjualan::query()
            ->join('penjualan', 'detail_penjualan.penjualan_id', '=', 'penjualan.id')
            ->join('produk', 'detail_penjualan.produk_id', '=', 'produk.id')
            ->when($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai'), function ($query) use ($request) {
                $query->whereBetween('penjualan.tanggal_penjualan', [$request->tanggal_mulai, $request->tanggal_selesai]);
            }, function ($query) {
                $query->whereMonth('penjualan.tanggal_penjualan', now()->month)
                    ->whereYear('penjualan.tanggal_penjualan', now()->year);
            })
            ->sum(DB::raw('(detail_penjualan.total) - (detail_penjualan.jumlah * COALESCE(produk.harga_beli, 0))'));

        return compact('penjualan', 'pembelian', 'totalPenjualan', 'totalPembelian', 'profit');
    }

    public function penjualan(Request $request)
    {
        $penjualan = $this->penjualanQuery($request)->orderBy('tanggal_penjualan', 'desc')->get();
        $totalPenjualan = $penjualan->sum('total_akhir');
        $totalTransaksi = $penjualan->count();

        return view('pages.laporan.penjualan', compact('penjualan', 'totalPenjualan', 'totalTransaksi'));
    }

    public function pembelian(Request $request)
    {
        $pembelian = $this->pembelianQuery($request)->orderBy('tanggal_pembelian', 'desc')->get();
        $totalPembelian = $pembelian->sum('total_akhir');
        $totalTransaksi = $pembelian->count();

        return view('pages.laporan.pembelian', compact('pembelian', 'totalPembelian', 'totalTransaksi'));
    }

    public function persediaan(Request $request)
    {
        $query = StokProduk::with(['produk.kategori', 'produk.satuan']);

        if ($request->filled('kategori')) {
            $query->whereHas('produk', function ($q) use ($request) {
                $q->where('kategori_id', $request->kategori);
            });
        }

        $stok = $query->get();
        $kategori = Kategori::where('status_aktif', true)->get();
        $totalNilai = $stok->sum(function ($item) {
            return (float) $item->jumlah * (float) ($item->produk->harga_beli ?? 0);
        });

        return view('pages.laporan.persediaan', compact('stok', 'kategori', 'totalNilai'));
    }

    public function keuangan(Request $request)
    {
        $summary = $this->keuanganSummary($request);

        return view('pages.laporan.keuangan', [
            'totalPenjualan' => $summary['totalPenjualan'],
            'totalPembelian' => $summary['totalPembelian'],
            'profit' => $summary['profit'],
        ]);
    }

    public function exportPenjualanExcel(Request $request)
    {
        $penjualan = $this->penjualanQuery($request)->orderBy('tanggal_penjualan', 'desc')->get();
        return Excel::download(new LaporanPenjualanExport($penjualan), 'laporan-penjualan.xlsx');
    }

    public function exportPenjualanCsv(Request $request)
    {
        $penjualan = $this->penjualanQuery($request)->orderBy('tanggal_penjualan', 'desc')->get();
        return Excel::download(new LaporanPenjualanExport($penjualan), 'laporan-penjualan.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPenjualanPdf(Request $request)
    {
        $penjualan = $this->penjualanQuery($request)->orderBy('tanggal_penjualan', 'desc')->get();
        $pdf = Pdf::loadView('print.laporan-penjualan', compact('penjualan'));
        return $pdf->download('laporan-penjualan.pdf');
    }

    public function exportPembelianExcel(Request $request)
    {
        $pembelian = $this->pembelianQuery($request)->orderBy('tanggal_pembelian', 'desc')->get();
        return Excel::download(new LaporanPembelianExport($pembelian), 'laporan-pembelian.xlsx');
    }

    public function exportPembelianCsv(Request $request)
    {
        $pembelian = $this->pembelianQuery($request)->orderBy('tanggal_pembelian', 'desc')->get();
        return Excel::download(new LaporanPembelianExport($pembelian), 'laporan-pembelian.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPembelianPdf(Request $request)
    {
        $pembelian = $this->pembelianQuery($request)->orderBy('tanggal_pembelian', 'desc')->get();
        $pdf = Pdf::loadView('print.laporan-pembelian', compact('pembelian'));
        return $pdf->download('laporan-pembelian.pdf');
    }

    public function exportPersediaanExcel(Request $request)
    {
        $query = StokProduk::with(['produk.kategori', 'produk.satuan']);
        if ($request->filled('kategori')) {
            $query->whereHas('produk', function ($q) use ($request) {
                $q->where('kategori_id', $request->kategori);
            });
        }
        $stok = $query->get();

        return Excel::download(new LaporanPersediaanExport($stok), 'laporan-persediaan.xlsx');
    }

    public function exportPersediaanCsv(Request $request)
    {
        $query = StokProduk::with(['produk.kategori', 'produk.satuan']);
        if ($request->filled('kategori')) {
            $query->whereHas('produk', function ($q) use ($request) {
                $q->where('kategori_id', $request->kategori);
            });
        }
        $stok = $query->get();

        return Excel::download(new LaporanPersediaanExport($stok), 'laporan-persediaan.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPersediaanPdf(Request $request)
    {
        $query = StokProduk::with(['produk.kategori', 'produk.satuan']);
        if ($request->filled('kategori')) {
            $query->whereHas('produk', function ($q) use ($request) {
                $q->where('kategori_id', $request->kategori);
            });
        }
        $stok = $query->get();

        $pdf = Pdf::loadView('print.laporan-persediaan', compact('stok'));
        return $pdf->download('laporan-persediaan.pdf');
    }

    public function exportKeuanganExcel(Request $request)
    {
        $summary = $this->keuanganSummary($request);

        return Excel::download(
            new LaporanKeuanganExport($summary['totalPenjualan'], $summary['totalPembelian'], $summary['profit']),
            'laporan-keuangan.xlsx'
        );
    }

    public function exportKeuanganCsv(Request $request)
    {
        $summary = $this->keuanganSummary($request);

        return Excel::download(
            new LaporanKeuanganExport($summary['totalPenjualan'], $summary['totalPembelian'], $summary['profit']),
            'laporan-keuangan.csv',
            \Maatwebsite\Excel\Excel::CSV
        );
    }

    public function exportKeuanganPdf(Request $request)
    {
        $summary = $this->keuanganSummary($request);

        $totalPenjualan = $summary['totalPenjualan'];
        $totalPembelian = $summary['totalPembelian'];
        $profit = $summary['profit'];

        $pdf = Pdf::loadView('print.laporan-keuangan', compact('totalPenjualan', 'totalPembelian', 'profit'));
        return $pdf->download('laporan-keuangan.pdf');
    }

    public function pelanggan()
    {
        $topPelanggan = Penjualan::with('pelanggan')
            ->selectRaw('pelanggan_id, SUM(total_akhir) as total_belanja, COUNT(*) as frekuensi')
            ->whereNotNull('pelanggan_id')
            ->groupBy('pelanggan_id')
            ->orderByDesc('total_belanja')
            ->limit(10)
            ->get();

        return view('pages.laporan.pelanggan', compact('topPelanggan'));
    }
}
