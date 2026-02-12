@extends('layouts.app')

@section('title', 'Produk')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Data Master</a></li>
<li class="breadcrumb-item active">Produk</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1 class="page-title">Daftar Produk</h1>
    <a href="{{ route('produk.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Tambah Produk
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('produk.index') }}" method="GET" class="row mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="kategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($kategori as $k)
                    <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i></button>
            </div>
        </form>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Status</th>
                    <th>Aksi</th>
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
                    <td>
                        <span class="badge bg-{{ $item->status_aktif ? 'success' : 'danger' }}">
                            {{ $item->status_aktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('produk.edit', $item->id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('produk.destroy', $item->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $produk->links() }}
    </div>
</div>
@endsection
