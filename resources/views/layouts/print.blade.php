<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Apocare</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body { padding: 20px; background: #fff; }
        .print-header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
        .print-footer { margin-top: 30px; border-top: 1px solid #ccc; padding-top: 20px; }
    </style>
    @stack('styles')
</head>
<body>
    @yield('content')
    <script>window.print();</script>
    @stack('scripts')
</body>
</html>
