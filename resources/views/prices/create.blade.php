@extends('layouts.app')

@section('title', 'إضافة أسعار جديدة')
@section('page-title', 'إضافة أسعار جديدة')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
            <i class="fas fa-dollar-sign ml-2 text-blue-600"></i>إضافة أسعار جديدة
        </h2>
        <p class="text-gray-600 dark:text-gray-300">إضافة أسعار للعطور غير المصنفة</p>
    </div>
    <a href="{{ route('prices.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-right ml-2"></i>العودة للقائمة
    </a>
</div>

<div class="max-w-4xl mx-auto">
    <div class="card">
        <div class="card-header bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
            <h5 class="text-lg font-bold">
                <i class="fas fa-dollar-sign ml-2"></i>بيانات الأسعار
            </h5>
        </div>
            <div class="card-body p-4">
                <form action="{{ route('prices.store') }}" method="POST">
                    @csrf
                    


                    <div class="mb-6">
                        <label for="perfume_search" class="form-label">العطر (العطور غير المصنفة فقط)</label>
                        <div class="search-container relative">
                            <input type="text" 
                                   class="form-input @error('perfume_id') error @enderror pr-12" 
                                   id="perfume_search" 
                                   name="perfume_search"
                                   placeholder="ابحث عن العطر غير المصنف..."
                                   value="{{ old('perfume_search') }}"
                                   autocomplete="off">
                            <i class="fas fa-search absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="hidden" id="perfume_id" name="perfume_id" value="{{ old('perfume_id') }}" required>
                            <div id="perfume_dropdown" class="search-dropdown hidden"></div>
                            @error('perfume_id')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">يمكن إضافة أسعار للعطور غير المصنفة فقط</p>
                    </div>

                    <h5 class="text-lg font-bold text-gray-900 dark:text-white mb-4">معلومات العبوة الكاملة</h5>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label for="bottle_size" class="form-label">حجم العبوة الكاملة</label>
                            <select class="form-select @error('bottle_size') error @enderror" id="bottle_size" name="bottle_size">
                                <option value="">اختر حجم العبوة</option>
                                @foreach($sizes as $size)
                                    <option value="{{ $size->label }}" {{ old('bottle_size') == $size->label ? 'selected' : '' }}>
                                        {{ $size->label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bottle_size')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="bottle_price_regular" class="form-label">سعر العبوة - عادي (دينار)</label>
                            <input type="number" step="0.01" class="form-input @error('bottle_price_regular') error @enderror" 
                                   id="bottle_price_regular" name="bottle_price_regular" 
                                   value="{{ old('bottle_price_regular') }}" placeholder="0.00">
                            @error('bottle_price_regular')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="bottle_price_vip" class="form-label">سعر العبوة - VIP (دينار)</label>
                            <input type="number" step="0.01" class="form-input @error('bottle_price_vip') error @enderror" 
                                   id="bottle_price_vip" name="bottle_price_vip" 
                                   value="{{ old('bottle_price_vip') }}" placeholder="0.00">
                            @error('bottle_price_vip')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">الأسعار حسب الأحجام</h4>
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>الحجم</th>
                                    <th>السعر العادي (ر.س)</th>
                                    <th>سعر VIP (ر.س)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sizes as $size)
                                <tr>
                                    <td>
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                            {{ $size->label }}
                                        </span>
                                        <input type="hidden" name="sizes[{{ $size->id }}][size_id]" value="{{ $size->id }}">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" 
                                               class="form-input @error('sizes.'.$size->id.'.price_regular') error @enderror" 
                                               name="sizes[{{ $size->id }}][price_regular]" 
                                               value="{{ old('sizes.'.$size->id.'.price_regular') }}"
                                               placeholder="0.00">
                                        @error('sizes.'.$size->id.'.price_regular')
                                            <div class="form-error">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" 
                                               class="form-input @error('sizes.'.$size->id.'.price_vip') error @enderror" 
                                               name="sizes[{{ $size->id }}][price_vip]" 
                                               value="{{ old('sizes.'.$size->id.'.price_vip') }}"
                                               placeholder="0.00">
                                        @error('sizes.'.$size->id.'.price_vip')
                                            <div class="form-error">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="flex gap-3 mt-6">
                        <button type="submit" class="flex-1 btn-success">
                            <i class="fas fa-save ml-2"></i>حفظ جميع الأسعار
                        </button>
                        <a href="{{ route('prices.index') }}" class="flex-1 btn-secondary text-center">
                            <i class="fas fa-arrow-right ml-2"></i>رجوع للقائمة
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('perfume_search');
    const perfumeIdInput = document.getElementById('perfume_id');
    const dropdown = document.getElementById('perfume_dropdown');
    
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
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            z-index: 1050;
            max-height: 300px;
            overflow-y: auto;
            overflow-x: hidden;
            margin-top: 8px;
            backdrop-filter: blur(10px);
        }
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
        .search-result-item {
            padding: 18px 20px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, #ffffff, #fafbfc);
        }
        .search-result-item:hover {
            background-color: #f8f9fa !important;
        }
        .search-result-item:last-child {
            border-bottom: none;
        }
    `;
    document.head.appendChild(style);
    
    // Search function
    async function performSearch(query) {
        if (query.length < 2) {
            dropdown.style.display = 'none';
            return;
        }
        
        try {
            const response = await fetch(`{{ route('perfumes.searchUncategorized') }}?q=${encodeURIComponent(query)}`);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
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
            dropdown.innerHTML = `
                <div class="p-4 text-center">
                    <i class="fas fa-search text-muted mb-2" style="font-size: 2rem;"></i>
                    <div class="text-muted">لا توجد نتائج</div>
                </div>
            `;
        } else {
            dropdown.innerHTML = results.map(perfume => `
                <div class="search-result-item" data-perfume-id="${perfume.id}" data-perfume-name="${perfume.name}">
                    <div class="fw-bold text-dark" style="font-size: 1rem; color: #2c3e50;">${perfume.name}</div>
                    <span class="badge bg-info px-4 py-2" style="border-radius: 20px; font-size: 0.8rem; font-weight: 600; box-shadow: 0 2px 8px rgba(23, 162, 184, 0.3);">
                        <i class="fas fa-tag me-2"></i>${perfume.category}
                    </span>
                </div>
            `).join('');
            
            // Add click handlers
            dropdown.querySelectorAll('.search-result-item').forEach((item) => {
                item.addEventListener('click', () => {
                    const perfumeId = item.dataset.perfumeId;
                    const perfumeName = item.dataset.perfumeName;
                    
                    // Set the selected perfume
                    searchInput.value = perfumeName;
                    searchInput.dataset.selectedName = perfumeName;
                    perfumeIdInput.value = perfumeId;
                    dropdown.style.display = 'none';
                    
                    // Remove any previous error styling
                    searchInput.classList.remove('is-invalid');
                    
                    // Load existing prices for this perfume
                    loadPerfumePrices(perfumeId);
                });
            });
        }
        dropdown.style.display = 'block';
    }
    
    // Load existing prices for selected perfume
    function loadPerfumePrices(perfumeId) {
        if (perfumeId) {
            fetch(`/api/get-perfume-prices/${perfumeId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.length > 0) {
                        // Load bottle information from first record
                        const firstPrice = data[0];
                        const bottleSizeInput = document.getElementById('bottle_size');
                        const bottlePriceRegularInput = document.getElementById('bottle_price_regular');
                        const bottlePriceVipInput = document.getElementById('bottle_price_vip');
                        
                        if (bottleSizeInput) bottleSizeInput.value = firstPrice.bottle_size || '';
                        if (bottlePriceRegularInput) bottlePriceRegularInput.value = firstPrice.bottle_price_regular || '';
                        if (bottlePriceVipInput) bottlePriceVipInput.value = firstPrice.bottle_price_vip || '';
                        
                        // Load size prices
                        data.forEach(price => {
                            const regularInput = document.querySelector(`input[name="sizes[${price.size_id}][price_regular]"]`);
                            const vipInput = document.querySelector(`input[name="sizes[${price.size_id}][price_vip]"]`);
                            if (regularInput) regularInput.value = price.price_regular || '';
                            if (vipInput) vipInput.value = price.price_vip || '';
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading perfume prices:', error);
                });
        } else {
            // Clear all inputs
            const bottleSizeInput = document.getElementById('bottle_size');
            const bottlePriceRegularInput = document.getElementById('bottle_price_regular');
            const bottlePriceVipInput = document.getElementById('bottle_price_vip');
            
            if (bottleSizeInput) bottleSizeInput.value = '';
            if (bottlePriceRegularInput) bottlePriceRegularInput.value = '';
            if (bottlePriceVipInput) bottlePriceVipInput.value = '';
            
            document.querySelectorAll('input[name*="[price_regular]"], input[name*="[price_vip]"]').forEach(input => {
                input.value = '';
            });
        }
    }
    
    // Input event handler
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        // Clear perfume ID if search input changes
        if (perfumeIdInput.value && this.value !== this.dataset.selectedName) {
            perfumeIdInput.value = '';
            delete this.dataset.selectedName;
        }
        
        if (query.length >= 2) {
            searchTimeout = setTimeout(() => performSearch(query), 300);
        } else {
            dropdown.style.display = 'none';
            perfumeIdInput.value = '';
        }
    });
    
    // Hide dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.closest('.search-container').contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });
    
    // Focus handling
    searchInput.addEventListener('focus', function() {
        if (this.value.trim().length >= 2) {
            dropdown.style.display = 'block';
        }
    });
    
    // Load initial data if perfume is already selected
    if (perfumeIdInput.value) {
        loadPerfumePrices(perfumeIdInput.value);
    }
    
    // Form validation before submit
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const perfumeId = perfumeIdInput.value;
            const searchValue = searchInput.value.trim();
            
            if (!perfumeId || !searchValue) {
                e.preventDefault();
                searchInput.classList.add('is-invalid');
                
                // Show error message
                let errorDiv = searchInput.parentNode.querySelector('.invalid-feedback');
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    searchInput.parentNode.appendChild(errorDiv);
                }
                errorDiv.textContent = 'يجب اختيار عطر من القائمة';
                
                searchInput.focus();
                return false;
            }
            
            // Check if at least one price is filled
            const priceInputs = form.querySelectorAll('input[name*="[price_regular]"], input[name*="[price_vip]"]');
            let hasPrice = false;
            
            priceInputs.forEach(input => {
                if (input.value && parseFloat(input.value) > 0) {
                    hasPrice = true;
                }
            });
            
            if (!hasPrice) {
                e.preventDefault();
                alert('يجب إدخال سعر واحد على الأقل');
                return false;
            }
        });
    }
});
</script>
@endsection