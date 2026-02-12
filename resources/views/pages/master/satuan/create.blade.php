@extends('layouts.app')

@section('title', 'Tambah Satuan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('satuan.index') }}">Satuan</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Tambah Satuan</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('satuan.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kode</label>
                    <input type="text" name="kode" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3"></textarea>
                </div>
                <div class="col-12 mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="status_aktif" value="1" class="form-check-input" id="statusAktif" checked>
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
