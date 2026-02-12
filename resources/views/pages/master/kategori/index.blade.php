@extends('layouts.app')

@section('title', 'Kategori Produk')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Data Master</a></li>
<li class="breadcrumb-item active">Kategori</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1 class="page-title">Kategori Produk</h1>
    <a href="{{ route('kategori.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Tambah Kategori
    </a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Parent</th>
                    <th>Ikon</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kategori as $item)
                <tr>
                    <td>{{ $item->kode }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->parent ? $item->parent->nama : '-' }}</td>
                    <td><i class="{{ $item->ikon }}"></i></td>
                    <td>
                        <span class="badge bg-{{ $item->status_aktif ? 'success' : 'danger' }}">
                            {{ $item->status_aktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('kategori.edit', $item->id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('kategori.destroy', $item->id) }}" method="POST" class="d-inline">
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
        {{ $kategori->links() }}
    </div>
</div>
@endsection
