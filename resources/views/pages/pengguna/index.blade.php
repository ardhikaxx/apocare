@extends('layouts.app')

@section('title', 'Pengguna')

@section('breadcrumb')
<li class="breadcrumb-item active">Pengguna</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1 class="page-title">Pengguna</h1>
    <a href="{{ route('pengguna.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Tambah Pengguna
    </a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengguna as $item)
                <tr>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->username }}</td>
                    <td>{{ $item->role ? $item->role->nama : '-' }}</td>
                    <td>
                        <span class="badge bg-{{ $item->status_aktif ? 'success' : 'danger' }}">
                            {{ $item->status_aktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('pengguna.edit', $item->id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $pengguna->links() }}
    </div>
</div>
@endsection
