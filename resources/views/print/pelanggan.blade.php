@extends('layouts.print')

@section('content')
<div class="print-header">
    <h4>Daftar Pelanggan</h4>
    <small>Dicetak: {{ now()->format('d/m/Y H:i') }}</small>
</div>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Kode</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Telepon</th>
            <th>Email</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pelanggan as $item)
            <tr>
                <td>{{ $item->kode }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->jenis_pelanggan }}</td>
                <td>{{ $item->telepon }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->status_aktif ? 'Aktif' : 'Nonaktif' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
