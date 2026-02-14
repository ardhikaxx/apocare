@extends('layouts.print')

@section('content')
<div class="print-header">
    <h4>Daftar Kategori</h4>
    <small>Dicetak: {{ now()->format('d/m/Y H:i') }}</small>
</div>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Kode</th>
            <th>Nama</th>
            <th>Parent</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($kategori as $item)
            <tr>
                <td>{{ $item->kode }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->parent ? $item->parent->nama : '-' }}</td>
                <td>{{ $item->status_aktif ? 'Aktif' : 'Nonaktif' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
