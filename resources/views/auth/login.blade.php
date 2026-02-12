@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="login-card">
    <div class="text-center mb-4">
        <div class="login-logo"><i class="fas fa-pills"></i></div>
        <h4 class="mb-1">APOCARE</h4>
        <small class="text-muted">Sistem Informasi Apotek</small>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Email / Username</label>
            <input type="text" name="login" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100 btn-login">
            <i class="fas fa-sign-in-alt me-2"></i>Login
        </button>
    </form>

    @if($errors->any())
    <div class="alert alert-danger mt-3">
        {{ $errors->first() }}
    </div>
    @endif
</div>
@endsection
