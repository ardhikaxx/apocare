<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Autentikasi - Sistem Informasi Apotek')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}" type="image/x-icon">
    @stack('styles')
</head>
<body class="auth-body">
    <div class="auth-shell">
        <div class="auth-card">
            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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
    </script>
    @stack('scripts')
</body>
</html>
