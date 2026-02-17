@extends('layouts.app')

@section('title', 'Kelola Sesi User')

@section('content')
<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
        <h1 class="page-title">Kelola Sesi User</h1>
        <p class="text-muted mb-0">Monitor dan kelola sesi user yang sedang aktif.</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary" onclick="refreshSessions()">
            <i class="fa-solid fa-sync me-2"></i>Refresh
        </button>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="mb-0">{{ $sessions->count() }}</h3>
                <p class="text-muted mb-0">User Online</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="mb-0">{{ $sessions->where('role.nama', 'Admin')->count() }}</h3>
                <p class="text-muted mb-0">Admin</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="mb-0">{{ $sessions->where('role.nama', 'Apoteker')->count() }}</h3>
                <p class="text-muted mb-0">Apoteker</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Sesi Aktif</h5>
    </div>
    <div class="card-body">
        @if($sessions->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Login dari IP</th>
                        <th>Waktu Login</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sessions as $session)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle px-3 py-2 rounded-circle text-white me-2" style="background-color: var(--brand-primary);">
                                    {{ strtoupper(substr($session->nama, 0, 1)) }}
                                </div>
                                <div>
                                    <strong>{{ $session->nama }}</strong><br>
                                    <small class="text-muted">{{ $session->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $roleColors = [
                                    'Admin' => 'danger',
                                    'Apoteker' => 'success',
                                    'Kasir' => 'info',
                                    'Gudang' => 'secondary'
                                ];
                            @endphp
                            <span class="badge bg-{{ $roleColors[$session->role->nama] ?? 'secondary' }} text-white">
                                {{ $session->role->nama ?? '-' }}
                            </span>
                        </td>
                        <td>{{ $session->last_login_ip ?? '-' }}</td>
                        <td>{{ $session->last_login_at?->translatedFormat('d F Y, H:i:s') }}</td>
                        <td>
                            <span class="badge bg-success">
                                <i class="fa-solid fa-circle me-1"></i> Online
                            </span>
                        </td>
                        <td class="text-end">
                            @if($session->id !== Auth::id())
                            <button type="button" class="btn btn-sm btn-danger" onclick="confirmForceLogout({{ $session->id }}, '{{ $session->nama }}')">
                                <i class="fa-solid fa-sign-out-alt"></i>
                            </button>
                            @else
                            <span class="badge bg-primary">Anda</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fa-solid fa-user-slash fa-3x text-muted mb-3"></i>
            <p class="text-muted">Tidak ada user yang sedang online.</p>
        </div>
        @endif
    </div>
</div>

<div class="card mt-3">
    <div class="card-header">
        <h5 class="card-title mb-0">Riwayat Login</h5>
    </div>
    <div class="card-body">
        <a href="{{ route('session.history') }}" class="btn btn-outline-primary">
            <i class="fa-solid fa-history me-2"></i>Lihat Semua Riwayat
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
function refreshSessions() {
    fetch('{{ route("session.refresh") }}')
        .then(response => response.text())
        .then(html => {
            location.reload();
        });
}

setInterval(refreshSessions, 30000);

function confirmForceLogout(userId, userName) {
    event.preventDefault();
    Swal.fire({
        title: 'Force Logout?',
        text: 'User ' + userName + ' akan dipaksa logout dari sistem!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Logout!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('{{ url("session") }}/' + userId + '/force-logout', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'User telah di-logout'
                }).then(() => {
                    location.reload();
                });
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan'
                });
            });
        }
    });
}
</script>
@endpush
