@extends('layouts.app')

@section('title', 'Backup Database')

@section('content')
<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
        <h1 class="page-title">Backup Database</h1>
        <p class="text-muted mb-0">Kelola backup database secara manual atau otomatis.</p>
    </div>
    <div>
        <form action="{{ route('backup.create') }}" method="POST" class="d-inline" id="backupForm">
            @csrf
            <button type="submit" class="btn btn-success" id="backupBtn">
                <i class="fa-solid fa-database me-2"></i>Buat Backup Sekarang
            </button>
        </form>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@push('scripts')
<script>
function handleBackupSubmit(form) {
    var btn = form.querySelector('button');
    var originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Membuat Backup...';
    
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'text/html'
        }
    })
    .then(response => {
        window.location.reload();
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: 'Terjadi kesalahan saat membuat backup!'
        });
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}

document.getElementById('backupForm').addEventListener('submit', function(e) {
    e.preventDefault();
    handleBackupSubmit(this);
});

document.querySelectorAll('.backup-form').forEach(function(form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        handleBackupSubmit(this);
    });
});

function confirmDelete(form) {
    event.preventDefault();
    Swal.fire({
        title: 'Hapus Backup?',
        text: "File backup yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
    return false;
}
</script>
@endpush

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Daftar Backup</h5>
    </div>
    <div class="card-body">
        @if(count($backups) > 0)
        <div class="table-responsive">
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th>Filename</th>
                        <th>Tanggal Dibuat</th>
                        <th>Ukuran</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($backups as $backup)
                    <tr>
                        <td>
                            <i class="fa-solid fa-database me-2 text-primary"></i>
                            {{ $backup['filename'] }}
                        </td>
                        <td>{{ \Carbon\Carbon::parse($backup['created'])->translatedFormat('d F Y, H:i:s') }}</td>
                        <td>{{ $backup['size'] }}</td>
                        <td class="text-end">
                            <a href="{{ route('backup.download', $backup['filename']) }}" class="btn btn-sm btn-primary">
                                <i class="fa-solid fa-download"></i>
                            </a>
                            <form action="{{ route('backup.destroy', $backup['filename']) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirmDelete(this.form)">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fa-solid fa-database fa-3x text-muted mb-3"></i>
            <p class="text-muted">Belum ada backup database.</p>
            <form action="{{ route('backup.create') }}" method="POST" class="d-inline backup-form">
                @csrf
                <button type="submit" class="btn btn-primary backup-btn">Buat Backup Pertama</button>
            </form>
        </div>
        @endif
    </div>
</div>

<div class="card mt-3">
    <div class="card-header">
        <h5 class="card-title mb-0">Informasi</h5>
    </div>
    <div class="card-body">
        <ul class="mb-0">
            <li>Backup otomatis dilakukan setiap hari pukul 01:00 pagi.</li>
            <li>Backup disimpan di <code>storage/app/backups</code>.</li>
            <li>Sistem akan menyimpan maksimal 30 backup terakhir.</li>
            <li>Untuk menjalankan scheduler, pastikan ada cron job yang menjalankan <code>php artisan schedule:run</code>.</li>
        </ul>
    </div>
</div>
@endsection
