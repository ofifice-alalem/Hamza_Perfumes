@extends('layouts.app')

@section('title', 'الأسعار')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="fas fa-dollar-sign me-2 text-primary"></i>الأسعار</h2>
        <p class="text-muted mb-0">إدارة أسعار العطور حسب الحجم ونوع العميل</p>
    </div>
    <a href="{{ route('prices.create') }}" class="btn btn-primary btn-modern">
        <i class="fas fa-plus me-2"></i>إضافة سعر جديد
    </a>
</div>

@if($prices->count() > 0)
    <!-- إحصائيات سريعة -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number">{{ $prices->count() }}</div>
                <div class="stats-label">إجمالي الأسعار</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #ff6b35, #f7931e);">
                <div class="stats-number">{{ number_format($prices->avg('price_regular'), 0) }}</div>
                <div class="stats-label">متوسط السعر العادي</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #28a745, #20c997);">
                <div class="stats-number">{{ number_format($prices->avg('price_vip'), 0) }}</div>
                <div class="stats-label">متوسط سعر VIP</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #17a2b8, #6f42c1);">
                <div class="stats-number">{{ $prices->unique('perfume_id')->count() }}</div>
                <div class="stats-label">عطور مسعرة</div>
            </div>
        </div>
    </div>

    <div class="card-modern">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th class="border-0 text-center">#</th>
                            <th class="border-0 text-center">العطر</th>
                            <th class="border-0 text-center">سعر العبوة</th>
                            <th class="border-0 text-center">30 مل</th>
                            <th class="border-0 text-center">50 مل</th>
                            <th class="border-0 text-center">100 مل</th>
                            <th class="border-0 text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $groupedPrices = $prices->groupBy('perfume_id');
                        @endphp
                        @foreach($groupedPrices as $perfumeId => $perfumePrices)
                        @php
                            $perfume = $perfumePrices->first()->perfume;
                            $bottlePrice = $perfumePrices->first()->bottle_price;
                            $bottleSize = $perfumePrices->first()->bottle_size;
                        @endphp
                        <tr>
                            <td class="fw-bold text-center">{{ $perfume->id }}</td>
                            <td class="text-center">
                                <span class="fw-semibold">
                                    {{ $perfume->name }}
                                    @if($bottleSize)
                                        <span class="text-muted">{{ $bottleSize }}</span>
                                    @endif
                                </span>
                            </td>
                            <td class="text-center">
                                @if($bottlePrice)
                                    <div class="fw-bold text-primary">
                                        <i class="fas fa-wine-bottle me-1"></i>{{ number_format($bottlePrice, 2) }} ريال
                                    </div>
                                @else
                                    <span class="text-muted">غير محدد</span>
                                @endif
                            </td>
                            @foreach(['30', '50', '100'] as $size)
                                @php
                                    $sizePrice = $perfumePrices->where('size.label', $size . ' مل')->first();
                                @endphp
                                <td class="text-center">
                                    @if($sizePrice)
                                        <div class="mb-1">
                                            <span class="fw-bold text-success">
                                                <i class="fas fa-dollar-sign me-1"></i>{{ number_format($sizePrice->price_regular, 2) }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-warning">
                                                <i class="fas fa-crown me-1"></i>{{ number_format($sizePrice->price_vip, 2) }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            @endforeach
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('prices.edit', $perfumePrices->first()) }}" class="btn btn-sm btn-warning d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('prices.destroy', $perfumePrices->first()) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%;" onclick="return confirm('هل أنت متأكد من حذف هذا السعر؟')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
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
        <i class="fas fa-dollar-sign"></i>
        <h4 class="text-muted mb-3">لا توجد أسعار مضافة بعد</h4>
        <p class="text-muted mb-4">ابدأ بتعيين أسعار للعطور المختلفة</p>
    </div>
@endif

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