@extends('layouts.print')

@section('title', 'Laporan Pembelian')

@section('content')
<div class="print-header">
    <h4>Laporan Pembelian</h4>
    <div class="text-muted">Tanggal cetak: {{ date('d/m/Y') }}</div>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nomor</th>
            <th>Tanggal</th>
            <th>Pemasok</th>
            <th>Status</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pembelian as $item)
            <tr>
                <td>{{ $item->nomor_pembelian }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_pembelian)->format('d/m/Y') }}</td>
                <td>{{ $item->pemasok->nama ?? '-' }}</td>
                <td>{{ $item->status }}</td>
                <td>Rp {{ number_format($item->total_akhir, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
