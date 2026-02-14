@extends('layouts.print')

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
