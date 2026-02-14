@extends('layouts.app')

@section('content')
@include('partials.breadcrumb', ['breadcrumbs' => [
    ['label' => 'Data Master'],
    ['label' => 'Kategori', 'url' => route('master.kategori.index')],
    ['label' => 'Edit']
]])

@include('pages.shared.page-header', [
    'title' => 'Edit Kategori',
    'subtitle' => 'Perbarui informasi kategori produk.'
])

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('master.kategori.update', $kategori) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Kode</label>
                    <input type="text" name="kode" class="form-control" value="{{ old('kode', $kategori->kode) }}" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $kategori->nama) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Parent</label>
                    <select name="parent_id" class="form-select">
                        <option value="">Tanpa Parent</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" @selected($kategori->parent_id == $parent->id)>{{ $parent->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Icon</label>
                    <input type="text" name="ikon" class="form-control" value="{{ old('ikon', $kategori->ikon) }}" placeholder="fa-solid fa-pills">
                </div>
                <div class="col-12">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $kategori->keterangan) }}</textarea>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i>Simpan</button>
                <a href="{{ route('master.kategori.index') }}" class="btn btn-soft">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
