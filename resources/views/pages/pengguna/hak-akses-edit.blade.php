@extends('layouts.app')

@section('content')
@include('partials.breadcrumb', ['breadcrumbs' => [
    ['label' => 'Pengguna & Peran'],
    ['label' => 'Hak Akses', 'url' => route('pengguna.hak-akses.index')],
    ['label' => 'Edit']
]])

@include('pages.shared.page-header', [
    'title' => 'Edit Hak Akses',
    'subtitle' => 'Perbarui detail hak akses.'
])

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('pengguna.hak-akses.update', $hakAkses) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $hakAkses->nama) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kode</label>
                    <input type="text" name="kode" class="form-control" value="{{ old('kode', $hakAkses->kode) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Modul</label>
                    <input type="text" name="modul" class="form-control" value="{{ old('modul', $hakAkses->modul) }}" required>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i>Simpan</button>
                <a href="{{ route('pengguna.hak-akses.index') }}" class="btn btn-soft">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
