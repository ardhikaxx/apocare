@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('pengguna.index') }}">Pengguna</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Tambah Pengguna</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('pengguna.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Role</label>
                    <select name="role_id" class="form-select" required>
                        @foreach($peran as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
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
