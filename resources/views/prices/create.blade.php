@extends('layouts.app')

@section('title', 'إضافة أسعار جديدة')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
        <div class="card-modern mb-4">
            <div class="card-header d-flex align-items-center" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 15px 15px 0 0;">
                <h5 class="mb-0"></h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('prices.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <h4 class="mb-0 fw-bold">إضافة أسعار جديدة</h4>
                    </div>

                    <div class="mb-4">
                        <label for="perfume_search" class="form-label fw-semibold">العطر (العطور غير المصنفة فقط)</label>
                        <div class="search-container position-relative">
                            <input type="text" 
                                   class="form-control @error('perfume_id') is-invalid @enderror" 
                                   id="perfume_search" 
                                   name="perfume_search"
                                   placeholder="ابحث عن العطر غير المصنف..."
                                   value="{{ old('perfume_search') }}"
                                   style="border-radius: 12px; padding: 12px 45px 12px 20px; border: 2px solid #e9ecef; transition: all 0.3s;"
                                   autocomplete="off">
                            <i class="fas fa-search search-icon" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                            <input type="hidden" id="perfume_id" name="perfume_id" value="{{ old('perfume_id') }}" required>
                            <div id="perfume_dropdown" class="search-dropdown" style="display: none;"></div>
                            @error('perfume_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted">يمكن إضافة أسعار للعطور غير المصنفة فقط. العطور المصنفة لها أسعار ثابتة.</small>
                    </div>

                    <!-- معلومات العبوة الكاملة -->
                    <h5 class="mb-4 fw-bold">معلومات العبوة الكاملة</h5>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="bottle_size" class="form-label fw-semibold">حجم العبوة الكاملة</label>
                            <select class="form-select @error('bottle_size') is-invalid @enderror" 
                                    id="bottle_size" 
                                    name="bottle_size" 
                                    style="border-radius: 12px; padding: 12px 14px;">
                                <option value="">اختر حجم العبوة</option>
                                @foreach($sizes as $size)
                                    <option value="{{ $size->label }}" {{ old('bottle_size') == $size->label ? 'selected' : '' }}>
                                        {{ $size->label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bottle_size')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="bottle_price_regular" class="form-label fw-semibold">سعر العبوة - عادي (دينار)</label>
                            <input type="number" step="0.01" 
                                   class="form-control @error('bottle_price_regular') is-invalid @enderror" 
                                   id="bottle_price_regular" 
                                   name="bottle_price_regular" 
                                   value="{{ old('bottle_price_regular') }}"
                                   placeholder="0.00"
                                   style="border-radius: 12px; padding: 12px 14px;">
                            @error('bottle_price_regular')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="bottle_price_vip" class="form-label fw-semibold">سعر العبوة - VIP (دينار)</label>
                            <input type="number" step="0.01" 
                                   class="form-control @error('bottle_price_vip') is-invalid @enderror" 
                                   id="bottle_price_vip" 
                                   name="bottle_price_vip" 
                                   value="{{ old('bottle_price_vip') }}"
                                   placeholder="0.00"
                                   style="border-radius: 12px; padding: 12px 14px;">
                            @error('bottle_price_vip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <h5 class="mb-4 fw-bold">الأسعار حسب الأحجام المقسمة</h5>
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th class="border-0">الحجم</th>
                                    <th class="border-0">السعر العادي (دينار)</th>
                                    <th class="border-0">سعر VIP (دينار)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sizes as $size)
                                <tr>
                                    <td class="align-middle">
                                        <span class="badge bg-secondary px-3 py-2 fw-semibold" style="border-radius: 15px;">
                                            <i class="fas fa-ruler me-1"></i>{{ $size->label }}
                                        </span>
                                        <input type="hidden" name="sizes[{{ $size->id }}][size_id]" value="{{ $size->id }}">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" 
                                               class="form-control @error('sizes.'.$size->id.'.price_regular') is-invalid @enderror" 
                                               name="sizes[{{ $size->id }}][price_regular]" 
                                               value="{{ old('sizes.'.$size->id.'.price_regular') }}"
                                               placeholder="0.00"
                                               style="border-radius: 10px; padding: 10px 12px;">
                                        @error('sizes.'.$size->id.'.price_regular')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" 
                                               class="form-control @error('sizes.'.$size->id.'.price_vip') is-invalid @enderror" 
                                               name="sizes[{{ $size->id }}][price_vip]" 
                                               value="{{ old('sizes.'.$size->id.'.price_vip') }}"
                                               placeholder="0.00"
                                               style="border-radius: 10px; padding: 10px 12px;">
                                        @error('sizes.'.$size->id.'.price_vip')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-modern d-inline-flex align-items-center gap-2" style="border-radius: 12px;">
                            حفظ جميع الأسعار <i class="fas fa-save"></i>
                        </button>
                        <a href="{{ route('prices.index') }}" class="btn btn-secondary btn-modern d-inline-flex align-items-center gap-2" style="border-radius: 12px;">
                            رجوع للقائمة <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.btn-modern {
    border-radius: 12px;
    padding: 10px 20px;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
}
.btn-modern i { font-size: .95rem; }
.btn-modern:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(0,0,0,0.12); }
.btn-primary.btn-modern { background: linear-gradient(135deg, #667eea, #764ba2); border: none; }
.btn-primary.btn-modern:hover { background: linear-gradient(135deg, #5a6fe0, #6b3fb1); }
.btn-secondary.btn-modern { background: #f1f3f5; color: #343a40; border: none; }
.btn-secondary.btn-modern:hover { background: #e9ecef; }

.table-modern {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.table-modern thead {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.table-modern thead th {
    padding: 20px 15px;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table-modern tbody tr {
    border-bottom: 1px solid #f0f0f0;
}

.table-modern tbody tr:last-child {
    border-bottom: none;
}

.table-modern tbody td {
    padding: 20px 15px;
    vertical-align: middle;
}

.table-modern tbody tr:hover {
    background-color: #f8f9fa;
}
</style>

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