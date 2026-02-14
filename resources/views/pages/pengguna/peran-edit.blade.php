@extends('layouts.app')

@section('content')
@include('partials.breadcrumb', ['breadcrumbs' => [
    ['label' => 'Pengguna & Peran'],
    ['label' => 'Peran', 'url' => route('pengguna.peran.index')],
    ['label' => 'Edit']
]])

@include('pages.shared.page-header', [
    'title' => 'Edit Peran',
    'subtitle' => 'Perbarui data peran.'
])

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('pengguna.peran.update', $peran) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Peran</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $peran->nama) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control" value="{{ old('keterangan', $peran->keterangan) }}">
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i>Simpan</button>
                <a href="{{ route('pengguna.peran.index') }}" class="btn btn-soft">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
