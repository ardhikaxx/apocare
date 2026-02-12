@extends('layouts.app')

@section('title', 'Dokter')

@section('breadcrumb')
<li class="breadcrumb-item active">Dokter</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1 class="page-title">Dokter</h1>
    <a href="{{ route('dokter.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Tambah Dokter
    </a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Spesialisasi</th>
                    <th>SIP</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dokter as $item)
                <tr>
                    <td>{{ $item->kode }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->spesialisasi }}</td>
                    <td>{{ $item->nomor_sip }}</td>
                    <td>
                        <span class="badge bg-{{ $item->status_aktif ? 'success' : 'danger' }}">
                            {{ $item->status_aktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('dokter.edit', $item->id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $dokter->links() }}
    </div>
</div>
@endsection
