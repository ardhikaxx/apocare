@extends('layouts.app')

@section('content')
@include('partials.breadcrumb', ['breadcrumbs' => [
    ['label' => 'Karyawan', 'url' => route('karyawan.index')],
    ['label' => 'Edit']
]])

@include('pages.shared.page-header', [
    'title' => 'Edit Karyawan',
    'subtitle' => 'Perbarui data karyawan.'
])

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('karyawan.update', $karyawan) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Pengguna</label>
                    <select name="pengguna_id" class="form-select" required>
                        @foreach($pengguna as $item)
                            <option value="{{ $item->id }}" @selected($karyawan->pengguna_id == $item->id)>{{ $item->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nomor Karyawan</label>
                    <input type="text" name="nomor_karyawan" class="form-control" value="{{ old('nomor_karyawan', $karyawan->nomor_karyawan) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jabatan</label>
                    <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan', $karyawan->jabatan) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Departemen</label>
                    <input type="text" name="departemen" class="form-control" value="{{ old('departemen', $karyawan->departemen) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status Kepegawaian</label>
                    <select name="status_kepegawaian" class="form-select">
                        @foreach(['TETAP','KONTRAK','MAGANG','FREELANCE'] as $status)
                            <option value="{{ $status }}" @selected($karyawan->status_kepegawaian == $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Bergabung</label>
                    <input type="date" name="tanggal_bergabung" class="form-control" value="{{ old('tanggal_bergabung', $karyawan->tanggal_bergabung) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="catatan" class="form-control" rows="3">{{ old('catatan', $karyawan->catatan) }}</textarea>
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
