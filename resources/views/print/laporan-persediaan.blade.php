@extends('layouts.print')

@section('title', 'Laporan Persediaan')

@push('styles')
    <style>
        body {
            font-size: 12px;
            color: #111;
        }

        .print-header {
            border-bottom: 1px solid #ddd;
            margin-bottom: 16px;
            padding-bottom: 8px;
        }

        .kop {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 8px;
        }

        .kop-logo img {
            height: 65px;
            width: auto;
        }

        .kop-title {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .kop-subtitle {
            font-size: 12px;
            color: #444;
        }

        .kop-line {
            border-bottom: 1px solid #222;
            margin-bottom: 16px;
        }
    </style>
@endpush

@section('content')
<div class="print-header">
    <h4>Laporan Persediaan</h4>
    <div class="text-muted">Tanggal cetak: {{ date('d/m/Y') }}</div>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Produk</th>
            <th>Kategori</th>
            <th>Stok</th>
            <th>Harga Beli</th>
            <th>Nilai</th>
        </tr>
    </thead>
    <tbody>
        @foreach($stok as $item)
            @php
                $harga = (float) ($item->produk->harga_beli ?? 0);
                $nilai = (float) $item->jumlah * $harga;
            @endphp
            <tr>
                <td>{{ $item->produk->nama ?? '-' }}</td>
                <td>{{ $item->produk->kategori->nama ?? '-' }}</td>
                <td>{{ number_format($item->jumlah, 2, ',', '.') }}</td>
                <td>Rp {{ number_format($harga, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($nilai, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
