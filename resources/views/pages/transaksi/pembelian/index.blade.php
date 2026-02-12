@extends('layouts.app')

@section('title', 'Pembelian')

@section('breadcrumb')
<li class="breadcrumb-item active">Pembelian</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1 class="page-title">Pembelian</h1>
    <a href="{{ route('pembelian.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Pembelian Baru
    </a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nomor</th>
                    <th>Tanggal</th>
                    <th>Pemasok</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pembelian as $item)
                <tr>
                    <td>{{ $item->nomor_pembelian }}</td>
                    <td>{{ $item->tanggal_pembelian }}</td>
                    <td>{{ $item->pemasok ? $item->pemasok->nama : '-' }}</td>
                    <td>{{ $item->status }}</td>
                    <td>Rp {{ number_format($item->total_akhir, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('pembelian.show', $item->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $pembelian->links() }}
    </div>
</div>
@endsection
