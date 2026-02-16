@extends('layouts.app')

@section('content')
@include('partials.breadcrumb', ['breadcrumbs' => [
    ['label' => 'Data Master'],
    ['label' => 'Produk', 'url' => route('master.produk.index')],
    ['label' => 'Edit']
]])

@include('pages.shared.page-header', [
    'title' => 'Edit Produk',
    'subtitle' => 'Perbarui informasi produk.'
])

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('master.produk.update', $produk) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Kode</label>
                    <input type="text" name="kode" class="form-control" value="{{ old('kode', $produk->kode) }}" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Nama Produk</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $produk->nama) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kategori</label>
                    <select name="kategori_id" class="form-select" required>
                        @foreach($kategori as $item)
                            <option value="{{ $item->id }}" @selected($produk->kategori_id == $item->id)>{{ $item->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Satuan Dasar</label>
                    <select name="satuan_id" class="form-select" required>
                        @foreach($satuan as $item)
                            <option value="{{ $item->id }}" @selected($produk->satuan_id == $item->id)>{{ $item->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Harga Beli</label>
                    <input type="number" step="1" name="harga_beli" class="form-control" value="{{ old('harga_beli', $produk->harga_beli) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Harga Jual</label>
                    <input type="number" step="1" name="harga_jual" class="form-control" value="{{ old('harga_jual', $produk->harga_jual) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Stok Minimum</label>
                    <input type="number" step="1" name="stok_minimum" class="form-control" value="{{ old('stok_minimum', $produk->stok_minimum) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Stok Maksimum</label>
                    <input type="number" step="1" name="stok_maksimum" class="form-control" value="{{ old('stok_maksimum', $produk->stok_maksimum) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Titik Pesan Ulang</label>
                    <input type="number" step="1" name="titik_pesan_ulang" class="form-control" value="{{ old('titik_pesan_ulang', $produk->titik_pesan_ulang) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jenis Produk</label>
                    <select name="jenis_produk" class="form-select">
                        @foreach(['Obat','Alkes','Vitamin','Kosmetik','Umum'] as $jenis)
                            <option value="{{ $jenis }}" @selected($produk->jenis_produk === $jenis)>{{ $jenis }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Perlu Resep</label>
                    <select name="perlu_resep" class="form-select">
                        <option value="0" @selected(!$produk->perlu_resep)>Tidak</option>
                        <option value="1" @selected($produk->perlu_resep)>Ya</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $produk->keterangan) }}</textarea>
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
