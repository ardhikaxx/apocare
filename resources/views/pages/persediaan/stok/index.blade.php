@extends('layouts.app')

@section('title', 'Stok Produk')

@section('content')
<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
        <h1 class="page-title">Monitoring Stok</h1>
        <p class="text-muted mb-0">Pantau stok real-time, batch, dan FEFO.</p>
    </div>
</div>

@php
$stats = [
    ['label' => 'Total Item', 'value' => number_format($totalItem), 'icon' => 'fa-solid fa-box'],
    ['label' => 'Stok Minimum', 'value' => number_format($stokMinimum), 'icon' => 'fa-solid fa-arrow-down'],
    ['label' => 'Reserved', 'value' => number_format($totalReserved, 2, ',', '.'), 'icon' => 'fa-solid fa-lock'],
    ['label' => 'Batch Expired', 'value' => number_format($batchExpired), 'icon' => 'fa-solid fa-calendar-xmark']
];
@endphp

@include('pages.shared.stats-row', ['stats' => $stats])

<div class="card mb-3">
    <div class="card-body">
        <form class="row g-3" method="GET">
            <div class="col-md-4">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-select">
                    <option value="">Semua</option>
                    @foreach($kategori as $item)
                        <option value="{{ $item->id }}" @selected(request('kategori') == $item->id)>{{ $item->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Lokasi Rak</label>
                <input type="text" name="lokasi_rak" class="form-control" value="{{ request('lokasi_rak') }}" placeholder="Contoh: R1-A">
            </div>
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button class="btn btn-primary" type="submit"><i class="fa-solid fa-filter me-2"></i>Filter</button>
                <a href="{{ route('persediaan.stok.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">Stok Terkini</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Batch Terdekat</th>
                        <th>Exp</th>
                        <th>Stok</th>
                        <th>Stok Tersedia</th>
                        <th>Status</th>
                        <th>Lokasi</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stok as $item)
                        @php
                            $min = (float) ($item->produk->stok_minimum ?? 0);
                            $status = 'Aman';
                            $badge = 'success';
                            if ($min > 0 && $item->jumlah_tersedia <= $min) {
                                $status = 'Menipis';
                                $badge = 'warning';
                            }
                            if ($min > 0 && $item->jumlah_tersedia <= ($min * 0.5)) {
                                $status = 'Kritis';
                                $badge = 'danger';
                            }
                            $batchTerdekat = $item->produk->batchProduk
                                ->where('jumlah', '>', 0)
                                ->sortBy('tanggal_kadaluarsa')
                                ->first();
                        @endphp
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $item->produk->nama ?? '-' }}</div>
                                <div class="text-muted small">{{ $item->produk->kode ?? '-' }}</div>
                            </td>
                            <td>{{ $item->produk->kategori->nama ?? '-' }}</td>
                            <td>{{ $batchTerdekat->nomor_batch ?? '-' }}</td>
                            <td>{{ $batchTerdekat?->tanggal_kadaluarsa ? \Carbon\Carbon::parse($batchTerdekat->tanggal_kadaluarsa)->format('d/m/Y') : '-' }}</td>
                            <td>{{ number_format($item->jumlah, 2, ',', '.') }}</td>
                            <td>{{ number_format($item->jumlah_tersedia, 2, ',', '.') }}</td>
                            <td><span class="badge bg-{{ $badge }}">{{ $status }}</span></td>
                            <td>{{ $item->produk->lokasi_rak ?? '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('persediaan.stok.show', $item->id) }}" class="btn btn-sm btn-action-view">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
