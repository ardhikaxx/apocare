@extends('layouts.app')

@section('title', 'Tambah Pemasok')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('pemasok.index') }}">Pemasok</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Tambah Pemasok</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('pemasok.store') }}" method="POST">
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
                    <label class="form-label">Telepon</label>
                    <input type="text" name="telepon" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kontak Person</label>
                    <input type="text" name="kontak_person" class="form-control">
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2"></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kota</label>
                    <input type="text" name="kota" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">NPWP</label>
                    <input type="text" name="npwp" class="form-control">
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan</button>
            <a href="{{ route('pemasok.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
