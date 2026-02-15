@extends('layouts.app')

@section('title', 'Pembelian')

@section('content')
<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
        <h1 class="page-title">Pembelian</h1>
        <p class="text-muted mb-0">Daftar PO dan penerimaan barang.</p>
    </div>
    <a href="{{ route('transaksi.pembelian.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus me-2"></i>Tambah PO
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
                        <th>Pemasok</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pembelian as $item)
                        <tr>
                            <td>{{ $item->nomor_pembelian }}</td>
                            <td>{{ $item->tanggal_pembelian ? \Carbon\Carbon::parse($item->tanggal_pembelian)->format('d/m/Y') : '-' }}</td>
                            <td>{{ $item->pemasok->nama ?? '-' }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $item->status }}</span>
                            </td>
                            <td>Rp {{ number_format($item->total_akhir, 0, ',', '.') }}</td>
                            <td class="text-end">
                                <a href="{{ route('transaksi.pembelian.show', $item) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <form id="delete-form-{{ $item->id }}" action="{{ route('transaksi.pembelian.destroy', $item) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete('delete-form-{{ $item->id }}')">
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
