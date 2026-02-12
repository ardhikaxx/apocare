@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Laporan</a></li>
<li class="breadcrumb-item active">Penjualan</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Laporan Penjualan</h1>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('laporan.penjualan') }}" method="GET" class="row">
            <div class="col-md-3">
                <input type="date" name="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="tanggal_selesai" class="form-control" value="{{ request('tanggal_selesai') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="text-muted">Total Penjualan</h6>
                <h3>Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="text-muted">Jumlah Transaksi</h6>
                <h3>{{ $totalTransaksi }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nomor</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penjualan as $item)
                <tr>
                    <td>{{ $item->nomor_penjualan }}</td>
                    <td>{{ $item->tanggal_penjualan }}</td>
                    <td>{{ $item->pelanggan ? $item->pelanggan->nama : 'Umum' }}</td>
                    <td>Rp {{ number_format($item->total_akhir, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
