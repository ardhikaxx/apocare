@extends('layouts.app')

@section('title', 'Detail Retur Pembelian')

@section('content')
<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
        <h1 class="page-title">Detail Retur Pembelian</h1>
        <p class="text-muted mb-0">{{ $returPembelian->nomor_retur }}</p>
    </div>
    <a href="{{ route('transaksi.retur.index') }}" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h6 class="text-uppercase text-muted">Informasi</h6>
                <div class="mb-2"><strong>Pemasok:</strong> {{ $returPembelian->pemasok->nama ?? '-' }}</div>
                <div class="mb-2"><strong>Tanggal:</strong> {{ $returPembelian->tanggal_retur ? \Carbon\Carbon::parse($returPembelian->tanggal_retur)->format('d/m/Y') : '-' }}</div>
                <div class="mb-2"><strong>Status:</strong> {{ $returPembelian->status }}</div>
                <div class="mb-2"><strong>Total:</strong> Rp {{ number_format($returPembelian->total, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h6 class="text-uppercase text-muted">Item Retur</h6>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($returPembelian->details as $detail)
                                <tr>
                                    <td>{{ $detail->produk->nama ?? '-' }}</td>
                                    <td>{{ number_format($detail->jumlah, 2, ',', '.') }}</td>
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
