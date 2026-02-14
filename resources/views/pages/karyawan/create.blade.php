@extends('layouts.app')

@section('content')
@include('partials.breadcrumb', ['breadcrumbs' => [
    ['label' => 'Karyawan', 'url' => route('karyawan.index')],
    ['label' => 'Tambah']
]])

@include('pages.shared.page-header', [
    'title' => 'Tambah Karyawan',
    'subtitle' => 'Masukkan data karyawan baru.'
])

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('karyawan.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Pengguna</label>
                    <select name="pengguna_id" class="form-select" required>
                        <option value="">Pilih pengguna</option>
                        @foreach($pengguna as $item)
                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nomor Karyawan</label>
                    <input type="text" name="nomor_karyawan" class="form-control" value="{{ old('nomor_karyawan') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jabatan</label>
                    <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Departemen</label>
                    <input type="text" name="departemen" class="form-control" value="{{ old('departemen') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status Kepegawaian</label>
                    <select name="status_kepegawaian" class="form-select">
                        <option value="TETAP">Tetap</option>
                        <option value="KONTRAK">Kontrak</option>
                        <option value="MAGANG">Magang</option>
                        <option value="FREELANCE">Freelance</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Bergabung</label>
                    <input type="date" name="tanggal_bergabung" class="form-control" value="{{ old('tanggal_bergabung') }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="catatan" class="form-control" rows="3">{{ old('catatan') }}</textarea>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i>Simpan</button>
                <a href="{{ route('karyawan.index') }}" class="btn btn-soft">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
