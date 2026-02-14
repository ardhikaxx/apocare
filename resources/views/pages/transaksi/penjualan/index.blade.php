@extends('layouts.app')

@section('title', 'Penjualan')

@section('content')
<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
        <h1 class="page-title">Penjualan</h1>
        <p class="text-muted mb-0">Daftar transaksi penjualan & POS.</p>
    </div>
    <a href="{{ route('transaksi.penjualan.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-cash-register me-2"></i>Buka POS
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th>Nomor</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Metode</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penjualan as $item)
                        <tr>
                            <td>{{ $item->nomor_penjualan }}</td>
                            <td>{{ $item->tanggal_penjualan ? \Carbon\Carbon::parse($item->tanggal_penjualan)->format('d/m/Y H:i') : '-' }}</td>
                            <td>{{ $item->pelanggan->nama ?? 'Umum' }}</td>
                            <td>{{ $item->metode_pembayaran ?? '-' }}</td>
                            <td>Rp {{ number_format($item->total_akhir, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ $item->status_pembayaran === 'LUNAS' ? 'success' : ($item->status_pembayaran === 'SEBAGIAN' ? 'warning' : 'secondary') }}">
                                    {{ $item->status_pembayaran }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('transaksi.penjualan.show', $item) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <form action="{{ route('transaksi.penjualan.destroy', $item) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus transaksi ini?')">
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
@endsection
