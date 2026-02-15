@extends('layouts.print')

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
    <h4>Daftar Produk</h4>
    <small>Dicetak: {{ now()->format('d/m/Y H:i') }}</small>
</div>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Kode</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Satuan</th>
            <th>Harga Jual</th>
            <th>Stok Min</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($produk as $item)
            <tr>
                <td>{{ $item->kode }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->kategori ? $item->kategori->nama : '-' }}</td>
                <td>{{ $item->satuan ? $item->satuan->nama : '-' }}</td>
                <td>{{ number_format($item->harga_jual ?? 0, 0, ',', '.') }}</td>
                <td>{{ $item->stok_minimum ?? 0 }}</td>
                <td>{{ $item->status_aktif ? 'Aktif' : 'Nonaktif' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
