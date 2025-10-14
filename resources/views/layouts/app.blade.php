<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'حمزة عطور - نظام إدارة العطور')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
    
    <style>
    .search-container {
        position: relative;
    }
    .search-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        z-index: 50;
        max-height: 300px;
        overflow-y: auto;
        margin-top: 4px;
    }

    .search-dropdown::-webkit-scrollbar {
        width: 6px;
    }
    .search-dropdown::-webkit-scrollbar-track {
        background: #f3f4f6;
        border-radius: 3px;
    }
    .search-dropdown::-webkit-scrollbar-thumb {
        background: #9ca3af;
        border-radius: 3px;
    }
    .search-dropdown::-webkit-scrollbar-thumb:hover {
        background: #6b7280;
    }
    .search-result-item {
        padding: 12px 16px;
        border-bottom: 1px solid #f3f4f6;
        cursor: pointer;
        transition: background-color 0.15s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .search-result-item:hover {
        background-color: #f9fafb;
    }

    .search-result-item:last-child {
        border-bottom: none;
    }
    </style>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const globalSearch = document.getElementById('globalSearch');
        const globalDropdown = document.getElementById('globalSearchDropdown');
        let searchTimeout;
        
        if (globalSearch && globalDropdown) {
            globalSearch.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const query = this.value.trim();
                
                if (query.length >= 2) {
                    searchTimeout = setTimeout(() => performGlobalSearch(query), 300);
                } else {
                    globalDropdown.classList.add('hidden');
                }
            });
            
            globalSearch.addEventListener('focus', function() {
                if (this.value.trim().length >= 2) {
                    globalDropdown.classList.remove('hidden');
                }
            });
            
            document.addEventListener('click', function(e) {
                if (!globalSearch.closest('.search-container').contains(e.target)) {
                    globalDropdown.classList.add('hidden');
                }
            });
        }
        
        async function performGlobalSearch(query) {
            try {
                const response = await fetch(`/perfumes/search?q=${encodeURIComponent(query)}`);
                const data = await response.json();
                displayGlobalResults(data.results || []);
            } catch (error) {
                console.error('Search error:', error);
                displayGlobalResults([]);
            }
        }
        
        function displayGlobalResults(results) {
            if (results.length === 0) {
                globalDropdown.innerHTML = `
                    <div class="p-4 text-center">
                        <i class="fas fa-search text-gray-400 mb-2" style="font-size: 2rem;"></i>
                        <div class="text-gray-500">لا توجد نتائج</div>
                    </div>
                `;
            } else {
                globalDropdown.innerHTML = results.map(perfume => `
                    <div class="search-result-item" onclick="window.location.href='/sales?perfume_id=${perfume.id}'">
                        <div class="font-medium text-gray-900">${perfume.name}</div>
                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                            ${perfume.category}
                        </span>
                    </div>
                `).join('');
            }
            globalDropdown.classList.remove('hidden');
        }
    });
    </script>
