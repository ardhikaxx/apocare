@extends('layouts.print')

@section('title', 'Laporan Penjualan')

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
    <h4>Laporan Penjualan</h4>
    <div class="text-muted">Tanggal cetak: {{ date('d/m/Y') }}</div>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nomor</th>
            <th>Tanggal</th>
            <th>Pelanggan</th>
            <th>Metode</th>
            <th>Total</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($penjualan as $item)
            <tr>
                <td>{{ $item->nomor_penjualan }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_penjualan)->format('d/m/Y') }}</td>
                <td>{{ $item->pelanggan->nama ?? 'Umum' }}</td>
                <td>{{ $item->metode_pembayaran ?? '-' }}</td>
                <td>Rp {{ number_format($item->total_akhir, 0, ',', '.') }}</td>
                <td>{{ $item->status_pembayaran }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
