@extends('layouts.auth')

@section('content')
<div class="brand-logo mb-4">
    <img src="{{ asset('assets/images/logo-color.png') }}" alt="Apocare" style="width: auto; height: 80px; object-fit: contain;">
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
        <div class="position-relative">
            <input type="password" name="password" class="form-control" placeholder="********" required id="password-input">
            <button type="button" class="btn btn-link position-absolute top-50 end-0 translate-middle-y text-muted p-0 me-2" onclick="togglePassword('password-input', 'toggle-password-icon')" style="text-decoration: none;">
                <i class="fa-solid fa-eye" id="toggle-password-icon"></i>
            </button>
        </div>
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

<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endsection
