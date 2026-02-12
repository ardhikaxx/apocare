@extends('layouts.app')

@section('title', 'Tambah Dokter')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dokter.index') }}">Dokter</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Tambah Dokter</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('dokter.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Spesialisasi</label>
                    <input type="text" name="spesialisasi" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nomor SIP</label>
                    <input type="text" name="nomor_sip" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="telepon" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Rumah Sakit</label>
                    <input type="text" name="rumah_sakit" class="form-control">
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan</button>
            <a href="{{ route('dokter.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
