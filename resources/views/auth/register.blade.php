@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="login-card">
    <div class="text-center mb-4">
        <div class="login-logo"><i class="fas fa-pills"></i></div>
        <h4 class="mb-1">APOCARE</h4>
        <small class="text-muted">Sistem Informasi Apotek</small>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100 btn-login">
            <i class="fas fa-user-plus me-2"></i>Register
        </button>
    </form>

    <div class="text-center mt-3">
        <a href="{{ route('login') }}" class="text-decoration-none">Sudah punya akun? Login</a>
    </div>
</div>
@endsection
