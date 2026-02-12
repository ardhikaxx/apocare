@extends('layouts.app')

@section('title', 'Edit Produk')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('produk.index') }}">Produk</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Edit Produk</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('produk.update', $produk->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Produk</label>
                    <input type="text" name="nama" class="form-control" value="{{ $produk->nama }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Barcode</label>
                    <input type="text" name="barcode" class="form-control" value="{{ $produk->barcode }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori_id" class="form-select" required>
                        @foreach($kategori as $k)
                        <option value="{{ $k->id }}" {{ $produk->kategori_id == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Satuan</label>
                    <select name="satuan_id" class="form-select" required>
                        @foreach($satuan as $s)
                        <option value="{{ $s->id }}" {{ $produk->satuan_id == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jenis Produk</label>
                    <select name="jenis_produk" class="form-select">
                        <option value="Obat" {{ $produk->jenis_produk == 'Obat' ? 'selected' : '' }}>Obat</option>
                        <option value="Alkes" {{ $produk->jenis_produk == 'Alkes' ? 'selected' : '' }}>Alkes</option>
                        <option value="Vitamin" {{ $produk->jenis_produk == 'Vitamin' ? 'selected' : '' }}>Vitamin</option>
                        <option value="Kosmetik" {{ $produk->jenis_produk == 'Kosmetik' ? 'selected' : '' }}>Kosmetik</option>
                        <option value="Umum" {{ $produk->jenis_produk == 'Umum' ? 'selected' : '' }}>Umum</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Golongan Obat</label>
                    <select name="golongan_obat" class="form-select">
                        <option value="Obat Bebas" {{ $produk->golongan_obat == 'Obat Bebas' ? 'selected' : '' }}>Obat Bebas</option>
                        <option value="Obat Bebas Terbatas" {{ $produk->golongan_obat == 'Obat Bebas Terbatas' ? 'selected' : '' }}>Obat Bebas Terbatas</option>
                        <option value="Obat Keras" {{ $produk->golongan_obat == 'Obat Keras' ? 'selected' : '' }}>Obat Keras</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Harga Beli</label>
                    <input type="number" name="harga_beli" class="form-control" value="{{ $produk->harga_beli }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Harga Jual</label>
                    <input type="number" name="harga_jual" class="form-control" value="{{ $produk->harga_jual }}" required>
                </div>
                <div class="col-md-12 mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="perlu_resep" value="1" class="form-check-input" id="perluResep" {{ $produk->perlu_resep ? 'checked' : '' }}>
                        <label class="form-check-label" for="perluResep">Perlu Resep Dokter</label>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan</button>
            <a href="{{ route('produk.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
