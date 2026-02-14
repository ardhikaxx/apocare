@extends('layouts.app')

@section('content')
@include('partials.breadcrumb', ['breadcrumbs' => [
    ['label' => 'Data Master'],
    ['label' => 'Satuan', 'url' => route('master.satuan.index')],
    ['label' => 'Tambah']
]])

@include('pages.shared.page-header', [
    'title' => 'Tambah Satuan',
    'subtitle' => 'Buat satuan baru untuk produk.'
])

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('master.satuan.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Kode</label>
                    <input type="text" name="kode" class="form-control" value="{{ old('kode') }}" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i>Simpan</button>
                <a href="{{ route('master.satuan.index') }}" class="btn btn-soft">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
