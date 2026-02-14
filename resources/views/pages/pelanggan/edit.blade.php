@extends('layouts.app')

@section('content')
@include('partials.breadcrumb', ['breadcrumbs' => [
    ['label' => 'Pelanggan', 'url' => route('pelanggan.index')],
    ['label' => 'Edit']
]])

@include('pages.shared.page-header', [
    'title' => 'Edit Pelanggan',
    'subtitle' => 'Perbarui data pelanggan.'
])

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('pelanggan.update', $pelanggan) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $pelanggan->nama) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jenis Pelanggan</label>
                    <select name="jenis_pelanggan" class="form-select">
                        @foreach(['REGULAR','RESELLER','KESEHATAN','PERUSAHAAN'] as $jenis)
                            <option value="{{ $jenis }}" @selected($pelanggan->jenis_pelanggan == $jenis)>{{ $jenis }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $pelanggan->telepon) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $pelanggan->email) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $pelanggan->alamat) }}</textarea>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i>Simpan</button>
                <a href="{{ route('pelanggan.index') }}" class="btn btn-soft">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
