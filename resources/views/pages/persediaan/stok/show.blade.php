@extends('layouts.app')

@section('title', 'Detail Stok')

@section('content')
<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
        <h1 class="page-title">Detail Stok</h1>
        <p class="text-muted mb-0">{{ $stok->produk->nama ?? '-' }}</p>
    </div>
    <a href="{{ route('persediaan.stok.index') }}" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h6 class="text-uppercase text-muted">Informasi Produk</h6>
                <div class="mb-2"><strong>Kode:</strong> {{ $stok->produk->kode ?? '-' }}</div>
                <div class="mb-2"><strong>Kategori:</strong> {{ $stok->produk->kategori->nama ?? '-' }}</div>
                <div class="mb-2"><strong>Lokasi:</strong> {{ $stok->produk->lokasi_rak ?? '-' }}</div>
                <div class="mb-2"><strong>Stok:</strong> {{ formatAngka($stok->jumlah) }}</div>
                <div class="mb-2"><strong>Reserved:</strong> {{ formatAngka($stok->jumlah_reservasi) }}</div>
                <div class="mb-0"><strong>Tersedia:</strong> {{ formatAngka($stok->jumlah_tersedia) }}</div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h6 class="text-uppercase text-muted">Batch Produk</h6>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Batch</th>
                                <th>Produksi</th>
                                <th>Exp</th>
                                <th>Jumlah</th>
                                <th>Harga Beli</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stok->produk->batchProduk as $batch)
                                <tr>
                                    <td>{{ $batch->nomor_batch }}</td>
                                    <td>{{ $batch->tanggal_produksi ? \Carbon\Carbon::parse($batch->tanggal_produksi)->format('d/m/Y') : '-' }}</td>
                                    <td>{{ $batch->tanggal_kadaluarsa ? \Carbon\Carbon::parse($batch->tanggal_kadaluarsa)->format('d/m/Y') : '-' }}</td>
                                    <td>{{ formatAngka($batch->jumlah) }}</td>
                                    <td>Rp {{ number_format($batch->harga_beli, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada batch.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
