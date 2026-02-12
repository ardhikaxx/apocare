@extends('layouts.app')

@section('title', 'Pelanggan')

@section('breadcrumb')
<li class="breadcrumb-item active">Pelanggan</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1 class="page-title">Pelanggan</h1>
    <a href="{{ route('pelanggan.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Tambah Pelanggan
    </a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Telepon</th>
                    <th>Jenis</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pelanggan as $item)
                <tr>
                    <td>{{ $item->kode }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->telepon }}</td>
                    <td>{{ $item->jenis_pelanggan }}</td>
                    <td>
                        <span class="badge bg-{{ $item->status_aktif ? 'success' : 'danger' }}">
                            {{ $item->status_aktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('pelanggan.edit', $item->id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('pelanggan.destroy', $item->id) }}" method="POST" class="d-inline">
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
        {{ $pelanggan->links() }}
    </div>
</div>
@endsection
