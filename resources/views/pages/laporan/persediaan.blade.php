@extends('layouts.app')

@section('title', 'Laporan Persediaan')

@section('content')
@include('partials.breadcrumb', ['breadcrumbs' => [
    ['label' => 'Laporan'],
    ['label' => 'Persediaan']
]])

@include('pages.shared.page-header', [
    'title' => 'Laporan Persediaan',
    'subtitle' => 'Ringkasan stok dan nilai persediaan.',
    'actions' => [
        ['label' => 'Export Excel', 'icon' => 'fa-solid fa-file-excel', 'class' => 'btn btn-soft', 'href' => route('laporan.persediaan.export.excel', request()->query())],
        ['label' => 'Export CSV', 'icon' => 'fa-solid fa-file-csv', 'class' => 'btn btn-soft', 'href' => route('laporan.persediaan.export.csv', request()->query())],
        ['label' => 'Export PDF', 'icon' => 'fa-solid fa-file-pdf', 'class' => 'btn btn-soft', 'href' => route('laporan.persediaan.export.pdf', request()->query())],
    ]
])

<div class="card mb-3">
    <div class="card-header">Filter Laporan</div>
    <div class="card-body">
        <form class="row g-3" method="GET">
            <div class="col-md-4">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-select">
                    <option value="">Semua</option>
                    @foreach($kategori as $item)
                        <option value="{{ $item->id }}" @selected(request('kategori') == $item->id)>{{ $item->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button class="btn btn-primary" type="submit"><i class="fa-solid fa-filter me-2"></i>Filter</button>
                <a href="{{ route('laporan.persediaan') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="text-muted">Total Nilai Persediaan</div>
                <h4 class="mb-0">Rp {{ number_format($totalNilai, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">Daftar Persediaan</div>
    <div class="card-body">
        @php
            $columns = ['Produk', 'Kategori', 'Stok', 'Harga Beli', 'Nilai'];
            $rows = $stok->map(function ($item) {
                $produk = $item->produk;
                $harga = (float) ($produk->harga_beli ?? 0);
                $nilai = (float) $item->jumlah * $harga;
                return [
                    $produk->nama ?? '-',
                    $produk->kategori->nama ?? '-',
                    formatAngka($item->jumlah),
                    'Rp ' . formatAngka($harga),
                    'Rp ' . formatAngka($nilai),
                ];
            })->toArray();
        @endphp
        @include('pages.shared.table', compact('columns', 'rows'))
    </div>
</div>
@endsection
