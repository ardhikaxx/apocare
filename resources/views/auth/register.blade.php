@extends('layouts.auth')

@section('content')
<div class="brand-logo mb-4">
    <img src="{{ asset('assets/images/logo-color.png') }}" alt="Apocare" style="width: auto; height: 80px; object-fit: contain;">
</div>
<form method="POST" action="{{ route('register') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label">Nama Lengkap</label>
        <input type="text" name="nama" class="form-control" placeholder="Nama lengkap" value="{{ old('nama') }}" required>
        @error('nama')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" placeholder="nama@apotek.com" value="{{ old('email') }}" required>
        @error('email')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" placeholder="username" value="{{ old('username') }}" required>
        @error('username')
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
    <div class="mb-4">
        <label class="form-label">Konfirmasi Password</label>
        <div class="position-relative">
            <input type="password" name="password_confirmation" class="form-control" placeholder="********" required id="password-confirm-input">
            <button type="button" class="btn btn-link position-absolute top-50 end-0 translate-middle-y text-muted p-0 me-2" onclick="togglePassword('password-confirm-input', 'toggle-password-confirm-icon')" style="text-decoration: none;">
                <i class="fa-solid fa-eye" id="toggle-password-confirm-icon"></i>
            </button>
        </div>
    </div>
    <button type="submit" class="btn btn-primary w-100">Daftar</button>
</form>
<div class="text-center mt-3">
    <small class="text-muted">Sudah punya akun?</small>
    <a href="/login" class="text-decoration-none">Masuk</a>
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
