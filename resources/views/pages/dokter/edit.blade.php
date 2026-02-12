@extends('layouts.app')

@section('title', 'Edit Dokter')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dokter.index') }}">Dokter</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Edit Dokter</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('dokter.update', $dokter->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" value="{{ $dokter->nama }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Spesialisasi</label>
                    <input type="text" name="spesialisasi" class="form-control" value="{{ $dokter->spesialisasi }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nomor SIP</label>
                    <input type="text" name="nomor_sip" class="form-control" value="{{ $dokter->nomor_sip }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="telepon" class="form-control" value="{{ $dokter->telepon }}">
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan</button>
            <a href="{{ route('dokter.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
