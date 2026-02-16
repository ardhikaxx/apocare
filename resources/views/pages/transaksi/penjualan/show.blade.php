@extends('layouts.app')

@section('title', 'Detail Penjualan')

@section('content')
<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
        <h1 class="page-title">Detail Penjualan</h1>
        <p class="text-muted mb-0">{{ $penjualan->nomor_penjualan }}</p>
    </div>
    <a href="{{ route('transaksi.penjualan.index') }}" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h6 class="text-uppercase text-muted">Informasi</h6>
                <div class="mb-2"><strong>Pelanggan:</strong> {{ $penjualan->pelanggan->nama ?? 'Umum' }}</div>
                <div class="mb-2"><strong>Tanggal:</strong> {{ $penjualan->tanggal_penjualan ? \Carbon\Carbon::parse($penjualan->tanggal_penjualan)->format('d/m/Y H:i') : '-' }}</div>
                <div class="mb-2"><strong>Metode:</strong> {{ $penjualan->metode_pembayaran ?? '-' }}</div>
                <div class="mb-2"><strong>Status:</strong> {{ $penjualan->status_pembayaran }}</div>
                <div class="mb-2"><strong>Subtotal:</strong> Rp {{ number_format($penjualan->subtotal, 0, ',', '.') }}</div>
                <div class="mb-2"><strong>Diskon:</strong> Rp {{ number_format($penjualan->jumlah_diskon, 0, ',', '.') }}</div>
                <div class="mb-2"><strong>Pajak:</strong> Rp {{ number_format($penjualan->jumlah_pajak, 0, ',', '.') }}</div>
                <div class="mb-2"><strong>Total:</strong> Rp {{ number_format($penjualan->total_akhir, 0, ',', '.') }}</div>
                <div class="mb-2"><strong>Bayar:</strong> Rp {{ number_format($penjualan->jumlah_bayar, 0, ',', '.') }}</div>
                <div class="mb-0"><strong>Kembalian:</strong> Rp {{ number_format($penjualan->jumlah_kembalian, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h6 class="text-uppercase text-muted">Item Penjualan</h6>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Diskon</th>
                                <th>Pajak</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penjualan->details as $detail)
                                <tr>
                                    <td>{{ $detail->produk->nama ?? '-' }}</td>
                                    <td>{{ formatAngka($detail->jumlah) }}</td>
                                    <td>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($detail->jumlah_diskon, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($detail->jumlah_pajak, 0, ',', '.') }}</td>
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
