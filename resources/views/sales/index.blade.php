@extends('layouts.app')

@section('title', 'المبيعات')
@section('page-title', 'المبيعات')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
            <i class="fas fa-chart-line ml-2 text-blue-600"></i>المبيعات
        </h2>
        <p class="text-gray-600 dark:text-gray-300">إدارة مبيعات العطور ومتابعة الإيرادات</p>
    </div>
</div>

<!-- نموذج البيع -->
<div class="mb-6">
    <div class="card">
        <div class="card-header bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
            <h5 class="text-lg font-bold">
                <i class="fas fa-shopping-cart ml-2"></i>نموذج البيع
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('sales.store') }}" method="POST" id="saleForm" class="flex gap-4">
                @csrf
                <div class="flex-2" style="flex: 2;">
                    <label for="perfume_search" class="form-label">العطر</label>
                    <div class="search-container relative">
                        <input type="text" 
                               class="form-input @error('perfume_id') error @enderror pr-12" 
                               id="perfume_search" 
                               name="perfume_search"
                               placeholder="ابحث عن العطر..."
                               autocomplete="off">
                        <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="hidden" id="perfume_id" name="perfume_id" value="{{ request('perfume_id') }}" required>
                        <div id="perfume_dropdown" class="search-dropdown hidden"></div>
                        @error('perfume_id')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="flex-1">
                    <label for="size_id" class="form-label">الحجم</label>
                    <select class="form-select @error('size_id') error @enderror" 
                            id="size_id" name="size_id" required disabled>
                        <option value="">اختر العطر أولاً</option>
                    </select>
                    @error('size_id')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="flex-1">
                    <label for="customer_type" class="form-label">نوع العميل</label>
                    <select class="form-select @error('customer_type') error @enderror" 
                            id="customer_type" name="customer_type" required>
                        <option value="">اختر نوع العميل</option>
                        <option value="regular">عادي</option>
                        <option value="vip">VIP</option>
                    </select>
                    @error('customer_type')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="flex-1">
                    <label class="form-label">السعر</label>
                    <div class="flex items-center h-12 px-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg" id="priceDisplay">
                        <i class="fas fa-info-circle ml-2 text-blue-600"></i>
                        <span class="text-blue-800 dark:text-blue-200 text-sm">اختر العطر والحجم ونوع العميل</span>
                    </div>
                </div>

                <div class="flex items-end flex-1">
                    <button type="submit" class="w-full btn-success h-12" id="sellBtn" disabled>
                        <i class="fas fa-shopping-cart ml-2"></i>بيع
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- سجل المبيعات -->
<div class="row">
    <div class="col-12">
        @if($sales->count() > 0)
    <!-- إحصائيات سريعة -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="stats-card bg-gradient-to-r from-cyan-500 to-purple-600">
            <div class="stats-value">{{ number_format($sales->where('customer_type', 'regular')->sum('price'), 0) }}</div>
            <div class="stats-title">عملاء عاديين</div>
            <div class="stats-subtitle">{{ $sales->where('customer_type', 'regular')->count() }} عميل</div>
        </div>
        <div class="stats-card bg-gradient-to-r from-orange-500 to-red-500">
            <div class="stats-value">{{ number_format($sales->where('customer_type', 'vip')->sum('price'), 0) }}</div>
            <div class="stats-title">عملاء VIP</div>
            <div class="stats-subtitle">{{ $sales->where('customer_type', 'vip')->count() }} عميل</div>
        </div>
        <div class="stats-card bg-gradient-to-r from-green-500 to-emerald-500">
            <div class="stats-value">{{ number_format($sales->sum('price'), 0) }}</div>
            <div class="stats-title">إجمالي المبيعات</div>
            <div class="stats-subtitle">{{ $sales->count() }} عملية بيع</div>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-right">#</th>
                            <th class="text-right">العطر</th>
                            <th class="text-right">الحجم</th>
                            <th class="text-right">نوع العميل</th>
                            <th class="text-right">البائع</th>
                            <th class="text-right">السعر</th>
                            <th class="text-right">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            <td class="text-right font-bold">{{ $sale->id }}</td>
                            <td class="text-right">
                                <span class="text-gray-900 dark:text-white font-medium">
                                    {{ $sale->perfume->name }}
                                </span>
                            </td>
                            <td class="text-right">
                                @if($sale->size_id && $sale->size)
                                    @if($sale->is_full_bottle)
                                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                            <i class="fas fa-wine-bottle ml-1"></i>{{ $sale->size->label }} (عبوة كاملة)
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                            {{ $sale->size->label }}
                                        </span>
                                    @endif
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        <i class="fas fa-wine-bottle ml-1"></i>عبوة كاملة
                                    </span>
                                @endif
                            </td>
                            <td class="text-right">
                                <span class="px-2 py-1 text-xs rounded-full {{ $sale->customer_type === 'vip' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                                    <i class="fas {{ $sale->customer_type === 'vip' ? 'fa-crown' : 'fa-user' }} ml-1"></i>
                                    {{ $sale->customer_type === 'vip' ? 'VIP' : 'عادي' }}
                                </span>
                            </td>
                            <td class="text-right">
                                @if($sale->user)
                                    <span class="px-2 py-1 text-xs rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                        {{ $sale->user->name }}
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                        غير محدد
                                    </span>
                                @endif
                            </td>
                            <td class="text-right">
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 font-semibold">
                                    {{ number_format($sale->price, 2) }} دينار
                                </span>
                            </td>
                            <td class="text-right text-gray-500 dark:text-gray-400 text-sm">
                                <span class="sale-date" data-date="{{ $sale->created_at->toISOString() }}">{{ $sale->created_at->format('Y-m-d H:i') }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
        @else
    <div class="text-center py-12">
        <i class="fas fa-shopping-cart text-6xl text-gray-400 mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">لا توجد مبيعات بعد</h3>
        <p class="text-gray-500 dark:text-gray-400 mb-6">ابدأ ببيع عطورك الأولى</p>
    </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const perfumeSearchInput = document.getElementById('perfume_search');
    const perfumeIdInput = document.getElementById('perfume_id');
    const perfumeDropdown = document.getElementById('perfume_dropdown');
    const sizeSelect = document.getElementById('size_id');
    const customerTypeSelect = document.getElementById('customer_type');
    const priceDisplay = document.getElementById('priceDisplay');
    const sellBtn = document.getElementById('sellBtn');
    
    let searchTimeout;
    
    // Create search dropdown styles
    const style = document.createElement('style');
    style.textContent = `
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
        .dark .search-dropdown {
            background: #374151;
            border-color: #4b5563;
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
        .dark .search-result-item {
            border-bottom-color: #4b5563;
        }
        .search-result-item:hover {
            background-color: #f9fafb;
        }
        .dark .search-result-item:hover {
            background-color: #4b5563;
        }
        .search-result-item:last-child {
            border-bottom: none;
        }
    `;
    document.head.appendChild(style);
    
    // Search function
    async function performSearch(query) {
        if (query.length < 2) {
            perfumeDropdown.style.display = 'none';
            return;
        }
        
        try {
            const response = await fetch(`{{ route('perfumes.search') }}?q=${encodeURIComponent(query)}`);
            const data = await response.json();
            displayResults(data.results || []);
        } catch (error) {
            console.error('Search error:', error);
            displayResults([]);
        }
    }
    
    // Display search results
    function displayResults(results) {
        if (results.length === 0) {
            perfumeDropdown.innerHTML = `
                <div class="p-4 text-center">
                    <i class="fas fa-search text-muted mb-2" style="font-size: 2rem;"></i>
                    <div class="text-muted">لا توجد نتائج</div>
                </div>
            `;
        } else {
            perfumeDropdown.innerHTML = results.map(perfume => `
                <div class="search-result-item" data-perfume-id="${perfume.id}" data-perfume-name="${perfume.name}">
                    <div class="font-medium text-gray-900 dark:text-white">${perfume.name}</div>
                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                        ${perfume.category}
                    </span>
                </div>
            `).join('');
            
            // Add click handlers
            perfumeDropdown.querySelectorAll('.search-result-item').forEach((item) => {
                item.addEventListener('click', () => {
                    const perfumeId = item.dataset.perfumeId;
                    const perfumeName = item.dataset.perfumeName;
                    
                    perfumeSearchInput.value = perfumeName;
                    perfumeSearchInput.dataset.selectedName = perfumeName;
                    perfumeIdInput.value = perfumeId;
                    perfumeDropdown.style.display = 'none';
                    
                    perfumeSearchInput.classList.remove('is-invalid');
                    loadAvailableSizes(perfumeId);
                    updatePrice();
                });
                item.addEventListener('mouseenter', () => {
                    item.style.backgroundColor = '#f8f9fa';
                });
                item.addEventListener('mouseleave', () => {
                    item.style.backgroundColor = 'linear-gradient(135deg, #ffffff, #fafbfc)';
                });
            });
        }
        perfumeDropdown.style.display = 'block';
    }
    
    // Input event handler
    perfumeSearchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (perfumeIdInput.value && this.value !== this.dataset.selectedName) {
            perfumeIdInput.value = '';
            delete this.dataset.selectedName;
            clearSizes();
            updatePrice();
        }
        
        if (query.length >= 2) {
            searchTimeout = setTimeout(() => performSearch(query), 300);
        } else {
            perfumeDropdown.style.display = 'none';
            perfumeIdInput.value = '';
            clearSizes();
        }
    });
    
    // Hide dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!perfumeSearchInput.closest('.search-container').contains(e.target)) {
            perfumeDropdown.style.display = 'none';
        }
    });
    
    // Focus handling
    perfumeSearchInput.addEventListener('focus', function() {
        if (this.value.trim().length >= 2) {
            perfumeDropdown.style.display = 'block';
        }
    });

    function updatePrice() {
        const perfumeId = perfumeIdInput.value;
        const sizeId = sizeSelect.value;
        const customerType = customerTypeSelect.value;

        if (perfumeId && sizeId && customerType) {
            fetch(`/api/get-price?perfume_id=${perfumeId}&size_id=${sizeId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        priceDisplay.innerHTML = '<i class="fas fa-exclamation-triangle ml-2 text-red-600"></i><span class="text-red-600">السعر غير محدد لهذا العطر والحجم</span>';
                        sellBtn.disabled = true;
                    } else {
                        const price = customerType === 'vip' ? data.vip : data.regular;
                        priceDisplay.innerHTML = `<i class="fas fa-tag ml-2 text-green-600"></i><span class="text-green-800 dark:text-green-200 font-semibold">${price} دينار</span>`;
                        sellBtn.disabled = false;
                    }
                })
                .catch(error => {
                    priceDisplay.innerHTML = '<i class="fas fa-exclamation-triangle ml-2 text-red-600"></i><span class="text-red-600">خطأ في جلب السعر</span>';
                    sellBtn.disabled = true;
                });
        } else {
            priceDisplay.innerHTML = '<i class="fas fa-info-circle ml-2 text-blue-600"></i><span class="text-blue-800 dark:text-blue-200 text-sm">اختر العطر والحجم ونوع العميل</span>';
            sellBtn.disabled = true;
        }
    }

    // perfumeSelect.addEventListener('change', updatePrice); // Removed as we now use search
    // Load available sizes for selected perfume
    function loadAvailableSizes(perfumeId) {
        if (!perfumeId) {
            clearSizes();
            return;
        }
        
        fetch(`/api/get-available-sizes/${perfumeId}`)
            .then(response => response.json())
            .then(data => {
                sizeSelect.innerHTML = '<option value="">اختر الحجم</option>';
                
                if (data && data.length > 0) {
                    data.forEach(size => {
                        const option = document.createElement('option');
                        option.value = size.id;
                        option.textContent = size.label;
                        sizeSelect.appendChild(option);
                    });
                    
                    sizeSelect.disabled = false;
                } else {
                    sizeSelect.innerHTML = '<option value="">لا توجد أحجام متاحة</option>';
                    sizeSelect.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error loading sizes:', error);
                sizeSelect.innerHTML = '<option value="">خطأ في تحميل الأحجام</option>';
                sizeSelect.disabled = true;
            });
    }
    
    // Clear sizes dropdown
    function clearSizes() {
        sizeSelect.innerHTML = '<option value="">اختر العطر أولاً</option>';
        sizeSelect.disabled = true;
        sizeSelect.value = '';
        customerTypeSelect.value = '';
    }
    
    sizeSelect.addEventListener('change', updatePrice);
    customerTypeSelect.addEventListener('change', updatePrice);
    
    // تحميل العطر من URL إذا كان موجود
    const urlParams = new URLSearchParams(window.location.search);
    const perfumeIdFromUrl = urlParams.get('perfume_id');
    
    if (perfumeIdFromUrl && perfumeIdInput.value === perfumeIdFromUrl) {
        // جلب بيانات العطر وتعبئة الحقل
        fetch(`{{ route('perfumes.search') }}?q=`)
            .then(response => response.json())
            .then(data => {
                // البحث عن العطر بالID
                fetch('/api/get-perfume-by-id/' + perfumeIdFromUrl)
                    .then(response => response.json())
                    .then(perfume => {
                        if (perfume) {
                            perfumeSearchInput.value = perfume.name;
                            perfumeSearchInput.dataset.selectedName = perfume.name;
                            perfumeIdInput.value = perfume.id;
                            loadAvailableSizes(perfume.id);
                        }
                    })
                    .catch(error => console.error('Error loading perfume:', error));
            })
            .catch(error => console.error('Error:', error));
    }
});

// Convert dates to local timezone
document.querySelectorAll('.sale-date').forEach(function(element) {
    const utcDate = element.getAttribute('data-date');
    if (utcDate) {
        const localDate = new Date(utcDate);
        const year = localDate.getFullYear();
        const month = String(localDate.getMonth() + 1).padStart(2, '0');
        const day = String(localDate.getDate()).padStart(2, '0');
        const hours = String(localDate.getHours()).padStart(2, '0');
        const minutes = String(localDate.getMinutes()).padStart(2, '0');
        element.textContent = `${year}-${month}-${day} ${hours}:${minutes}`;
    }
});
</script>

@endsection