@extends('layouts.print')

@section('title', 'Laporan Keuangan')

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
    <h4>Laporan Keuangan</h4>
    <div class="text-muted">Tanggal cetak: {{ date('d/m/Y') }}</div>
</div>

<table class="table table-bordered">
    <tbody>
        <tr>
            <th>Total Penjualan</th>
            <td>Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Total Pembelian</th>
            <td>Rp {{ number_format($totalPembelian, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Profit (Estimasi)</th>
            <td>Rp {{ number_format($profit, 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>
@endsection
