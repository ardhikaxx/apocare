@extends('layouts.app')

@section('title', 'Detail Penjualan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('penjualan.index') }}">Penjualan</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Penjualan: {{ $penjualan->nomor_penjualan }}</h1>
</div>

<div class="card">
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h5>Pelanggan</h5>
                <p>{{ $penjualan->pelanggan ? $penjualan->pelanggan->nama : 'Umum' }}</p>
            </div>
            <div class="col-md-6">
                <h5>Tanggal</h5>
                <p>{{ $penjualan->tanggal_penjualan }}</p>
            </div>
        </div>
        <h5>Total: Rp {{ number_format($penjualan->total_akhir, 0, ',', '.') }}</h5>
        <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection
