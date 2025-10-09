<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'حمزة عطور')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Tajawal', sans-serif; }
        .offcanvas-header { background: linear-gradient(135deg, #0d6efd, #6610f2); color: white; }
        .nav-link { color: #495057; transition: all 0.3s; }
        .nav-link:hover { color: #0d6efd; background-color: #f8f9fa; }
        .nav-link i { width: 20px; }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <nav class="navbar navbar-light bg-light border-bottom">
        <div class="container-fluid">
            <button class="btn btn-outline-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar">
                <i class="fas fa-bars"></i>
            </button>
            <span class="navbar-brand mb-0 h1">
                <i class="fas fa-spray-can me-2 text-primary"></i>حمزة عطور
            </span>
        </div>
    </nav>

    <!-- Sidebar Offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="sidebar">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">
                <i class="fas fa-spray-can me-2"></i>حمزة عطور
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            <nav class="nav flex-column">
                <a class="nav-link py-3 px-4 border-bottom" href="{{ route('perfumes.index') }}">
                    <i class="fas fa-spray-can me-3"></i>العطور
                </a>
                <a class="nav-link py-3 px-4 border-bottom" href="{{ route('categories.index') }}">
                    <i class="fas fa-tags me-3"></i>التصنيفات
                </a>
                <a class="nav-link py-3 px-4 border-bottom" href="{{ route('sizes.index') }}">
                    <i class="fas fa-ruler me-3"></i>الأحجام
                </a>
                <a class="nav-link py-3 px-4 border-bottom" href="{{ route('prices.index') }}">
                    <i class="fas fa-dollar-sign me-3"></i>الأسعار
                </a>
                <a class="nav-link py-3 px-4" href="{{ route('sales.index') }}">
                    <i class="fas fa-chart-line me-3"></i>المبيعات
                </a>
            </nav>
        </div>
    </div>

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>