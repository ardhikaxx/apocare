@extends('layouts.app')

@section('title', 'Edit Pelanggan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('pelanggan.index') }}">Pelanggan</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Edit Pelanggan</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('pelanggan.update', $pelanggan->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" value="{{ $pelanggan->nama }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="telepon" class="form-control" value="{{ $pelanggan->telepon }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $pelanggan->email }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select">
                        <option value="PRIA" {{ $pelanggan->jenis_kelamin == 'PRIA' ? 'selected' : '' }}>PRIA</option>
                        <option value="WANITA" {{ $pelanggan->jenis_kelamin == 'WANITA' ? 'selected' : '' }}>WANITA</option>
                    </select>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2">{{ $pelanggan->alamat }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan</button>
            <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
