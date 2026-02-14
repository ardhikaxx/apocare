@extends('layouts.app')

@section('title', 'Laporan Pembelian')

@section('content')
@include('partials.breadcrumb', ['breadcrumbs' => [
    ['label' => 'Laporan'],
    ['label' => 'Pembelian']
]])

@include('pages.shared.page-header', [
    'title' => 'Laporan Pembelian',
    'subtitle' => 'Ringkasan transaksi pembelian.',
    'actions' => [
        ['label' => 'Export Excel', 'icon' => 'fa-solid fa-file-excel', 'class' => 'btn btn-soft', 'href' => route('laporan.pembelian.export.excel', request()->query())],
        ['label' => 'Export CSV', 'icon' => 'fa-solid fa-file-csv', 'class' => 'btn btn-soft', 'href' => route('laporan.pembelian.export.csv', request()->query())],
        ['label' => 'Export PDF', 'icon' => 'fa-solid fa-file-pdf', 'class' => 'btn btn-soft', 'href' => route('laporan.pembelian.export.pdf', request()->query())],
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
                <a href="{{ route('laporan.pembelian') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="text-muted">Total Pembelian</div>
                <h4 class="mb-0">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="text-muted">Total Transaksi</div>
                <h4 class="mb-0">{{ number_format($totalTransaksi) }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">Daftar Pembelian</div>
    <div class="card-body">
        @php
            $columns = ['Nomor', 'Tanggal', 'Pemasok', 'Status', 'Total'];
            $rows = $pembelian->map(function ($item) {
                $tanggal = $item->tanggal_pembelian
                    ? \Carbon\Carbon::parse($item->tanggal_pembelian)->format('d/m/Y')
                    : '-';
                return [
                    $item->nomor_pembelian,
                    $tanggal,
                    $item->pemasok->nama ?? '-',
                    $item->status,
                    'Rp ' . number_format($item->total_akhir, 0, ',', '.'),
                ];
            })->toArray();
        @endphp
        @include('pages.shared.table', compact('columns', 'rows'))
    </div>
</div>
@endsection
