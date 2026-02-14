@extends('layouts.auth')

@section('content')
<div class="brand">
    <div class="brand-icon">
        <img src="{{ asset('images/logo-color.png') }}" alt="Apocare" style="width: 40px; height: 40px; object-fit: contain;">
    </div>
    <div>
        <h4 class="mb-0">APOCARE</h4>
        <small class="text-muted">Create New Password</small>
    </div>
</div>
<form>
    <div class="mb-3">
        <label class="form-label">Password Baru</label>
        <input type="password" class="form-control" placeholder="********">
    </div>
    <div class="mb-4">
        <label class="form-label">Konfirmasi Password</label>
        <input type="password" class="form-control" placeholder="********">
    </div>
    <button type="button" class="btn btn-primary w-100">Simpan Password</button>
</form>
<div class="text-center mt-3">
    <a href="/login" class="text-decoration-none">Kembali ke login</a>
</div>
@endsection
