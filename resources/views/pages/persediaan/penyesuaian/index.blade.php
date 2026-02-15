@extends('layouts.app')

@section('title', 'Penyesuaian Stok')

@section('content')
<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
        <h1 class="page-title">Penyesuaian Stok</h1>
        <p class="text-muted mb-0">Kelola penambahan, pengurangan, rusak, dan kadaluarsa.</p>
    </div>
    <a href="{{ route('persediaan.penyesuaian.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus me-2"></i>Tambah Penyesuaian
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form class="row g-3" method="GET">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    @foreach(['DRAFT','DISETUJUI','DITOLAK'] as $status)
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
                <a href="{{ route('persediaan.penyesuaian.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                        <th>Jenis</th>
                        <th>Total Item</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penyesuaian as $item)
                        <tr>
                            <td>{{ $item->nomor_penyesuaian }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_penyesuaian)->format('d/m/Y') }}</td>
                            <td>{{ $item->jenis_penyesuaian }}</td>
                            <td>{{ $item->total_item }}</td>
                            <td>
                                <span class="badge bg-{{ $item->status === 'DISETUJUI' ? 'success' : ($item->status === 'DITOLAK' ? 'danger' : 'secondary') }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('persediaan.penyesuaian.show', $item) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <form id="delete-form-{{ $item->id }}" action="{{ route('persediaan.penyesuaian.destroy', $item) }}" method="POST" class="d-inline">
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
