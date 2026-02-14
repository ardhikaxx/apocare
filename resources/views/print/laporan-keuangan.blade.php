@extends('layouts.print')

@section('title', 'Laporan Keuangan')

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
