@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
@endpush

@section('content')
@include('partials.breadcrumb', ['breadcrumbs' => [
    ['label' => 'Dashboard']
]])

@php
$stats = [
    ['label' => 'Penjualan Bulan Ini', 'value' => 'Rp ' . number_format($penjualanBulanIni, 0, ',', '.'), 'icon' => 'fa-solid fa-wallet'],
    ['label' => 'Pembelian Bulan Ini', 'value' => 'Rp ' . number_format($pembelianBulanIni, 0, ',', '.'), 'icon' => 'fa-solid fa-basket-shopping'],
    ['label' => 'Profit Bulan Ini', 'value' => 'Rp ' . number_format($profitBulanIni, 0, ',', '.'), 'icon' => 'fa-solid fa-chart-line'],
    ['label' => 'Transaksi Bulan Ini', 'value' => number_format($transaksiBulanIni), 'icon' => 'fa-solid fa-receipt']
];
@endphp

@include('pages.shared.page-header', [
    'title' => 'Dashboard Apotek',
    'subtitle' => 'Ringkasan operasional, penjualan, dan persediaan terkini.'
])

@include('pages.shared.stats-row', ['stats' => $stats])

<div class="row g-4 mb-4">
    <div class="col-12 col-xl-7">
        <div class="card dashboard-hero">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h4 class="mb-1">Kinerja Penjualan</h4>
                        <div class="text-muted">7 hari terakhir</div>
                    </div>
                </div>
                <canvas id="salesChart" height="120"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-5">
        <div class="card h-100">
            <div class="card-header">Peringatan Prioritas</div>
            <div class="card-body">
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">Stok menipis</div>
                            <small class="text-muted">{{ $stokMenipis }} produk perlu reorder</small>
                        </div>
                        <span class="badge-soft warning"><i class="fa-solid fa-box-open me-1"></i>Reorder</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">Batch mendekati kadaluarsa</div>
                            <small class="text-muted">{{ $batchDekatKadaluarsa }} batch dalam 30 hari</small>
                        </div>
                        <span class="badge-soft danger"><i class="fa-solid fa-triangle-exclamation me-1"></i>Perlu cek</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">Hutang pemasok jatuh tempo</div>
                            <small class="text-muted">Rp {{ number_format($hutangJatuhTempo, 0, ',', '.') }}</small>
                        </div>
                        <span class="badge-soft info"><i class="fa-solid fa-clock me-1"></i>7 hari</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-lg-4">
        <div class="card chart-card h-100">
            <div class="card-header">Kategori Terlaris</div>
            <div class="card-body">
                @if($kategoriTerlaris->isEmpty())
                    <div class="text-muted">Belum ada data penjualan.</div>
                @else
                    <canvas id="categoryChart"></canvas>
                @endif
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="card chart-card h-100">
            <div class="card-header">Metode Pembayaran</div>
            <div class="card-body">
                @if($paymentSummary->isEmpty())
                    <div class="text-muted">Belum ada data pembayaran.</div>
                @else
                    <canvas id="paymentChart"></canvas>
                @endif
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="card h-100">
            <div class="card-header">Aktivitas Terbaru</div>
            <div class="card-body activity-scroll">
                @if($aktivitas->isEmpty())
                    <div class="text-muted">Belum ada aktivitas terbaru.</div>
                @else
                    <ul class="timeline">
                        @foreach($aktivitas as $item)
                            <li>
                                <strong>{{ $item['label'] }}</strong><br>
                                <small class="text-muted">{{ $item['detail'] }}</small>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">Transaksi Terakhir</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No Transaksi</th>
                        <th>Pelanggan</th>
                        <th>Metode</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penjualanTerakhir as $item)
                        <tr>
                            <td>{{ $item->nomor_penjualan }}</td>
                            <td>{{ $item->pelanggan->nama ?? 'Umum' }}</td>
                            <td>{{ $item->metode_pembayaran ?? '-' }}</td>
                            <td>Rp {{ number_format($item->total_akhir, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ $item->status_pembayaran === 'LUNAS' ? 'success' : ($item->status_pembayaran === 'SEBAGIAN' ? 'warning' : 'secondary') }}">
                                    {{ $item->status_pembayaran }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const salesCtx = document.getElementById('salesChart');
    if (salesCtx) {
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: @json($salesLabels),
                datasets: [{
                    label: 'Penjualan',
                    data: @json($salesData),
                    borderColor: '#0e6f64',
                    backgroundColor: 'rgba(14, 111, 100, 0.12)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: { plugins: { legend: { display: false } } }
        });
    }

    const categoryCtx = document.getElementById('categoryChart');
    if (categoryCtx) {
        new Chart(categoryCtx, {
            type: 'pie',
            data: {
                labels: @json($kategoriTerlaris->pluck('nama')),
                datasets: [{ data: @json($kategoriTerlaris->pluck('total')), backgroundColor: ['#0e6f64', '#f6b72b', '#2b8ac6', '#d94848'] }]
            },
            options: { plugins: { legend: { position: 'bottom' } } }
        });
    }

    const paymentCtx = document.getElementById('paymentChart');
    if (paymentCtx) {
        new Chart(paymentCtx, {
            type: 'doughnut',
            data: {
                labels: @json($paymentSummary->pluck('metode')),
                datasets: [{ data: @json($paymentSummary->pluck('total')), backgroundColor: ['#0e6f64', '#f6b72b', '#2b8ac6', '#d94848'] }]
            },
            options: { plugins: { legend: { position: 'bottom' } } }
        });
    }
</script>
@endpush





