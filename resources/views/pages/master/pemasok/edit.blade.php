@extends('layouts.app')

@section('content')
@include('partials.breadcrumb', ['breadcrumbs' => [
    ['label' => 'Data Master'],
    ['label' => 'Pemasok', 'url' => route('master.pemasok.index')],
    ['label' => 'Edit']
]])

@include('pages.shared.page-header', [
    'title' => 'Edit Pemasok',
    'subtitle' => 'Perbarui informasi pemasok.'
])

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('master.pemasok.update', $pemasok) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Pemasok</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $pemasok->nama) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kontak Person</label>
                    <input type="text" name="kontak_person" class="form-control" value="{{ old('kontak_person', $pemasok->kontak_person) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $pemasok->telepon) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $pemasok->email) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kota</label>
                    <input type="text" name="kota" class="form-control" value="{{ old('kota', $pemasok->kota) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Provinsi</label>
                    <input type="text" name="provinsi" class="form-control" value="{{ old('provinsi', $pemasok->provinsi) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kode Pos</label>
                    <input type="text" name="kode_pos" class="form-control" value="{{ old('kode_pos', $pemasok->kode_pos) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">NPWP</label>
                    <input type="text" name="npwp" class="form-control" value="{{ old('npwp', $pemasok->npwp) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Termin Pembayaran (hari)</label>
                    <input type="number" name="termin_pembayaran" class="form-control" value="{{ old('termin_pembayaran', $pemasok->termin_pembayaran) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Limit Kredit</label>
                    <input type="number" step="0.01" name="limit_kredit" class="form-control" value="{{ old('limit_kredit', $pemasok->limit_kredit) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $pemasok->alamat) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="catatan" class="form-control" rows="2">{{ old('catatan', $pemasok->catatan) }}</textarea>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i>Simpan</button>
                <a href="{{ route('master.pemasok.index') }}" class="btn btn-soft">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
