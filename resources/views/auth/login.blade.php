@extends('layouts.auth')

@section('content')
<div class="brand">
    <div class="brand-icon"><i class="fa-solid fa-pills"></i></div>
    <div>
        <h4 class="mb-0">APOCARE</h4>
        <small class="text-muted">Masuk ke sistem apotek</small>
    </div>
</div>
<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label">Email / Username</label>
        <input type="text" name="login" class="form-control" placeholder="nama@apotek.com atau username" value="{{ old('login') }}" required>
        @error('login')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="********" required>
        @error('password')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="remember">
            <label class="form-check-label" for="remember">Ingat saya</label>
        </div>
        <a href="/forgot-password" class="text-decoration-none">Lupa password?</a>
    </div>
    <button type="submit" class="btn btn-primary w-100">Masuk</button>
</form>
<div class="text-center mt-3">
    <small class="text-muted">Belum punya akun?</small>
    <a href="/register" class="text-decoration-none">Daftar</a>
</div>
@endsection
