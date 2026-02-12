@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('kategori.index') }}">Kategori</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Edit Kategori</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('kategori.update', $kategori->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kode</label>
                    <input type="text" name="kode" class="form-control" value="{{ $kategori->kode }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" value="{{ $kategori->nama }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Parent Kategori</label>
                    <select name="parent_id" class="form-select">
                        <option value="">-</option>
                        @foreach($parents as $p)
                        <option value="{{ $p->id }}" {{ $kategori->parent_id == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Ikon</label>
                    <input type="text" name="ikon" class="form-control" value="{{ $kategori->ikon }}">
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3">{{ $kategori->keterangan }}</textarea>
                </div>
                <div class="col-12 mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="status_aktif" value="1" class="form-check-input" id="statusAktif" {{ $kategori->status_aktif ? 'checked' : '' }}>
                        <label class="form-check-label" for="statusAktif">Aktif</label>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan</button>
            <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
