@extends('layouts.print')

@section('content')
<div class="print-header">
    <h4>Daftar Pemasok</h4>
    <small>Dicetak: {{ now()->format('d/m/Y H:i') }}</small>
</div>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Kode</th>
            <th>Nama</th>
            <th>Kontak</th>
            <th>Telepon</th>
            <th>Email</th>
            <th>Kota</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pemasok as $item)
            <tr>
                <td>{{ $item->kode }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->kontak_person }}</td>
                <td>{{ $item->telepon }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->kota }}</td>
                <td>{{ $item->status_aktif ? 'Aktif' : 'Nonaktif' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
