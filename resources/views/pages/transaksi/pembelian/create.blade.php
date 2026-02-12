@extends('layouts.app')

@section('title', 'Pembelian Baru')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('pembelian.index') }}">Pembelian</a></li>
<li class="breadcrumb-item active">Baru</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Pembelian Baru</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('pembelian.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Pemasok</label>
                <select name="pemasok_id" class="form-select" required>
                    <option value="">Pilih Pemasok</option>
                    @foreach($pemasok as $p)
                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan</button>
            <a href="{{ route('pembelian.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
