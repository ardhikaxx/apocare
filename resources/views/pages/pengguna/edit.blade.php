@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('pengguna.index') }}">Pengguna</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Edit Pengguna</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('pengguna.update', $pengguna->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" value="{{ $pengguna->nama }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $pengguna->email }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="{{ $pengguna->username }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password (kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Role</label>
                    <select name="role_id" class="form-select" required>
                        @foreach($peran as $p)
                        <option value="{{ $p->id }}" {{ $pengguna->role_id == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan</button>
            <a href="{{ route('pengguna.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
