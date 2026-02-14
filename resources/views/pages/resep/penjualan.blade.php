@extends('layouts.app')

@section('title', 'Penjualan Resep')

@section('content')
<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
        <h1 class="page-title">Penjualan Resep</h1>
        <p class="text-muted mb-0">{{ $resep->nomor_resep }} - {{ $resep->pelanggan->nama ?? 'Pasien' }}</p>
    </div>
    <a href="{{ route('resep.show', $resep) }}" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<form action="{{ route('resep.penjualan.store', $resep) }}" method="POST">
    @csrf
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted">Pembayaran</h6>
                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select name="metode_pembayaran" class="form-select" required>
                            <option value="TUNAI">Tunai</option>
                            <option value="DEBIT">Debit</option>
                            <option value="KREDIT">Kredit</option>
                            <option value="TRANSFER">Transfer</option>
                            <option value="EWALLET">E-Wallet</option>
                            <option value="QRIS">QRIS</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah Bayar</label>
                        <input type="number" name="jumlah_bayar" class="form-control" value="{{ $resep->total_harga }}" min="0" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-check me-2"></i>Selesaikan Penjualan
                    </button>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted">Detail Resep</h6>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Dosis</th>
                                    <th>Frekuensi</th>
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
                                        <td>{{ number_format($detail->jumlah_resep, 2, ',', '.') }}</td>
                                        <td>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-end">Total</th>
                                    <th>Rp {{ number_format($resep->total_harga, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
