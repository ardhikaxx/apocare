<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login') - Apocare</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { background: #fff; border-radius: 15px; box-shadow: 0 15px 35px rgba(0,0,0,0.2); padding: 40px; width: 100%; max-width: 400px; }
        .login-logo { font-size: 3rem; color: #198754; margin-bottom: 10px; }
        .form-control { border-radius: 8px; padding: 12px 15px; }
        .btn-login { border-radius: 8px; padding: 12px; font-weight: 600; }
    </style>
</head>
<body>
    @yield('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
