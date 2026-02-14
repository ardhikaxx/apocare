@extends('layouts.app')

@section('content')
@include('partials.breadcrumb', ['breadcrumbs' => [
    ['label' => 'Profil']
]])

@include('pages.shared.page-header', [
    'title' => 'Profil Saya',
    'subtitle' => 'Kelola informasi akun Anda.'
])

<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar avatar-xl mx-auto mb-3">
                    <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-1">
                        {{ strtoupper(substr($pengguna->nama, 0, 1)) }}
                    </span>
                </div>
                <h5 class="mb-1">{{ $pengguna->nama }}</h5>
                <p class="text-muted mb-2">{{ $pengguna->role->nama ?? '-' }}</p>
                <span class="badge bg-success-subtle text-success">Aktif</span>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="card-title mb-0">Informasi Akun</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Username</span>
                    <span class="fw-medium">{{ $pengguna->username }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Email</span>
                    <span class="fw-medium">{{ $pengguna->email }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Telepon</span>
                    <span class="fw-medium">{{ $pengguna->telepon ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Alamat</span>
                    <span class="fw-medium text-end" style="max-width: 150px;">{{ $pengguna->alamat ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <span class="text-muted">Terakhir Login</span>
                    <span class="fw-medium">{{ $pengguna->updated_at->format('d M Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profil-tab" type="button">
                            <i class="fa-solid fa-user me-1"></i> Informasi Profil
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#password-tab" type="button">
                            <i class="fa-solid fa-lock me-1"></i> Ubah Password
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="profil-tab">
                        <form method="POST" action="{{ route('profil.update') }}">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $pengguna->nama) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Username <span class="text-danger">*</span></label>
                                    <input type="text" name="username" class="form-control" value="{{ old('username', $pengguna->username) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $pengguna->email) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nomor Telepon</label>
                                    <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $pengguna->telepon) }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Alamat</label>
                                    <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $pengguna->alamat) }}</textarea>
                                </div>
                            </div>
                            <div class="mt-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-save me-1"></i>Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="password-tab">
                        <form method="POST" action="{{ route('profil.update') }}">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Password Lama <span class="text-danger">*</span></label>
                                    <input type="password" name="password_lama" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Password Baru <span class="text-danger">*</span></label>
                                    <input type="password" name="password_baru" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password_baru_confirmation" class="form-control" required>
                                </div>
                            </div>
                            <div class="alert alert-warning mt-3">
                                <i class="fa-solid fa-circle-info me-1"></i> 
                                Password harus minimal 6 karakter. Pastikan password baru berbeda dari password lama.
                            </div>
                            <div class="mt-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-key me-1"></i>Ubah Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
