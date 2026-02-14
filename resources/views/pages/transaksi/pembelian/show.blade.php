@extends('layouts.app')

@section('title', 'Detail Pembelian')

@section('content')
<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
        <h1 class="page-title">Detail Pembelian</h1>
        <p class="text-muted mb-0">{{ $pembelian->nomor_pembelian }}</p>
    </div>
    <a href="{{ route('transaksi.pembelian.index') }}" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h6 class="text-uppercase text-muted">Informasi</h6>
                <div class="mb-2"><strong>Pemasok:</strong> {{ $pembelian->pemasok->nama ?? '-' }}</div>
                <div class="mb-2"><strong>Tanggal:</strong> {{ $pembelian->tanggal_pembelian ? \Carbon\Carbon::parse($pembelian->tanggal_pembelian)->format('d/m/Y') : '-' }}</div>
                <div class="mb-2"><strong>Status:</strong> {{ $pembelian->status }}</div>
                <div class="mb-2"><strong>Subtotal:</strong> Rp {{ number_format($pembelian->subtotal, 0, ',', '.') }}</div>
                <div class="mb-2"><strong>Diskon:</strong> Rp {{ number_format($pembelian->jumlah_diskon, 0, ',', '.') }}</div>
                <div class="mb-2"><strong>Pajak:</strong> Rp {{ number_format($pembelian->jumlah_pajak, 0, ',', '.') }}</div>
                <div class="mb-2"><strong>Total:</strong> Rp {{ number_format($pembelian->total_akhir, 0, ',', '.') }}</div>
                <div class="mb-2"><strong>Bayar:</strong> Rp {{ number_format($pembelian->jumlah_bayar, 0, ',', '.') }}</div>
                <div class="mb-0"><strong>Sisa:</strong> Rp {{ number_format($pembelian->sisa_bayar, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h6 class="text-uppercase text-muted">Item Pembelian</h6>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Qty Pesan</th>
                                <th>Qty Terima</th>
                                <th>Harga</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pembelian->details as $detail)
                                <tr>
                                    <td>{{ $detail->produk->nama ?? '-' }}</td>
                                    <td>{{ number_format($detail->jumlah_pesan, 2, ',', '.') }}</td>
                                    <td>{{ number_format($detail->jumlah_terima, 2, ',', '.') }}</td>
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
