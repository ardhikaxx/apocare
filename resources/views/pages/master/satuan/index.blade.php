@extends('layouts.app')

@section('title', 'Satuan Produk')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Data Master</a></li>
<li class="breadcrumb-item active">Satuan</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1 class="page-title">Satuan Produk</h1>
    <a href="{{ route('satuan.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Tambah Satuan
    </a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Keterangan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($satuan as $item)
                <tr>
                    <td>{{ $item->kode }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->keterangan }}</td>
                    <td>
                        <span class="badge bg-{{ $item->status_aktif ? 'success' : 'danger' }}">
                            {{ $item->status_aktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('satuan.edit', $item->id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('satuan.destroy', $item->id) }}" method="POST" class="d-inline">
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
        {{ $satuan->links() }}
    </div>
</div>
@endsection
