<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Sistem Informasi Apotek')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        :root { --primary-color: #198754; --secondary-color: #6c757d; }
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background: linear-gradient(180deg, #2c3e50 0%, #1a252f 100%); width: 250px; position: fixed; left: 0; top: 0; z-index: 100; transition: all 0.3s; }
        .sidebar .nav-link { color: rgba(255,255,255,0.8); padding: 12px 20px; border-radius: 8px; margin: 2px 10px; transition: all 0.3s; }
        .sidebar .nav-link:hover { background: rgba(255,255,255,0.1); color: #fff; }
        .sidebar .nav-link.active { background: var(--primary-color); color: #fff; }
        .sidebar .nav-link i { width: 25px; text-align: center; margin-right: 10px; }
        .main-content { margin-left: 250px; padding: 20px; transition: all 0.3s; }
        .navbar { background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 15px 25px; margin-bottom: 20px; border-radius: 10px; }
        .card { border: none; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); transition: transform 0.3s; }
        .card:hover { transform: translateY(-3px); }
        .card-header { background: #fff; border-bottom: 1px solid #eee; font-weight: 600; padding: 15px 20px; border-radius: 12px 12px 0 0 !important; }
        .card-body { padding: 20px; }
        .stat-card { border-left: 4px solid var(--primary-color); }
        .btn-primary { background: var(--primary-color); border-color: var(--primary-color); }
        .btn-primary:hover { background: #157347; border-color: #157347; }
        .table thead th { background: #f8f9fa; border-bottom: 2px solid #dee2e6; font-weight: 600; color: #495057; }
        .page-header { margin-bottom: 20px; }
        .page-header h1 { font-size: 1.75rem; font-weight: 600; color: #2c3e50; }
        .breadcrumb { background: transparent; padding: 0; margin-bottom: 0; }
        .dropdown-menu { border: none; box-shadow: 0 5px 20px rgba(0,0,0,0.15); border-radius: 10px; }
        .dropdown-item { padding: 10px 20px; }
        @media (max-width: 768px) { .sidebar { margin-left: -250px; } .sidebar.active { margin-left: 0; } .main-content { margin-left: 0; } }
    </style>
    @stack('styles')
</head>
<body>
    <div class="d-flex">
        @include('partials.sidebar')
        <div class="main-content flex-grow-1">
            @include('partials.navbar')
            @yield('content')
        </div>
    </div>
    @include('partials.footer')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
    <script>
        $(document).ready(function() { $('.sidebar-toggle').click(function() { $('.sidebar').toggleClass('active'); }); });
    </script>
    @stack('scripts')
</body>
</html>
