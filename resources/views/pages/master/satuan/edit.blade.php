@extends('layouts.app')

@section('title', 'Edit Satuan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('satuan.index') }}">Satuan</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Edit Satuan</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('satuan.update', $satuan->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kode</label>
                    <input type="text" name="kode" class="form-control" value="{{ $satuan->kode }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" value="{{ $satuan->nama }}" required>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3">{{ $satuan->keterangan }}</textarea>
                </div>
                <div class="col-12 mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="status_aktif" value="1" class="form-check-input" id="statusAktif" {{ $satuan->status_aktif ? 'checked' : '' }}>
                        <label class="form-check-label" for="statusAktif">Aktif</label>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan</button>
            <a href="{{ route('satuan.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
