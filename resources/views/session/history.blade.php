@extends('layouts.app')

@section('title', 'Riwayat Login User')

@section('content')
<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
        <h1 class="page-title">Riwayat Login User</h1>
        <p class="text-muted mb-0">Riwayat login seluruh user.</p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>IP Address</th>
                        <th>Waktu Login Terakhir</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($histories as $user)
                    <tr>
                        <td>
                            <strong>{{ $user->nama }}</strong><br>
                            <small class="text-muted">{{ $user->email }}</small>
                        </td>
                        <td>{{ $user->role->nama ?? '-' }}</td>
                        <td>{{ $user->last_login_ip ?? '-' }}</td>
                        <td>{{ $user->last_login_at?->translatedFormat('d F Y, H:i:s') ?? '-' }}</td>
                        <td>
                            @if($user->is_online && $user->last_login_at > now()->subMinutes(30))
                                <span class="badge bg-success">Online</span>
                            @else
                                <span class="badge bg-secondary">Offline</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
