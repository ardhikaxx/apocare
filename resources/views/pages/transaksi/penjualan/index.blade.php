@extends('layouts.app')

@section('title', 'Penjualan')

@section('breadcrumb')
<li class="breadcrumb-item active">Penjualan</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1 class="page-title">Penjualan</h1>
    <a href="{{ route('penjualan.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Penjualan Baru
    </a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nomor</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penjualan as $item)
                <tr>
                    <td>{{ $item->nomor_penjualan }}</td>
                    <td>{{ $item->tanggal_penjualan }}</td>
                    <td>{{ $item->pelanggan ? $item->pelanggan->nama : 'Umum' }}</td>
                    <td>Rp {{ number_format($item->total_akhir, 0, ',', '.') }}</td>
                    <td>{{ $item->status_pembayaran }}</td>
                    <td>
                        <a href="{{ route('penjualan.show', $item->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        <form action="{{ route('penjualan.destroy', $item->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $penjualan->links() }}
    </div>
</div>
@endsection
