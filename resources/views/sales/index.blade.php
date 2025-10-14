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
            <form action="{{ route('sales.store') }}" method="POST" id="saleForm">
                @csrf
                <!-- الصف الأول -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
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

                    <div>
                        <label for="size_id" class="form-label">الحجم</label>
                        <select class="form-select @error('size_id') error @enderror" 
                                id="size_id" name="size_id" required disabled>
                            <option value="">اختر العطر أولاً</option>
                        </select>
                        @error('size_id')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="customer_type" class="form-label">نوع العميل</label>
                        <select class="form-select @error('customer_type') error @enderror" 
                                id="customer_type" name="customer_type" required>
                            <option value="regular" selected>عادي</option>
                            <option value="vip">VIP</option>
                        </select>
                        @error('customer_type')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- الصف الثاني -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label">طريقة الدفع</label>
                        <div class="flex gap-2 mt-2 w-full">
                            <label class="flex items-center cursor-pointer flex-1">
                                <input type="radio" name="payment_method" value="cash" class="sr-only" checked>
                                <div class="payment-option active w-full" data-value="cash">
                                    <i class="fas fa-money-bill-wave ml-2"></i>كاش
                                </div>
                            </label>
                            <label class="flex items-center cursor-pointer flex-1">
                                <input type="radio" name="payment_method" value="card" class="sr-only">
                                <div class="payment-option w-full" data-value="card">
                                    <i class="fas fa-credit-card ml-2"></i>بطاقة
                                </div>
                            </label>
                        </div>
                        @error('payment_method')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">السعر</label>
                        <div class="flex items-center h-12 px-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg" id="priceDisplay">
                            <i class="fas fa-info-circle ml-2 text-blue-600"></i>
                            <span class="text-blue-800 dark:text-blue-200 text-sm">اختر العطر والحجم ونوع العميل</span>
                        </div>
                    </div>

                    <div>
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="w-full btn-success h-12" id="sellBtn" disabled>
                            <i class="fas fa-shopping-cart ml-2"></i>بيع
                        </button>
                    </div>
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
                            <th class="text-right">طريقة الدفع</th>
                            <th class="text-right">البائع</th>
                            <th class="text-right">السعر</th>
                            <th class="text-right">التاريخ</th>
                            <th class="text-right">الإجراءات</th>
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
                                <span class="px-2 py-1 text-xs rounded-full {{ $sale->payment_method === 'card' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }}">
                                    <i class="fas {{ $sale->payment_method === 'card' ? 'fa-credit-card' : 'fa-money-bill-wave' }} ml-1"></i>
                                    {{ $sale->payment_method === 'card' ? 'بطاقة' : 'كاش' }}
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
                            <td class="text-right">
                                @if(auth()->user()->isSuperAdmin() || $sale->user_id === auth()->id())
                                    <div class="flex gap-2 justify-center">
                                        <button onclick="editSale({{ $sale->id }}, {{ $sale->perfume_id }}, {{ $sale->size_id }}, '{{ $sale->customer_type }}', '{{ $sale->payment_method }}', {{ $sale->is_full_bottle ? 'true' : 'false' }})"
                                                class="w-8 h-8 bg-yellow-500 hover:bg-yellow-600 text-white rounded-full flex items-center justify-center transition-colors">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>
                                        <button onclick="deleteSale({{ $sale->id }})"
                                                class="w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition-colors">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-xs">غير متاح</span>
                                @endif
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

<!-- Edit Sale Modal -->
<div id="editSaleModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">تعديل البيع</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editSaleForm">
                <input type="hidden" id="editSaleId">
                <input type="hidden" id="editPerfumeId">
                <div class="mb-4">
                    <label class="form-label">الحجم</label>
                    <select id="editSizeId" class="form-select">
                        <option value="">جاري تحميل الأحجام...</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="form-label">نوع العميل</label>
                    <select id="editCustomerType" class="form-select">
                        <option value="regular">عادي</option>
                        <option value="vip">VIP</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="form-label">السعر</label>
                    <div class="flex items-center h-12 px-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg" id="editPriceDisplay">
                        <i class="fas fa-info-circle ml-2 text-blue-600"></i>
                        <span class="text-blue-800 dark:text-blue-200 text-sm">اختر الحجم ونوع العميل</span>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label">طريقة الدفع</label>
                    <div class="flex gap-2 mt-2">
                        <label class="flex items-center cursor-pointer flex-1">
                            <input type="radio" name="edit_payment_method" value="cash" class="sr-only">
                            <div class="payment-option w-full" data-value="cash">
                                <i class="fas fa-money-bill-wave ml-2"></i>كاش
                            </div>
                        </label>
                        <label class="flex items-center cursor-pointer flex-1">
                            <input type="radio" name="edit_payment_method" value="card" class="sr-only">
                            <div class="payment-option w-full" data-value="card">
                                <i class="fas fa-credit-card ml-2"></i>بطاقة
                            </div>
                        </label>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="button" onclick="updateSale()" class="btn-success flex-1">
                        <i class="fas fa-save ml-2"></i>حفظ
                    </button>
                    <button type="button" onclick="closeEditModal()" class="btn-secondary flex-1">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Sale Modal -->
