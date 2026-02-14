@extends('layouts.app')

@section('content')
@include('partials.breadcrumb', ['breadcrumbs' => [
    ['label' => 'Dokter', 'url' => route('dokter.index')],
    ['label' => 'Edit']
]])

@include('pages.shared.page-header', [
    'title' => 'Edit Dokter',
    'subtitle' => 'Perbarui informasi dokter.'
])

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('dokter.update', $dokter) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Dokter</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $dokter->nama) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Spesialisasi</label>
                    <input type="text" name="spesialisasi" class="form-control" value="{{ old('spesialisasi', $dokter->spesialisasi) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nomor SIP</label>
                    <input type="text" name="nomor_sip" class="form-control" value="{{ old('nomor_sip', $dokter->nomor_sip) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $dokter->telepon) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $dokter->email) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Rumah Sakit/Klinik</label>
                    <input type="text" name="rumah_sakit" class="form-control" value="{{ old('rumah_sakit', $dokter->rumah_sakit) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $dokter->alamat) }}</textarea>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i>Simpan</button>
                <a href="{{ route('dokter.index') }}" class="btn btn-soft">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
