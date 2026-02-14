@extends('layouts.auth')

@section('content')
<div class="brand-logo mb-4">
    <img src="{{ asset('assets/images/logo-color.png') }}" alt="Apocare" style="width: auto; height: 80px; object-fit: contain;">
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