<div id="deleteSaleModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full animate-scale-in">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center ml-4">
                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">تأكيد الحذف</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">هذا الإجراء لا يمكن التراجع عنه</p>
                </div>
            </div>
            
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-4">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-yellow-600 dark:text-yellow-400 ml-3 mt-0.5"></i>
                    <div class="text-sm">
                        <strong class="text-yellow-800 dark:text-yellow-200">تحذير:</strong>
                        <span class="text-yellow-700 dark:text-yellow-300">سيتم حذف عملية البيع رقم "<span id="deleteSaleNumber" class="font-bold"></span>" نهائياً.</span>
                    </div>
                </div>
            </div>
            
            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteSaleModal()" class="flex-1 btn-secondary">
                    <i class="fas fa-times ml-2"></i>إلغاء
                </button>
                <button type="button" onclick="confirmDeleteSale()" class="flex-1 btn-danger">
                    <i class="fas fa-trash ml-2"></i>حذف نهائياً
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const perfumeSearchInput = document.getElementById('perfume_search');
    const perfumeIdInput = document.getElementById('perfume_id');
    const perfumeDropdown = document.getElementById('perfume_dropdown');
    const sizeSelect = document.getElementById('size_id');
    const customerTypeSelect = document.getElementById('customer_type');
    const paymentMethodInputs = document.querySelectorAll('input[name="payment_method"]');
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
        .payment-option {
            padding: 8px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            background: white;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
        }
        .dark .payment-option {
            background: #374151;
            border-color: #4b5563;
            color: #d1d5db;
        }
        .payment-option:hover {
            border-color: #3b82f6;
            background: #eff6ff;
        }
        .dark .payment-option:hover {
            border-color: #3b82f6;
            background: #1e3a8a;
        }
        .payment-option.active {
            border-color: #3b82f6;
            background: #3b82f6;
            color: white;
        }
        .dark .payment-option.active {
            border-color: #3b82f6;
            background: #3b82f6;
            color: white;
        }
    `;
    document.head.appendChild(style);
    
    // Payment method handling
    function updatePaymentOptions() {
        const paymentOptions = document.querySelectorAll('.payment-option');
        const checkedInput = document.querySelector('input[name="payment_method"]:checked');
        
        paymentOptions.forEach(option => {
            option.classList.remove('active');
            if (option.dataset.value === checkedInput?.value) {
                option.classList.add('active');
            }
        });
    }
    
    // Add click handlers for payment options
    document.querySelectorAll('.payment-option').forEach(option => {
        option.addEventListener('click', function() {
            const value = this.dataset.value;
            const input = document.querySelector(`input[name="payment_method"][value="${value}"]`);
            input.checked = true;
            updatePaymentOptions();
            updatePrice();
        });
    });
    
    // Initialize payment options
    updatePaymentOptions();
    
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
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked')?.value;

        if (perfumeId && sizeId && customerType && paymentMethod) {
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
            priceDisplay.innerHTML = '<i class="fas fa-info-circle ml-2 text-blue-600"></i><span class="text-blue-800 dark:text-blue-200 text-sm">اختر العطر والحجم ونوع العميل وطريقة الدفع</span>';
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
        customerTypeSelect.value = 'regular';
        document.querySelector('input[name="payment_method"][value="cash"]').checked = true;
        updatePaymentOptions();
    }
    
    sizeSelect.addEventListener('change', updatePrice);
    customerTypeSelect.addEventListener('change', updatePrice);
    paymentMethodInputs.forEach(input => {
        input.addEventListener('change', updatePrice);
    });
    
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

// Edit Sale Functions
function editSale(id, perfumeId, sizeId, customerType, paymentMethod, isFullBottle) {
    document.getElementById('editSaleId').value = id;
    document.getElementById('editPerfumeId').value = perfumeId;
    document.getElementById('editCustomerType').value = customerType;
    
    // Set payment method
    document.querySelector(`input[name="edit_payment_method"][value="${paymentMethod}"]`).checked = true;
    updateEditPaymentOptions();
    
    // Load available sizes
    loadEditSizes(perfumeId, sizeId, isFullBottle);
    
    document.getElementById('editSaleModal').classList.remove('hidden');
}

function loadEditSizes(perfumeId, currentSizeId, isFullBottle) {
    const sizeSelect = document.getElementById('editSizeId');
    sizeSelect.innerHTML = '<option value="">جاري تحميل الأحجام...</option>';
    
    fetch(`/api/get-available-sizes/${perfumeId}`)
        .then(response => response.json())
        .then(data => {
            sizeSelect.innerHTML = '';
            
            if (data && data.length > 0) {
                data.forEach(size => {
                    const option = document.createElement('option');
                    option.value = size.id;
                    option.textContent = size.label;
                    
                    // تحديد الحجم الحالي
                    if ((isFullBottle && size.id === `bottle_${currentSizeId}`) || 
                        (!isFullBottle && size.id == currentSizeId)) {
                        option.selected = true;
                    }
                    
                    sizeSelect.appendChild(option);
                });
            } else {
                sizeSelect.innerHTML = '<option value="">لا توجد أحجام متاحة</option>';
            }
        })
        .catch(error => {
            console.error('Error loading sizes:', error);
            sizeSelect.innerHTML = '<option value="">خطأ في تحميل الأحجام</option>';
        })
        .finally(() => {
            // تحديث السعر بعد تحميل الأحجام
            setTimeout(updateEditPrice, 100);
        });
    
    // إضافة event listeners لتحديث السعر
    document.getElementById('editSizeId').addEventListener('change', updateEditPrice);
    document.getElementById('editCustomerType').addEventListener('change', updateEditPrice);
}

function updateEditPrice() {
    const perfumeId = document.getElementById('editPerfumeId').value;
    const sizeId = document.getElementById('editSizeId').value;
    const customerType = document.getElementById('editCustomerType').value;
    const priceDisplay = document.getElementById('editPriceDisplay');

    if (perfumeId && sizeId && customerType) {
        fetch(`/api/get-price?perfume_id=${perfumeId}&size_id=${sizeId}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    priceDisplay.innerHTML = '<i class="fas fa-exclamation-triangle ml-2 text-red-600"></i><span class="text-red-600">السعر غير محدد لهذا الحجم</span>';
                } else {
                    const price = customerType === 'vip' ? data.vip : data.regular;
                    priceDisplay.innerHTML = `<i class="fas fa-tag ml-2 text-green-600"></i><span class="text-green-800 dark:text-green-200 font-semibold">${price} دينار</span>`;
                }
            })
            .catch(error => {
                priceDisplay.innerHTML = '<i class="fas fa-exclamation-triangle ml-2 text-red-600"></i><span class="text-red-600">خطأ في جلب السعر</span>';
            });
    } else {
        priceDisplay.innerHTML = '<i class="fas fa-info-circle ml-2 text-blue-600"></i><span class="text-blue-800 dark:text-blue-200 text-sm">اختر الحجم ونوع العميل</span>';
    }
}

