<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'حمزة عطور')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <style>
        body { font-family: 'Tajawal', sans-serif; background-color: #f5f5f5; }
        .page-container { background-color: #f5f5f5; min-height: 100vh; padding: 0; margin: 0; }
        .main-content { background: #f5f5f5; border-radius: 0;  padding: 24px; margin: 0; }
        .offcanvas-header { background: linear-gradient(135deg, #ff6b35, #f7931e); color: white; }
        .nav-link { color: #495057; transition: all 0.3s; border-radius: 8px; margin: 0; padding: 8px 12px; }
        .nav-link:hover { color: #ff6b35; background-color: transparent; }
        .nav-link.active { background: transparent; color: #ff6b35; }
        .nav-link i { width: 20px; }
        .table-modern { border-radius: 15px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .table-modern thead { background: linear-gradient(135deg, #667eea, #764ba2); color: white; }
        .table-modern tbody tr:hover { background-color: #f8f9fa; }
        .btn-modern { border-radius: 25px; padding: 8px 20px; font-weight: 500; transition: all 0.3s; }
        .btn-modern:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        .card-modern { border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border: 1px solid #f0f0f0; transition: all 0.3s ease; }
        .card-modern:hover { transform: translateY(-2px); box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 4rem; color: #6c757d; margin-bottom: 20px; }
        .stats-card { background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 15px; padding: 25px; text-align: center; }
        .stats-number { font-size: 2.5rem; font-weight: 700; margin-bottom: 10px; }
        .stats-label { font-size: 1rem; opacity: 0.9; }
    </style>
</head>
<body>
    <div class="page-container">
        <!-- Header like dashboard (full width, no extra spacing) -->
        <header class="w-100" style="border-bottom: 2px solid #e9ecef;">
            <div class="d-flex align-items-center justify-content-between" style=" background: transparent; padding: 20px 16px;">
                <!-- Mobile burger -->
                <button class="btn btn-outline-primary d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <!-- Center nav links (desktop) - moved to start position -->
                <nav class="nav-links d-none d-md-flex align-items-center" style="gap: 24px;">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">لوحة التحكم</a>
                    <a class="nav-link {{ request()->routeIs('perfumes.*') ? 'active' : '' }}" href="{{ route('perfumes.index') }}">العطور</a>
                    <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">التصنيفات</a>
                    <a class="nav-link {{ request()->routeIs('sizes.*') ? 'active' : '' }}" href="{{ route('sizes.index') }}">الأحجام</a>
                    <a class="nav-link {{ request()->routeIs('prices.*') ? 'active' : '' }}" href="{{ route('prices.index') }}">الأسعار</a>
                    <a class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}" href="{{ route('sales.index') }}">المبيعات</a>
                </nav>
                <!-- Right actions (desktop) -->
                <div class="search-section d-none d-md-flex">
                    <div class="search-box me-3" style="width: 400px;">
                        <input type="text" class="search-input" placeholder="ابحث عن العطور..." style="width: 100%; border-radius: 25px; padding: 12px 45px 12px 20px; border: 2px solid #e9ecef; transition: all 0.3s;">
                        <i class="fas fa-search search-icon" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                    </div>
                    <div class="header-icons">
                        <button class="icon-btn"><i class="fas fa-bell"></i></button>
                        <button class="icon-btn"><i class="fas fa-cog"></i></button>
                        <div class="user-avatar">س</div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Sidebar Offcanvas - mobile only -->
        <div class="offcanvas offcanvas-end d-md-none" tabindex="-1" id="sidebar">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title d-flex align-items-center">
                    <div class="logo-icon me-2">T</div>
                    TWISTY
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body p-0">
                <nav class="nav flex-column">
                    <a class="nav-link py-3 px-4 border-bottom {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt me-3"></i>لوحة التحكم
                    </a>
                    <a class="nav-link py-3 px-4 border-bottom {{ request()->routeIs('perfumes.*') ? 'active' : '' }}" href="{{ route('perfumes.index') }}">
                        <i class="fas fa-spray-can me-3"></i>العطور
                    </a>
                    <a class="nav-link py-3 px-4 border-bottom {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                        <i class="fas fa-tags me-3"></i>التصنيفات
                    </a>
                    <a class="nav-link py-3 px-4 border-bottom {{ request()->routeIs('sizes.*') ? 'active' : '' }}" href="{{ route('sizes.index') }}">
                        <i class="fas fa-ruler me-3"></i>الأحجام
                    </a>
                    <a class="nav-link py-3 px-4 border-bottom {{ request()->routeIs('prices.*') ? 'active' : '' }}" href="{{ route('prices.index') }}">
                        <i class="fas fa-dollar-sign me-3"></i>الأسعار
                    </a>
                    <a class="nav-link py-3 px-4 {{ request()->routeIs('sales.*') ? 'active' : '' }}" href="{{ route('sales.index') }}">
                        <i class="fas fa-chart-line me-3"></i>المبيعات
                    </a>
                </nav>
            </div>
        </div>

        <div class="main-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" style="border-radius: 10px;">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" style="border-radius: 10px;">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('.search-input');
        if (!searchInput) return;
        
        let searchTimeout;
        let searchResults = null;
        
        // Create search results dropdown
        const searchDropdown = document.createElement('div');
        searchDropdown.className = 'search-dropdown';
        searchDropdown.style.cssText = `
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            z-index: 1000;
            max-height: 300px;
            overflow-y: auto;
            overflow-x: hidden;
            display: none;
            margin-top: 8px;
            backdrop-filter: blur(10px);
        `;
        
        // Add custom scrollbar styles
        const style = document.createElement('style');
        style.textContent = `
            .search-dropdown::-webkit-scrollbar {
                width: 6px;
            }
            .search-dropdown::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 3px;
            }
            .search-dropdown::-webkit-scrollbar-thumb {
                background: linear-gradient(135deg, #667eea, #764ba2);
                border-radius: 3px;
            }
            .search-dropdown::-webkit-scrollbar-thumb:hover {
                background: linear-gradient(135deg, #5a6fe0, #6b3fb1);
            }
        `;
        document.head.appendChild(style);
        
        // Position search container relatively
        const searchBox = document.querySelector('.search-box');
        if (searchBox) {
            searchBox.style.position = 'relative';
            searchBox.appendChild(searchDropdown);
        }
        
        // Search function
        async function performSearch(query) {
            if (query.length < 2) {
                searchDropdown.style.display = 'none';
                return;
            }
            
            try {
                const response = await fetch(`{{ route('perfumes.search') }}?q=${encodeURIComponent(query)}`);
                const data = await response.json();
                displayResults(data.results);
            } catch (error) {
                console.error('Search error:', error);
            }
        }
        
        // Display search results
        function displayResults(results) {
            if (results.length === 0) {
                searchDropdown.innerHTML = `
                    <div class="p-4 text-center">
                        <i class="fas fa-search text-muted mb-2" style="font-size: 2rem;"></i>
                        <div class="text-muted">لا توجد نتائج</div>
                    </div>
                `;
            } else {
                searchDropdown.innerHTML = results.map(perfume => `
                    <div class="search-result-item" style="padding: 18px 20px; border-bottom: 1px solid #f0f0f0; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: space-between; background: linear-gradient(135deg, #ffffff, #fafbfc);">
                        <div class="fw-bold text-dark" style="font-size: 1rem; color: #2c3e50;">${perfume.name}</div>
                        <span class="badge bg-info px-4 py-2" style="border-radius: 20px; font-size: 0.8rem; font-weight: 600; box-shadow: 0 2px 8px rgba(23, 162, 184, 0.3);">
                            <i class="fas fa-tag me-2"></i>${perfume.category}
                        </span>
                    </div>
                `).join('');
                
                // Add click handlers
                searchDropdown.querySelectorAll('.search-result-item').forEach((item, index) => {
                    item.addEventListener('click', () => {
                        window.location.href = results[index].url;
                    });
                    item.addEventListener('mouseenter', () => {
                        item.style.backgroundColor = '#f8f9fa';
                    });
                    item.addEventListener('mouseleave', () => {
                        item.style.backgroundColor = 'linear-gradient(135deg, #ffffff, #fafbfc)';
                    });
                });
            }
            searchDropdown.style.display = 'block';
        }
        
        // Input event handler
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length >= 2) {
                searchTimeout = setTimeout(() => performSearch(query), 300);
            } else {
                searchDropdown.style.display = 'none';
            }
        });
        
        // Hide dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchBox.contains(e.target)) {
                searchDropdown.style.display = 'none';
            }
        });
        
        // Focus handling
        searchInput.addEventListener('focus', function() {
            if (this.value.trim().length >= 2) {
                searchDropdown.style.display = 'block';
            }
        });
    });
    </script>
</body>
</html>