<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Informasi Apotek')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}" type="image/x-icon">
    @stack('styles')
</head>
<body>
    @include('partials.loading')

    <div class="app-shell">
        @include('partials.sidebar')
        <div class="app-main">
            @include('partials.navbar')
            <div class="app-content">
                @yield('content')
            </div>
            @include('partials.footer')
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>
    <script>
        const body = document.body;
        document.querySelectorAll('[data-sidebar-toggle]').forEach((btn) => {
            btn.addEventListener('click', () => {
                body.classList.toggle('sidebar-open');
            });
        });

        window.showToast = (icon, title) => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: icon || 'success',
                title: title || 'Aksi berhasil',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        };

        window.initDataTables = () => {
            if (typeof DataTable === 'undefined') return;
            document.querySelectorAll('.datatable').forEach((table) => {
                if (table.dataset.dtInitialized) return;
                new DataTable(table, {
                    pageLength: 10,
                    lengthChange: false,
                    language: {
                        search: 'Cari:',
                        info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                        paginate: { previous: 'Sebelumnya', next: 'Berikutnya' }
                    }
                });
                table.dataset.dtInitialized = 'true';
            });
        };

        document.addEventListener('DOMContentLoaded', () => {
            window.initDataTables();
        });
    </script>
    @if(session('success'))
        <script>
            window.showToast('success', @json(session('success')));
        </script>
    @endif
    @if($errors->any())
        <script>
            window.showToast('error', 'Terjadi kesalahan. Periksa input Anda.');
        </script>
    @endif
    @stack('scripts')
</body>
</html>


