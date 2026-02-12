@extends('layouts.app')

@section('title', 'Stok Produk')

@section('breadcrumb')
<li class="breadcrumb-item active">Stok</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Stok Produk</h1>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Kategori</th>
                    <th>Jumlah</th>
                    <th>Harga Beli</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stok as $item)
                <tr>
                    <td>{{ $item->produk->nama }}</td>
                    <td>{{ $item->produk->kategori->nama }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>Rp {{ number_format($item->harga_beli_terakhir, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('stok.show', $item->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $stok->links() }}
    </div>
</div>
@endsection
