<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Informasi Apotek')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}" type="image/x-icon">
    <style>
        .page-title, h1 {
            font-family: 'Google Sans Flex', sans-serif;
            font-size: 3.5rem;
        }
    </style>
    @stack('styles')
</head>

<body>
    @include('partials.loading')

    <div class="app-shell">
        <div class="sidebar-overlay" data-sidebar-close></div>
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
        
        document.querySelectorAll('[data-sidebar-close]').forEach((btn) => {
            btn.addEventListener('click', () => {
                body.classList.remove('sidebar-open');
            });
        });
        
        const SwalToast = Swal.mixin({
            toast: true,
            icon: 'success',
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        const SwalConfirm = (options = {}) => {
            const defaults = {
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin melanjutkan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0e6f64',
                cancelButtonColor: '#6f7f7f',
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal',
                reverseButtons: false,
                customClass: {
                    confirmButton: 'swal2-confirm-custom',
                    cancelButton: 'swal2-cancel-custom'
                }
            };
            return Swal.fire({ ...defaults, ...options });
        };

        window.showToast = (icon, title, message = '') => {
            let toastOptions = {
                title: title || 'Aksi berhasil',
                icon: icon || 'success',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#ffffff',
                color: '#0f2a2e',
                iconColor: icon === 'success' ? '#0e6f64' : icon === 'error' ? '#d94848' : icon === 'warning' ? '#e58e26' : '#2b8ac6'
            };
            
            if (message) {
                toastOptions.text = message;
            }
            
            SwalToast.fire(toastOptions);
        };

        window.showAlert = (type, title, message = '') => {
            const icons = {
                success: { icon: 'success', color: '#0e6f64' },
                error: { icon: 'error', color: '#d94848' },
                warning: { icon: 'warning', color: '#e58e26' },
                info: { icon: 'info', color: '#2b8ac6' },
                question: { icon: 'question', color: '#0e6f64' }
            };
            
            const config = icons[type] || icons.success;
            
            return Swal.fire({
                title: title,
                text: message,
                icon: config.icon,
                confirmButtonColor: '#0e6f64',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'swal2-popup-custom',
                    title: 'swal2-title-custom',
                    confirmButton: 'swal2-confirm-btn'
                },
                backdrop: `
                    rgba(15, 42, 46, 0.6)
                `,
                allowOutsideClick: true,
                allowEscapeKey: true
            });
        };

        window.confirmDelete = (formId, itemName = 'Data ini') => {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: `<strong>${itemName}</strong> akan dihapus secara permanen dan tidak dapat dikembalikan!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d94848',
                cancelButtonColor: '#6f7f7f',
                confirmButtonText: '<i class="fas fa-trash-alt"></i> Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'swal2-delete-btn',
                    cancelButton: 'swal2-cancel-btn'
                },
                backdrop: `
                    rgba(15, 42, 46, 0.6)
                `
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
            return false;
        };

        window.confirmAction = (title, text, icon = 'warning', confirmText = 'Ya, Lanjutkan', cancelText = 'Batal', confirmColor = '#0e6f64') => {
            return Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#6f7f7f',
                confirmButtonText: confirmText,
                cancelButtonText: cancelText,
                reverseButtons: true,
                customClass: {
                    confirmButton: 'swal2-confirm-btn',
                    cancelButton: 'swal2-cancel-btn'
                },
                backdrop: `
                    rgba(15, 42, 46, 0.6)
                `
            });
        };

        window.showLoading = (title = 'Mohon tunggu...') => {
            return Swal.fire({
                title: title,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                },
                backdrop: `
                    rgba(15, 42, 46, 0.6)
                `
            });
        };

        window.closeLoading = () => {
            Swal.close();
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
                        searchPlaceholder: 'Ketik untuk mencari...',
                        info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                        paginate: {
                            previous: '<i class="fa-solid fa-chevron-left"></i>',
                            next: '<i class="fa-solid fa-chevron-right"></i>',
                            first: '<i class="fa-solid fa-angles-left"></i>',
                            last: '<i class="fa-solid fa-angles-right"></i>'
                        }
                    },
                    dom: '<"d-flex justify-content-between align-items-center mb-3"<"dt-search"f><"dt-info">><"table-responsive"t><"d-flex justify-content-center align-items-center mt-4"p>'
                });
                table.dataset.dtInitialized = 'true';
            });
        };

        document.addEventListener('DOMContentLoaded', () => {
            window.initDataTables();
        });
    </script>
    @if (session('success'))
        <script>
            window.showToast('success', @json(session('success')));
        </script>
    @endif
    @if ($errors->any())
        <script>
            window.showToast('error', 'Terjadi kesalahan. Periksa input Anda.');
        </script>
    @endif
    @stack('scripts')
</body>

</html>
