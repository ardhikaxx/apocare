@extends('layouts.app')

@section('title', 'Resep Baru')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('resep.index') }}">Resep</a></li>
<li class="breadcrumb-item active">Baru</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Resep Baru</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('resep.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Pasien</label>
                    <select name="pelanggan_id" class="form-select" required>
                        <option value="">Pilih Pasien</option>
                        @foreach($pelanggan as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Dokter</label>
                    <select name="dokter_id" class="form-select" required>
                        <option value="">Pilih Dokter</option>
                        @foreach($dokter as $d)
                        <option value="{{ $d->id }}">{{ $d->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">Diagnosa</label>
                    <textarea name="diagnosa" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan</button>
            <a href="{{ route('resep.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
