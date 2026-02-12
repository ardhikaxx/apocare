@extends('layouts.app')

@push('styles')
<style>
.stat-card { border-left: 4px solid #198754; }
.stat-icon { font-size: 2.5rem; opacity: 0.3; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1>Dashboard</h1>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center">
                <i class="fas fa-pills stat-icon text-primary me-3"></i>
                <div>
                    <h6 class="text-muted mb-1">Total Produk</h6>
                    <h3 class="mb-0">{{ $totalProduk }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center">
                <i class="fas fa-users stat-icon text-success me-3"></i>
                <div>
                    <h6 class="text-muted mb-1">Total Pelanggan</h6>
                    <h3 class="mb-0">{{ $totalPelanggan }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center">
                <i class="fas fa-shopping-cart stat-icon text-info me-3"></i>
                <div>
                    <h6 class="text-muted mb-1">Transaksi Hari Ini</h6>
                    <h3 class="mb-0">{{ $transaksiHariIni }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center">
                <i class="fas fa-exclamation-triangle stat-icon text-warning me-3"></i>
                <div>
                    <h6 class="text-muted mb-1">Stok Menipis</h6>
                    <h3 class="mb-0">{{ $stokMenipis }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Penjualan Hari Ini</span>
                <span class="h4 mb-0 text-success">Rp {{ number_format($penjualanHariIni, 0, ',', '.') }}</span>
            </div>
            <div class="card-body">
                <h6 class="text-muted">Penjualan Bulan Ini</h6>
                <h3 class="text-primary mb-3">Rp {{ number_format($penjualanBulanIni, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Penjualan Terakhir</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    @foreach($penjualanTerakhir as $item)
                    <tr>
                        <td>{{ $item->nomor_penjualan }}</td>
                        <td>{{ $item->pelanggan ? $item->pelanggan->nama : 'Umum' }}</td>
                        <td class="text-end">Rp {{ number_format($item->total_akhir, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
