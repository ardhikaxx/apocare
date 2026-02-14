@extends('layouts.app')

@section('title', 'Laporan Pelanggan')

@section('content')
@include('partials.breadcrumb', ['breadcrumbs' => [
    ['label' => 'Laporan'],
    ['label' => 'Pelanggan']
]])

@include('pages.shared.page-header', [
    'title' => 'Laporan Pelanggan',
    'subtitle' => 'Top pelanggan berdasarkan transaksi.',
])

<div class="card">
    <div class="card-header">Top Pelanggan</div>
    <div class="card-body">
        @php
            $columns = ['Pelanggan', 'Frekuensi', 'Total Belanja'];
            $rows = $topPelanggan->map(function ($item) {
                return [
                    $item->pelanggan->nama ?? '-',
                    number_format($item->frekuensi),
                    'Rp ' . number_format($item->total_belanja, 0, ',', '.'),
                ];
            })->toArray();
        @endphp
        @include('pages.shared.table', compact('columns', 'rows'))
    </div>
</div>
@endsection
