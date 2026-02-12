@extends('layouts.app')

@section('title', 'Resep')

@section('breadcrumb')
<li class="breadcrumb-item active">Resep</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1 class="page-title">Resep Dokter</h1>
    <a href="{{ route('resep.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Resep Baru
    </a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nomor</th>
                    <th>Tanggal</th>
                    <th>Pasien</th>
                    <th>Dokter</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resep as $item)
                <tr>
                    <td>{{ $item->nomor_resep }}</td>
                    <td>{{ $item->tanggal_resep }}</td>
                    <td>{{ $item->pelanggan ? $item->pelanggan->nama : '-' }}</td>
                    <td>{{ $item->dokter ? $item->dokter->nama : '-' }}</td>
                    <td>
                        <span class="badge bg-{{ $item->status == 'SELESAI' ? 'success' : 'warning' }}">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('resep.show', $item->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $resep->links() }}
    </div>
</div>
@endsection
