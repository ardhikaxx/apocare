@extends('layouts.app')

@section('title', 'Detail Stok Opname')

@section('content')
<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
        <h1 class="page-title">Detail Stok Opname</h1>
        <p class="text-muted mb-0">{{ $opname->nomor_opname }}</p>
    </div>
    <a href="{{ route('persediaan.opname.index') }}" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h6 class="text-uppercase text-muted">Informasi</h6>
                <div class="mb-2"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($opname->tanggal_opname)->format('d/m/Y') }}</div>
                <div class="mb-2"><strong>Status:</strong> {{ $opname->status }}</div>
                <div class="mb-2"><strong>Total Item:</strong> {{ $opname->total_item_dihitung }}</div>
                <div class="mb-2"><strong>Selisih:</strong> {{ $opname->total_item_selisih }}</div>
                <div class="mb-0"><strong>Catatan:</strong> {{ $opname->catatan ?? '-' }}</div>
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
                                <th>Hitung</th>
                                <th>Selisih</th>
                                <th>Status</th>
                                <th>Total Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($opname->detail as $detail)
                                <tr>
                                    <td>{{ $detail->produk->nama ?? '-' }}</td>
                                    <td>{{ number_format($detail->jumlah_sistem, 2, ',', '.') }}</td>
                                    <td>{{ number_format($detail->jumlah_hitung, 2, ',', '.') }}</td>
                                    <td>{{ number_format($detail->selisih, 2, ',', '.') }}</td>
                                    <td>{{ $detail->status }}</td>
                                    <td>Rp {{ number_format($detail->total_nilai_selisih, 0, ',', '.') }}</td>
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
