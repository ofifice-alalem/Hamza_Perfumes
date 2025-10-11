@extends('layouts.app')

@section('title', 'المبيعات')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="fas fa-chart-line me-2 text-primary"></i>المبيعات</h2>
        <p class="text-muted mb-0">إدارة مبيعات العطور ومتابعة الإيرادات</p>
    </div>
</div>

<!-- نموذج البيع -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card-modern">
            <div class="card-body p-4">
                <form action="{{ route('sales.store') }}" method="POST" id="saleForm" class="row g-3">
                    @csrf
                    <div class="col">
                        <label for="perfume_search" class="form-label fw-semibold">العطر</label>
                        <div class="search-container position-relative">
                            <input type="text" 
                                   class="form-control @error('perfume_id') is-invalid @enderror" 
                                   id="perfume_search" 
                                   name="perfume_search"
                                   placeholder="ابحث عن العطر..."
                                   style="border-radius: 10px; padding: 12px 45px 12px 20px; height: 48px;"
                                   autocomplete="off">
                            <i class="fas fa-search search-icon" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                            <input type="hidden" id="perfume_id" name="perfume_id" value="{{ request('perfume_id') }}" required>
                            <div id="perfume_dropdown" class="search-dropdown" style="display: none;"></div>
                            @error('perfume_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col">
                        <label for="size_id" class="form-label fw-semibold">الحجم</label>
                        <select class="form-select @error('size_id') is-invalid @enderror" 
                                id="size_id" name="size_id" required style="border-radius: 10px; height: 48px;" disabled>
                            <option value="">اختر العطر أولاً</option>
                        </select>
                        @error('size_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <label for="customer_type" class="form-label fw-semibold">نوع العميل</label>
                        <select class="form-select @error('customer_type') is-invalid @enderror" 
                                id="customer_type" name="customer_type" required style="border-radius: 10px; height: 48px;">
                            <option value="">اختر نوع العميل</option>
                            <option value="regular">عادي</option>
                            <option value="vip">VIP</option>
                        </select>
                        @error('customer_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <label class="form-label fw-semibold">السعر</label>
                        <div class="alert alert-info d-flex align-items-center" id="priceDisplay" style="border-radius: 10px; height: 48px; margin-bottom: 0; padding: 8px 12px;">
                            <i class="fas fa-info-circle me-2"></i>اختر العطر والحجم ونوع العميل
                        </div>
                    </div>

                    <div class="col-auto d-flex align-items-end">
                        <button type="submit" class="btn btn-success btn-modern" id="sellBtn" disabled style="height: 48px; border-radius: 10px;">
                            <i class="fas fa-shopping-cart me-2"></i>بيع
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- سجل المبيعات -->
<div class="row">
    <div class="col-12">
        @if($sales->count() > 0)
            <!-- إحصائيات سريعة -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stats-card" style="background: linear-gradient(135deg, #17a2b8, #6f42c1);">
                        <div class="stats-sublabel">{{ $sales->where('customer_type', 'regular')->count() }} عميل</div>
                        <div class="stats-label">عملاء عاديين</div>
                        <div class="stats-number">{{ number_format($sales->where('customer_type', 'regular')->sum('price'), 0) }} دينار</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card" style="background: linear-gradient(135deg, #ff6b35, #f7931e);">
                        <div class="stats-sublabel">{{ $sales->where('customer_type', 'vip')->count() }} عميل</div>
                        <div class="stats-label">عملاء VIP</div>
                        <div class="stats-number">{{ number_format($sales->where('customer_type', 'vip')->sum('price'), 0) }} دينار</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card" style="background: linear-gradient(135deg, #28a745, #20c997);">
                        <div class="stats-sublabel">{{ $sales->count() }} عميل</div>
                        <div class="stats-label">إجمالي العملاء</div>
                        <div class="stats-number">{{ number_format($sales->sum('price'), 0) }} دينار</div>
                    </div>
                </div>
            </div>

            <div class="card-modern">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern mb-0">
                            <thead>
                                <tr>
                                    <th class="border-0" style="padding: 20px 15px; font-size: 0.9rem; font-weight: 600; text-align: center;">#</th>
                                    <th class="border-0" style="padding: 20px 15px; font-size: 0.9rem; font-weight: 600; text-align: center;">العطر</th>
                                    <th class="border-0" style="padding: 20px 15px; font-size: 0.9rem; font-weight: 600; text-align: center;">الحجم</th>
                                    <th class="border-0" style="padding: 20px 15px; font-size: 0.9rem; font-weight: 600; text-align: center;">نوع العميل</th>
                                    <th class="border-0" style="padding: 20px 15px; font-size: 0.9rem; font-weight: 600; text-align: center;">السعر</th>
                                    <th class="border-0" style="padding: 20px 15px; font-size: 0.9rem; font-weight: 600; text-align: center;">التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sales as $sale)
                                <tr>
                                    <td class="fw-bold" style="padding: 20px 15px; font-size: 0.95rem; text-align: center;">{{ $sale->id }}</td>
                                    <td style="padding: 20px 15px; text-align: center;">
                                        <span class="badge px-3 py-2 fw-semibold" style="border-radius: 15px; background: linear-gradient(135deg, #667eea, #764ba2); color: #fff;">
                                            {{ $sale->perfume->name }}
                                        </span>
                                    </td>
                                    <td style="padding: 20px 15px; text-align: center;">
                                        @if($sale->size_id && $sale->size)
                                            @if($sale->is_full_bottle)
                                                <span class="badge px-3 py-2" style="border-radius: 15px; background:#fff3cd; color:#856404; font-weight:600;">
                                                    <i class="fas fa-wine-bottle me-1" style="color:#856404;"></i>{{ $sale->size->label }} (عبوة كاملة)
                                                </span>
                                            @else
                                                <span class="badge px-3 py-2" style="border-radius: 15px; background:#eef2ff; color:#374151; font-weight:600;">
                                                    <i class="fas fa-ruler me-1" style="color:#6366f1;"></i>{{ $sale->size->label }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="badge px-3 py-2" style="border-radius: 15px; background:#fff3cd; color:#856404; font-weight:600;">
                                                <i class="fas fa-wine-bottle me-1" style="color:#856404;"></i>عبوة كاملة
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding: 20px 15px; text-align: center;">
                                        <span class="badge px-3 py-2" style="border-radius: 15px; background:{{ $sale->customer_type === 'vip' ? '#fff3cd' : '#d1ecf1' }}; color:{{ $sale->customer_type === 'vip' ? '#856404' : '#0c5460' }}; font-weight:600;">
                                            <i class="fas {{ $sale->customer_type === 'vip' ? 'fa-crown' : 'fa-user' }} me-1"></i>
                                            {{ $sale->customer_type === 'vip' ? 'VIP' : 'عادي' }}
                                        </span>
                                    </td>
                                    <td style="padding: 20px 15px; text-align: center;">
                                        <span class="badge px-3 py-2" style="border-radius: 15px; background:#e8f5e8; color:#28a745; font-weight:600;">
                                            {{ number_format($sale->price, 2) }} دينار
                                        </span>
                                    </td>
                                    <td style="padding: 20px 15px; text-align: center;">
                                        <span class="text-muted">
                                            {{ $sale->created_at->format('Y-m-d H:i') }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-shopping-cart"></i>
                <h4 class="text-muted mb-3">لا توجد مبيعات بعد</h4>
                <p class="text-muted mb-4">ابدأ ببيع عطورك الأولى</p>
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
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            z-index: 9999;
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
                    <div class="fw-bold text-dark" style="font-size: 1rem; color: #2c3e50;">${perfume.name}</div>
                    <span class="badge bg-info px-4 py-2" style="border-radius: 20px; font-size: 0.8rem; font-weight: 600; box-shadow: 0 2px 8px rgba(23, 162, 184, 0.3);">
                        <i class="fas fa-tag me-2"></i>${perfume.category}
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
                        priceDisplay.innerHTML = '<span class="text-danger">السعر غير محدد لهذا العطر والحجم</span>';
                        sellBtn.disabled = true;
                    } else {
                        const price = customerType === 'vip' ? data.vip : data.regular;
                        priceDisplay.innerHTML = `<strong>${price} دينار</strong>`;
                        sellBtn.disabled = false;
                    }
                })
                .catch(error => {
                    priceDisplay.innerHTML = '<span class="text-danger">خطأ في جلب السعر</span>';
                    sellBtn.disabled = true;
                });
        } else {
            priceDisplay.innerHTML = 'اختر العطر والحجم ونوع العميل لعرض السعر';
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
</script>

<style>
/* Lightly increase row spacing and font size for this table */
.table-modern tbody td {
    padding: 22px 18px; /* more space between rows */
    font-size: 0.95rem; /* slightly larger text */
    line-height: 1.5;
}
.table-modern thead th {
    font-size: 0.95rem;
}

/* Table Row Styling */
.table-modern tbody tr {
    background-color: #ffffff;
    transition: background-color 0.2s ease;
}

.table-modern tbody tr:hover >*{
    background-color: #f5f5f5;
}
</style>
@endsection