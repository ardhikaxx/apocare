@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')
@include('partials.breadcrumb', ['breadcrumbs' => [
    ['label' => 'Laporan'],
    ['label' => 'Keuangan']
]])

@include('pages.shared.page-header', [
    'title' => 'Laporan Keuangan',
    'subtitle' => 'Ringkasan total penjualan, pembelian, dan profit.',
    'actions' => [
        ['label' => 'Export Excel', 'icon' => 'fa-solid fa-file-excel', 'class' => 'btn btn-soft', 'href' => route('laporan.keuangan.export.excel', request()->query())],
        ['label' => 'Export CSV', 'icon' => 'fa-solid fa-file-csv', 'class' => 'btn btn-soft', 'href' => route('laporan.keuangan.export.csv', request()->query())],
        ['label' => 'Export PDF', 'icon' => 'fa-solid fa-file-pdf', 'class' => 'btn btn-soft', 'href' => route('laporan.keuangan.export.pdf', request()->query())],
    ]
])

<div class="card mb-3">
    <div class="card-header">Filter Laporan</div>
    <div class="card-body">
        <form class="row g-3" method="GET">
            <div class="col-md-4">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" class="form-control" value="{{ request('tanggal_selesai') }}">
            </div>
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button class="btn btn-primary" type="submit"><i class="fa-solid fa-filter me-2"></i>Filter</button>
                <a href="{{ route('laporan.keuangan') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="text-muted">Total Penjualan</div>
                <h4 class="mb-0">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="text-muted">Total Pembelian</div>
                <h4 class="mb-0">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="text-muted">Profit (Estimasi)</div>
                <h4 class="mb-0">Rp {{ number_format($profit, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>
</div>
@endsection
