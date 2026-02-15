<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cetak Dokumen')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            font-size: 12px;
            color: #111;
        }

        .print-header {
            border-bottom: 1px solid #ddd;
            margin-bottom: 16px;
            padding-bottom: 8px;
        }

        .kop {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 16px;
            margin-bottom: 8px;
        }

        .kop-logo img {
            height: 65px;
            width: auto;
        }

        .kop-title {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .kop-subtitle {
            font-size: 12px;
            color: #444;
        }

        .kop-line {
            border-bottom: 1px solid #222;
            margin-bottom: 16px;
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="container my-4" style="max-width: 100%; padding: 0;">
        @include('print.partials.kop')
        @yield('content')
    </div>
</body>

</html>
