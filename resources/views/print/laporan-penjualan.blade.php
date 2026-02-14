@extends('layouts.print')

@section('title', 'Laporan Penjualan')

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
