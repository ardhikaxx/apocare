@extends('layouts.auth')

@section('content')
<div class="brand">
    <div class="brand-icon"><i class="fa-solid fa-key"></i></div>
    <div>
        <h4 class="mb-0">Lupa Password</h4>
        <small class="text-muted">Masukkan email untuk reset</small>
    </div>
</div>
<form>
    <div class="mb-4">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" placeholder="nama@apotek.com">
    </div>
    <button type="button" class="btn btn-primary w-100">Kirim Tautan Reset</button>
</form>
<div class="text-center mt-3">
    <a href="/login" class="text-decoration-none">Kembali ke login</a>
</div>
@endsection
