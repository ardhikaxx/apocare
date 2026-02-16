@extends('layouts.app')

@section('title', 'Detail Penyesuaian Stok')

@section('content')
<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
        <h1 class="page-title">Detail Penyesuaian</h1>
        <p class="text-muted mb-0">{{ $penyesuaian->nomor_penyesuaian }}</p>
    </div>
    <a href="{{ route('persediaan.penyesuaian.index') }}" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h6 class="text-uppercase text-muted">Informasi</h6>
                <div class="mb-2"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($penyesuaian->tanggal_penyesuaian)->format('d/m/Y') }}</div>
                <div class="mb-2"><strong>Jenis:</strong> {{ $penyesuaian->jenis_penyesuaian }}</div>
                <div class="mb-2"><strong>Status:</strong> {{ $penyesuaian->status }}</div>
                <div class="mb-2"><strong>Total Item:</strong> {{ $penyesuaian->total_item }}</div>
                <div class="mb-0"><strong>Catatan:</strong> {{ $penyesuaian->catatan ?? '-' }}</div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h6 class="text-uppercase text-muted">Detail Item</h6>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Sistem</th>
                                <th>Aktual</th>
                                <th>Selisih</th>
                                <th>Harga</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penyesuaian->detail as $detail)
                                <tr>
                                    <td>{{ $detail->produk->nama ?? '-' }}</td>
                                    <td>{{ formatAngka($detail->jumlah_sistem) }}</td>
                                    <td>{{ formatAngka($detail->jumlah_aktual) }}</td>
                                    <td>{{ formatAngka($detail->selisih) }}</td>
                                    <td>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($detail->total_nilai, 0, ',', '.') }}</td>
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