function closeEditModal() {
    document.getElementById('editSaleModal').classList.add('hidden');
}

function updateEditPaymentOptions() {
    const paymentOptions = document.querySelectorAll('#editSaleModal .payment-option');
    const checkedInput = document.querySelector('input[name="edit_payment_method"]:checked');
    
    paymentOptions.forEach(option => {
        option.classList.remove('active');
        if (option.dataset.value === checkedInput?.value) {
            option.classList.add('active');
        }
    });
}

// Add click handlers for edit modal payment options
document.querySelectorAll('#editSaleModal .payment-option').forEach(option => {
    option.addEventListener('click', function() {
        const value = this.dataset.value;
        const input = document.querySelector(`input[name="edit_payment_method"][value="${value}"]`);
        input.checked = true;
        updateEditPaymentOptions();
    });
});

function updateSale() {
    const id = document.getElementById('editSaleId').value;
    const sizeId = document.getElementById('editSizeId').value;
    const customerType = document.getElementById('editCustomerType').value;
    const paymentMethod = document.querySelector('input[name="edit_payment_method"]:checked').value;
    
    if (!sizeId) {
        alert('يرجى اختيار الحجم');
        return;
    }
    
    fetch(`/sales/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            size_id: sizeId,
            customer_type: customerType,
            payment_method: paymentMethod
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'خطأ في تحديث البيع');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('خطأ في تحديث البيع');
    });
}

let saleToDelete = null;

function deleteSale(id) {
    saleToDelete = id;
    document.getElementById('deleteSaleNumber').textContent = id;
    document.getElementById('deleteSaleModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteSaleModal() {
    document.getElementById('deleteSaleModal').classList.add('hidden');
    document.body.style.overflow = '';
    saleToDelete = null;
}

function confirmDeleteSale() {
    if (saleToDelete) {
        fetch(`/sales/${saleToDelete}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('خطأ في حذف البيع');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('خطأ في حذف البيع');
        })
        .finally(() => {
            closeDeleteSaleModal();
        });
    }
}

// إغلاق modal عند النقر خارجه
document.getElementById('deleteSaleModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteSaleModal();
});

// إغلاق modal بمفتاح Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeDeleteSaleModal();
});
</script>

@endsection