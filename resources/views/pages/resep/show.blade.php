@extends('layouts.app')

@section('title', 'Detail Resep')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('resep.index') }}">Resep</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Resep: {{ $resep->nomor_resep }}</h1>
</div>

<div class="card">
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h5>Pasien</h5>
                <p>{{ $resep->pelanggan ? $resep->pelanggan->nama : '-' }}</p>
            </div>
            <div class="col-md-6">
                <h5>Dokter</h5>
                <p>{{ $resep->dokter ? $resep->dokter->nama : '-' }}</p>
            </div>
            <div class="col-md-6">
                <h5>Diagnosa</h5>
                <p>{{ $resep->diagnosa }}</p>
            </div>
            <div class="col-md-6">
                <h5>Status</h5>
                <span class="badge bg-{{ $resep->status == 'SELESAI' ? 'success' : 'warning' }}">{{ $resep->status }}</span>
            </div>
        </div>
        <a href="{{ route('resep.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection
