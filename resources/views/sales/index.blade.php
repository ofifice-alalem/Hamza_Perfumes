@extends('layouts.app')

@section('title', 'المبيعات')

@section('content')
<div class="row">
    <!-- نموذج البيع -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>بيع جديد</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('sales.store') }}" method="POST" id="saleForm">
                    @csrf
                    <div class="mb-3">
                        <label for="perfume_id" class="form-label">العطر</label>
                        <select class="form-select @error('perfume_id') is-invalid @enderror" 
                                id="perfume_id" name="perfume_id" required>
                            <option value="">اختر العطر</option>
                            @foreach($perfumes as $perfume)
                                <option value="{{ $perfume->id }}">{{ $perfume->name }}</option>
                            @endforeach
                        </select>
                        @error('perfume_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="size_id" class="form-label">الحجم</label>
                        <select class="form-select @error('size_id') is-invalid @enderror" 
                                id="size_id" name="size_id" required>
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
                        <label for="customer_type" class="form-label">نوع العميل</label>
                        <select class="form-select @error('customer_type') is-invalid @enderror" 
                                id="customer_type" name="customer_type" required>
                            <option value="">اختر نوع العميل</option>
                            <option value="regular">عادي</option>
                            <option value="vip">VIP</option>
                        </select>
                        @error('customer_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">السعر</label>
                        <div class="alert alert-info" id="priceDisplay">
                            اختر العطر والحجم ونوع العميل لعرض السعر
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100" id="sellBtn" disabled>
                        <i class="fas fa-shopping-cart me-2"></i>بيع
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- سجل المبيعات -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>سجل المبيعات</h5>
            </div>
            <div class="card-body">
                @if($sales->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>العطر</th>
                                    <th>الحجم</th>
                                    <th>نوع العميل</th>
                                    <th>السعر</th>
                                    <th>التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sales as $sale)
                                <tr>
                                    <td>{{ $sale->id }}</td>
                                    <td>{{ $sale->perfume->name }}</td>
                                    <td>{{ $sale->size->label }}</td>
                                    <td>
                                        <span class="badge {{ $sale->customer_type === 'vip' ? 'bg-warning' : 'bg-secondary' }}">
                                            {{ $sale->customer_type === 'vip' ? 'VIP' : 'عادي' }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($sale->price, 2) }} ريال</td>
                                    <td>{{ $sale->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <p class="text-muted">لا توجد مبيعات بعد</p>
                    </div>
                @endif
            </div>
        </div>
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
@endsection