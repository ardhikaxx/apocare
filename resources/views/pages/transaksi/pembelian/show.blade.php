@extends('layouts.app')

@section('title', 'Detail Pembelian')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('pembelian.index') }}">Pembelian</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Pembelian: {{ $pembelian->nomor_pembelian }}</h1>
</div>

<div class="card">
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h5>Pemasok</h5>
                <p>{{ $pembelian->pemasok ? $pembelian->pemasok->nama : '-' }}</p>
            </div>
            <div class="col-md-6">
                <h5>Tanggal</h5>
                <p>{{ $pembelian->tanggal_pembelian }}</p>
            </div>
        </div>
        <h5>Total: Rp {{ number_format($pembelian->total_akhir, 0, ',', '.') }}</h5>
        <a href="{{ route('pembelian.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection
