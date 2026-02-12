@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('produk.index') }}">Produk</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Tambah Produk</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('produk.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Produk</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Barcode</label>
                    <input type="text" name="barcode" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori_id" class="form-select" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($kategori as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Satuan</label>
                    <select name="satuan_id" class="form-select" required>
                        <option value="">Pilih Satuan</option>
                        @foreach($satuan as $s)
                        <option value="{{ $s->id }}">{{ $s->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jenis Produk</label>
                    <select name="jenis_produk" class="form-select">
                        <option value="Obat">Obat</option>
                        <option value="Alkes">Alkes</option>
                        <option value="Vitamin">Vitamin</option>
                        <option value="Kosmetik">Kosmetik</option>
                        <option value="Umum">Umum</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Golongan Obat</label>
                    <select name="golongan_obat" class="form-select">
                        <option value="Obat Bebas">Obat Bebas</option>
                        <option value="Obat Bebas Terbatas">Obat Bebas Terbatas</option>
                        <option value="Obat Keras">Obat Keras</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Harga Beli</label>
                    <input type="number" name="harga_beli" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Harga Jual</label>
                    <input type="number" name="harga_jual" class="form-control" required>
                </div>
                <div class="col-md-12 mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="perlu_resep" value="1" class="form-check-input" id="perluResep">
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
