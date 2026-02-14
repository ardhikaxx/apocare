@extends('layouts.app')

@section('title', 'Retur')

@section('content')
<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
        <h1 class="page-title">Retur</h1>
        <p class="text-muted mb-0">Kelola retur pembelian dan penjualan.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('transaksi.retur.pembelian.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-arrow-rotate-left me-2"></i>Retur Pembelian
        </a>
        <a href="{{ route('transaksi.retur.penjualan.create') }}" class="btn btn-outline-primary">
            <i class="fa-solid fa-arrow-rotate-left me-2"></i>Retur Penjualan
        </a>
    </div>
</div>

<ul class="nav nav-tabs" id="returTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="retur-pembelian-tab" data-bs-toggle="tab" data-bs-target="#retur-pembelian" type="button" role="tab">Retur Pembelian</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="retur-penjualan-tab" data-bs-toggle="tab" data-bs-target="#retur-penjualan" type="button" role="tab">Retur Penjualan</button>
    </li>
</ul>
<div class="tab-content mt-3" id="returTabContent">
    <div class="tab-pane fade show active" id="retur-pembelian" role="tabpanel">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped datatable">
                        <thead>
                            <tr>
                                <th>Nomor</th>
                                <th>Tanggal</th>
                                <th>Pemasok</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($returPembelian as $item)
                                <tr>
                                    <td>{{ $item->nomor_retur }}</td>
                                    <td>{{ $item->tanggal_retur ? \Carbon\Carbon::parse($item->tanggal_retur)->format('d/m/Y') : '-' }}</td>
                                    <td>{{ $item->pemasok->nama ?? '-' }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td>Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('transaksi.retur.pembelian.show', $item) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <form action="{{ route('transaksi.retur.pembelian.destroy', $item) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus retur ini?')">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="retur-penjualan" role="tabpanel">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped datatable">
                        <thead>
                            <tr>
                                <th>Nomor</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($returPenjualan as $item)
                                <tr>
                                    <td>{{ $item->nomor_retur }}</td>
                                    <td>{{ $item->tanggal_retur ? \Carbon\Carbon::parse($item->tanggal_retur)->format('d/m/Y') : '-' }}</td>
                                    <td>{{ $item->pelanggan->nama ?? 'Umum' }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td>Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('transaksi.retur.penjualan.show', $item) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <form action="{{ route('transaksi.retur.penjualan.destroy', $item) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus retur ini?')">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
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
