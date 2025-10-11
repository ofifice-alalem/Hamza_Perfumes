@extends('layouts.app')

@section('title', 'المبيعات')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="fas fa-chart-line me-2 text-primary"></i>المبيعات</h2>
        <p class="text-muted mb-0">إدارة مبيعات العطور ومتابعة الإيرادات</p>
    </div>
</div>

<div class="row">
    <!-- نموذج البيع -->
    <div class="col-md-4">
        <div class="card-modern">
            <div class="card-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>بيع جديد</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('sales.store') }}" method="POST" id="saleForm">
                    @csrf
                    <div class="mb-3">
                        <label for="perfume_id" class="form-label fw-semibold">العطر</label>
                        <select class="form-select @error('perfume_id') is-invalid @enderror" 
                                id="perfume_id" name="perfume_id" required style="border-radius: 10px;">
                            <option value="">اختر العطر</option>
                            @foreach($perfumes as $perfume)
                                <option value="{{ $perfume->id }}" {{ request('perfume_id') == $perfume->id ? 'selected' : '' }}>{{ $perfume->name }}</option>
                            @endforeach
                        </select>
                        @error('perfume_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="size_id" class="form-label fw-semibold">الحجم</label>
                        <select class="form-select @error('size_id') is-invalid @enderror" 
                                id="size_id" name="size_id" required style="border-radius: 10px;">
                            <option value="">اختر الحجم</option>
                            @foreach($sizes as $size)
                                <option value="{{ $size->id }}">{{ $size->label }}</option>
                            @endforeach
                        </select>
                        @error('size_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="customer_type" class="form-label fw-semibold">نوع العميل</label>
                        <select class="form-select @error('customer_type') is-invalid @enderror" 
                                id="customer_type" name="customer_type" required style="border-radius: 10px;">
                            <option value="">اختر نوع العميل</option>
                            <option value="regular">عادي</option>
                            <option value="vip">VIP</option>
                        </select>
                        @error('customer_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">السعر</label>
                        <div class="alert alert-info" id="priceDisplay" style="border-radius: 10px;">
                            <i class="fas fa-info-circle me-2"></i>اختر العطر والحجم ونوع العميل لعرض السعر
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100 btn-modern" id="sellBtn" disabled>
                        <i class="fas fa-shopping-cart me-2"></i>بيع
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- سجل المبيعات -->
    <div class="col-md-8">
        @if($sales->count() > 0)
            <!-- إحصائيات سريعة -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stats-number">{{ $sales->count() }}</div>
                        <div class="stats-label">إجمالي المبيعات</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card" style="background: linear-gradient(135deg, #ff6b35, #f7931e);">
                        <div class="stats-number">{{ number_format($sales->sum('price'), 0) }}</div>
                        <div class="stats-label">إجمالي الإيرادات (ريال)</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card" style="background: linear-gradient(135deg, #28a745, #20c997);">
                        <div class="stats-number">{{ $sales->where('customer_type', 'vip')->count() }}</div>
                        <div class="stats-label">عملاء VIP</div>
                    </div>
                </div>
            </div>

            <div class="card-modern">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>سجل المبيعات</h5>
                    <span class="badge bg-primary px-3 py-2">{{ $sales->count() }} عملية بيع</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern mb-0">
                            <thead>
                                <tr>
                                    <th class="border-0">#</th>
                                    <th class="border-0">العطر</th>
                                    <th class="border-0">الحجم</th>
                                    <th class="border-0">نوع العميل</th>
                                    <th class="border-0">السعر</th>
                                    <th class="border-0">التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sales as $sale)
                                <tr>
                                    <td class="fw-bold">{{ $sale->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="project-icon me-3" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                                                {{ substr($sale->perfume->name, 0, 1) }}
                                            </div>
                                            <span class="fw-semibold">{{ $sale->perfume->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary px-3 py-2" style="border-radius: 15px;">
                                            <i class="fas fa-ruler me-1"></i>{{ $sale->size->label }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $sale->customer_type === 'vip' ? 'bg-warning' : 'bg-info' }} px-3 py-2" style="border-radius: 15px;">
                                            <i class="fas {{ $sale->customer_type === 'vip' ? 'fa-crown' : 'fa-user' }} me-1"></i>
                                            {{ $sale->customer_type === 'vip' ? 'VIP' : 'عادي' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">
                                            <i class="fas fa-dollar-sign me-1"></i>{{ number_format($sale->price, 2) }} ريال
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>{{ $sale->created_at->format('Y-m-d H:i') }}
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
    const perfumeSelect = document.getElementById('perfume_id');
    const sizeSelect = document.getElementById('size_id');
    const customerTypeSelect = document.getElementById('customer_type');
    const priceDisplay = document.getElementById('priceDisplay');
    const sellBtn = document.getElementById('sellBtn');

    function updatePrice() {
        const perfumeId = perfumeSelect.value;
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
                        priceDisplay.innerHTML = `<strong>${price} ريال</strong>`;
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

    perfumeSelect.addEventListener('change', updatePrice);
    sizeSelect.addEventListener('change', updatePrice);
    customerTypeSelect.addEventListener('change', updatePrice);
});
</script>

<style>
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