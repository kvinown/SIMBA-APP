<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    @include('app_layouts.sidebar')

    <!-- Page Content -->
    <div id="page-content-wrapper" class="p-4 w-100">
        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
            <button class="btn btn-primary" id="sidebarToggle">☰</button>
        </nav>
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('sidebarToggle').addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('d-none');
    });
</script>
</body>
</html>
