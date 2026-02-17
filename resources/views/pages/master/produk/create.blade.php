@extends('layouts.app')

@section('content')
@include('partials.breadcrumb', ['breadcrumbs' => [
    ['label' => 'Data Master'],
    ['label' => 'Produk', 'url' => route('master.produk.index')],
    ['label' => 'Tambah']
]])

@include('pages.shared.page-header', [
    'title' => 'Tambah Produk',
    'subtitle' => 'Masukkan data produk baru.'
])

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('master.produk.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Kode</label>
                    <input type="text" name="kode" class="form-control" value="{{ old('kode') }}" placeholder="PRD-000001">
                </div>
                <div class="col-md-8">
                    <label class="form-label">Nama Produk</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kategori</label>
                    <select name="kategori_id" class="form-select" required>
                        <option value="">Pilih kategori</option>
                        @foreach($kategori as $item)
                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Satuan Dasar</label>
                    <select name="satuan_id" class="form-select" required>
                        <option value="">Pilih satuan</option>
                        @foreach($satuan as $item)
                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Harga Beli</label>
                    <input type="number" step="1" name="harga_beli" class="form-control" value="{{ old('harga_beli', 0) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Harga Jual</label>
                    <input type="number" step="1" name="harga_jual" class="form-control" value="{{ old('harga_jual', 0) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Stok Minimum</label>
                    <input type="number" step="1" name="stok_minimum" class="form-control" value="{{ old('stok_minimum', 0) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Stok Maksimum</label>
                    <input type="number" step="1" name="stok_maksimum" class="form-control" value="{{ old('stok_maksimum', 0) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Titik Pesan Ulang</label>
                    <input type="number" step="1" name="titik_pesan_ulang" class="form-control" value="{{ old('titik_pesan_ulang', 0) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Golongan Produk</label>
                    <select name="jenis_produk" class="form-select">
                        <option value="umum">Obat Umum</option>
                        <option value="keras">Obat Keras (Obt, QQ, K, dll)</option>
                        <option value="psikotropika">Psikotropika</option>
                        <option value="golongan">Golongan (Narcotic)</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">No. IJin Edar (BPOM)</label>
                    <input type="text" name="no_ijin_edar" class="form-control" value="{{ old('no_ijin_edar') }}" placeholder="DKLxxxxxxxxxxx">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Perlu Resep</label>
                    <select name="perlu_resep" class="form-select">
                        <option value="0">Tidak</option>
                        <option value="1">Ya</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Expired</label>
                    <input type="date" name="tanggal_expired" class="form-control" value="{{ old('tanggal_expired') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status Aktif</label>
                    <select name="status_aktif" class="form-select">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i>Simpan</button>
                <a href="{{ route('master.produk.index') }}" class="btn btn-soft">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
