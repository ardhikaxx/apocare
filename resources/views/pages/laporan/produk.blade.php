@extends('layouts.app')

@section('title', 'Laporan Produk')

@section('content')
@include('partials.breadcrumb', ['breadcrumbs' => [
    ['label' => 'Laporan'],
    ['label' => 'Produk']
]])

@include('pages.shared.page-header', [
    'title' => 'Laporan Produk',
    'subtitle' => 'Ringkasan data produk.',
])

<div class="card">
    <div class="card-header">Daftar Produk</div>
    <div class="card-body">
        @php
            $columns = ['Kode', 'Nama', 'Kategori', 'Harga Beli', 'Harga Jual'];
            $rows = $produk->map(function ($item) {
                return [
                    $item->kode,
                    $item->nama,
                    $item->kategori->nama ?? '-',
                    'Rp ' . number_format($item->harga_beli ?? 0, 0, ',', '.'),
                    'Rp ' . number_format($item->harga_jual ?? 0, 0, ',', '.'),
                ];
            })->toArray();
        @endphp
        @include('pages.shared.table', compact('columns', 'rows'))
    </div>
</div>
@endsection
