@extends('layouts.app')

@section('title', 'Detail Resep')

@section('content')
<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
        <h1 class="page-title">Detail Resep</h1>
        <p class="text-muted mb-0">{{ $resep->nomor_resep }}</p>
    </div>
    <div class="d-flex gap-2">
        @if($resep->status !== 'SELESAI')
            <a href="{{ route('resep.penjualan.create', $resep) }}" class="btn btn-success">
                <i class="fa-solid fa-cash-register me-2"></i>Buat Penjualan
            </a>
        @endif
        <a href="{{ route('resep.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h6 class="text-uppercase text-muted">Informasi</h6>
                <div class="mb-2"><strong>Pasien:</strong> {{ $resep->pelanggan->nama ?? '-' }}</div>
                <div class="mb-2"><strong>Dokter:</strong> {{ $resep->dokter->nama ?? '-' }}</div>
                <div class="mb-2"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($resep->tanggal_resep)->format('d/m/Y') }}</div>
                <div class="mb-2"><strong>Status:</strong> {{ $resep->status }}</div>
                <div class="mb-2"><strong>Total Item:</strong> {{ $resep->total_item }}</div>
                <div class="mb-2"><strong>Total Harga:</strong> Rp {{ number_format($resep->total_harga, 0, ',', '.') }}</div>
                <div class="mb-0"><strong>Diagnosa:</strong> {{ $resep->diagnosa ?? '-' }}</div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h6 class="text-uppercase text-muted">Detail Obat</h6>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Dosis</th>
                                <th>Frekuensi</th>
                                <th>Durasi</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resep->details as $detail)
                                <tr>
                                    <td>{{ $detail->produk->nama ?? '-' }}</td>
                                    <td>{{ $detail->dosis ?? '-' }}</td>
                                    <td>{{ $detail->frekuensi ?? '-' }}</td>
                                    <td>{{ $detail->durasi ?? '-' }}</td>
                                    <td>{{ number_format($detail->jumlah_resep, 2, ',', '.') }}</td>
                                    <td>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
