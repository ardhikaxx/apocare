@extends('layouts.app')

@section('title', 'Detail Stok')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('stok.index') }}">Stok</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">{{ $stok->produk->nama }}</h1>
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Stok Tersedia</h6>
                        <h3 class="text-success">{{ $stok->jumlah_tersedia }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Stok Reservasi</h6>
                        <h3 class="text-warning">{{ $stok->jumlah_reservasi }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Harga Beli</h6>
                        <h3 class="text-primary">Rp {{ number_format($stok->harga_beli_terakhir, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Nilai Stok</h6>
                        <h3 class="text-info">Rp {{ number_format($stok->jumlah * $stok->harga_beli_terakhir, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <a href="{{ route('stok.index') }}" class="btn btn-secondary mt-3">Kembali</a>
    </div>
</div>
@endsection
