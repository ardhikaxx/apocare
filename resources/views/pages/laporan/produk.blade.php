@extends('layouts.app')

@section('title', 'Laporan Produk')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Laporan</a></li>
<li class="breadcrumb-item active">Produk</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Laporan Produk</h1>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produk as $item)
                <tr>
                    <td>{{ $item->kode }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->kategori->nama }}</td>
                    <td>Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
