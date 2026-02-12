@extends('layouts.app')

@section('title', 'Penjualan Baru')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('penjualan.index') }}">Penjualan</a></li>
<li class="breadcrumb-item active">Baru</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Penjualan Baru</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('penjualan.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Pelanggan</label>
                    <select name="pelanggan_id" class="form-select">
                        <option value="">Umum</option>
                        @foreach($pelanggan as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Metode Pembayaran</label>
                    <select name="metode_pembayaran" class="form-select">
                        <option value="TUNAI">Tunai</option>
                        <option value="DEBIT">Debit</option>
                        <option value="TRANSFER">Transfer</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan</button>
            <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
