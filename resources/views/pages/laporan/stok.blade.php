@extends('layouts.app')

@section('title', 'Laporan Stok')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Laporan</a></li>
<li class="breadcrumb-item active">Stok</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Laporan Stok</h1>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Harga Beli</th>
                    <th>Nilai Stok</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stok as $item)
                <tr>
                    <td>{{ $item->produk->nama }}</td>
                    <td>{{ $item->produk->kategori->nama }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>Rp {{ number_format($item->harga_beli_terakhir, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->jumlah * $item->harga_beli_terakhir, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