</head>
<body class="bg-gray-50 font-tajawal">

    @auth
        <!-- Sidebar -->
        <div class="fixed inset-y-0 right-0 z-40 w-64 bg-white shadow-xl transform transition-transform duration-300 ease-in-out lg:translate-x-0 -translate-x-full" id="sidebar">
            <div class="flex items-center justify-center h-16 bg-gradient-to-r from-blue-500 to-blue-600">
                <h1 class="text-white text-xl font-bold">حمزة عطور</h1>
            </div>
            
            <nav class="mt-8 px-4">
                <div class="space-y-2">
                    @if(auth()->user()->role === 'super-admin')
                        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-100 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-700' : '' }}">
                            <i class="fas fa-chart-line mr-3"></i>
                            <span>لوحة التحكم</span>
                        </a>
                        
                        <a href="{{ route('users.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-100 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('users.*') ? 'bg-blue-100 text-blue-700' : '' }}">
                            <i class="fas fa-users mr-3"></i>
                            <span>المستخدمين</span>
                        </a>
                    @endif
                    
                    @if(in_array(auth()->user()->role, ['super-admin', 'admin']))
                        <a href="{{ route('perfumes.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-100 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('perfumes.*') ? 'bg-blue-100 text-blue-700' : '' }}">
                            <i class="fas fa-spray-can mr-3"></i>
                            <span>العطور</span>
                        </a>
                        
                        <a href="{{ route('categories.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-100 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('categories.*') ? 'bg-blue-100 text-blue-700' : '' }}">
                            <i class="fas fa-tags mr-3"></i>
                            <span>التصنيفات</span>
                        </a>
                        
                        <a href="{{ route('sizes.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-100 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('sizes.*') ? 'bg-blue-100 text-blue-700' : '' }}">
                            <i class="fas fa-ruler mr-3"></i>
                            <span>الأحجام</span>
                        </a>
                        
                        <a href="{{ route('prices.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-100 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('prices.*') ? 'bg-blue-100 text-blue-700' : '' }}">
                            <i class="fas fa-dollar-sign mr-3"></i>
                            <span>الأسعار</span>
                        </a>
                    @endif
                    
                    <a href="{{ route('sales.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-100 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('sales.*') ? 'bg-blue-100 text-blue-700' : '' }}">
                        <i class="fas fa-shopping-cart mr-3"></i>
                        <span>المبيعات</span>
                    </a>
                </div>
            </nav>
            


            <!-- User Info -->
            <div class="px-4 py-3 border-t border-gray-200" >
                <div class="mb-3 p-2 border shadow-sm rounded-lg border-blue-200 border-2 bg-blue-50">
                    <p class="text-sm font-medium text-gray-900 ">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 ">
                        @switch(auth()->user()->role)
                            @case('super-admin')
                                مدير عام
                                @break
                            @case('admin')
                                مدير
                                @break
                            @case('saler')
                                بائع
                                @break
                        @endswitch
                    </p>
                </div>
                
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>

        <!-- Mobile sidebar overlay -->
        <div id="sidebarOverlay" class="fixed inset-0 z-30 bg-black bg-opacity-50 hidden lg:hidden transition-opacity duration-300"></div>

        <!-- Main Content -->
        <div class="lg:mr-64 min-h-screen transition-all duration-300">
            <!-- Top Bar -->
            <div class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center px-4 lg:px-6 py-4">
                    <div class="flex items-center">
                        <button id="sidebarToggle" class="lg:hidden p-2 rounded-lg text-gray-500 hover:text-gray-700 transition-colors">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h2 class="text-lg lg:text-xl font-semibold text-gray-900 mr-2 lg:mr-4">@yield('page-title', 'الصفحة الرئيسية')</h2>
                    </div>
                    
                    <!-- Search Bar - Center -->
                    <div class="flex-1 flex justify-center px-4">
                        <div class="search-container relative w-full max-w-md">
                            <input type="text" 
                                   id="globalSearch" 
                                   placeholder="بحث عن عطر..."
                                   class="form-input pr-12 w-full" 
                                   autocomplete="off">
                            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <div id="globalSearchDropdown" class="search-dropdown hidden"></div>
                        </div>
                    </div>
                    
                    <div class="text-xs lg:text-sm text-gray-500" id="currentDate">
                        <!-- سيتم تحديثه بـ JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="p-4 lg:p-6 bg-gray-50 min-h-screen">
                @if(session('success'))
                    <div class="mb-4 lg:mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative animate-slide-up" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle ml-2"></i>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="absolute top-2 left-2 text-green-700 hover:text-green-900">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 lg:mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative animate-slide-up" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle ml-2"></i>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="absolute top-2 left-2 text-red-700 hover:text-red-900">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                <div class="animate-fade-in">
                    @yield('content')
                </div>
            </main>
        </div>
    @else
        <!-- Guest Layout -->
        <main>
            @yield('content')
        </main>
    @endauth

    <!-- JavaScript -->
    <script>
        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Update date from client device
            const dateElement = document.getElementById('currentDate');
            if (dateElement) {
                const now = new Date();
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                dateElement.textContent = `${year}/${month}/${day}`;
            }
        });

        // Mobile sidebar toggle
        function initSidebar() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (sidebarToggle && sidebar && overlay) {
                sidebarToggle.addEventListener('click', function() {
                    const isHidden = sidebar.classList.contains('-translate-x-full');
                    
                    if (isHidden) {
                        sidebar.classList.remove('-translate-x-full');
                        overlay.classList.remove('hidden');
                        document.body.style.overflow = 'hidden'; // منع التمرير
                    } else {
                        sidebar.classList.add('-translate-x-full');
                        overlay.classList.add('hidden');
                        document.body.style.overflow = ''; // إعادة التمرير
                    }
                });

                // Close sidebar when clicking overlay
                overlay.addEventListener('click', function() {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                    document.body.style.overflow = '';
                });
                
                // Close sidebar on escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && !sidebar.classList.contains('-translate-x-full')) {
                        sidebar.classList.add('-translate-x-full');
                        overlay.classList.add('hidden');
                        document.body.style.overflow = '';
                    }
                });
                
                // Handle window resize
                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 1024) { // lg breakpoint
                        sidebar.classList.remove('-translate-x-full');
                        overlay.classList.add('hidden');
                        document.body.style.overflow = '';
                    } else {
                        sidebar.classList.add('-translate-x-full');
                    }
                });
            }
        }

        // Initialize sidebar when DOM is loaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initSidebar);
        } else {
            initSidebar();
        }

        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(function(alert) {
                if (!alert.querySelector('button')) { // فقط إذا لم يكن هناك زر إغلاق
                    alert.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    setTimeout(function() {
                        if (alert.parentNode) {
                            alert.remove();
                        }
                    }, 500);
                }
            });
        }, 5000);
        
        // Add loading states to forms
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.tagName === 'FORM') {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin ml-2"></i>جاري المعالجة...';
                }
            }
        });
    </script>

    @stack('scripts')
</body>
</html>