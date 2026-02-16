@extends('layouts.app')

@section('title', 'Laporan Stok')

@section('content')
@include('partials.breadcrumb', ['breadcrumbs' => [
    ['label' => 'Laporan'],
    ['label' => 'Stok']
]])

@include('pages.shared.page-header', [
    'title' => 'Laporan Stok',
    'subtitle' => 'Ringkasan stok produk dan nilai persediaan.',
])

<div class="card">
    <div class="card-header">Daftar Stok</div>
    <div class="card-body">
        @php
            $columns = ['Produk', 'Kategori', 'Stok', 'Harga Beli', 'Nilai Stok'];
            $rows = $stok->map(function ($item) {
                $harga = (float) ($item->harga_beli_terakhir ?? 0);
                $nilai = (float) $item->jumlah * $harga;
                return [
                    $item->produk->nama ?? '-',
                    $item->produk->kategori->nama ?? '-',
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
