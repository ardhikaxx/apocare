@extends('layouts.app')

@section('title', 'Resep Dokter')

@section('content')
<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
        <h1 class="page-title">Resep Dokter</h1>
        <p class="text-muted mb-0">Kelola resep dan dispensing.</p>
    </div>
    <a href="{{ route('resep.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus me-2"></i>Tambah Resep
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form class="row g-3" method="GET">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    @foreach(['PENDING','SEBAGIAN','SELESAI','BATAL'] as $status)
                        <option value="{{ $status }}" @selected(request('status') == $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" class="form-control" value="{{ request('tanggal_selesai') }}">
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button class="btn btn-primary" type="submit"><i class="fa-solid fa-filter me-2"></i>Filter</button>
                <a href="{{ route('resep.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th>Nomor</th>
                        <th>Tanggal</th>
                        <th>Pasien</th>
                        <th>Dokter</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($resep as $item)
                        <tr>
                            <td>{{ $item->nomor_resep }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_resep)->format('d/m/Y') }}</td>
                            <td>{{ $item->pelanggan->nama ?? '-' }}</td>
                            <td>{{ $item->dokter->nama ?? '-' }}</td>
                            <td>{{ $item->status }}</td>
                            <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                            <td class="text-end">
                                <a href="{{ route('resep.show', $item) }}" class="btn btn-sm btn-action-view">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                @if($item->status !== 'SELESAI')
                                    <a href="{{ route('resep.penjualan.create', $item) }}" class="btn btn-sm btn-action-process">
                                        <i class="fa-solid fa-cash-register"></i>
                                    </a>
                                @endif
                                <form id="delete-form-{{ $item->id }}" action="{{ route('resep.destroy', $item) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-action-delete" onclick="confirmDelete('delete-form-{{ $item->id }}')">
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
