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
                            <th class="border-0" style="padding: 20px 15px; font-size: 0.9rem; font-weight: 600; text-align: center;">#</th>
                            <th class="border-0" style="padding: 20px 15px; font-size: 0.9rem; font-weight: 600; text-align: center;">العطر</th>
                            <th class="border-0" style="padding: 20px 15px; font-size: 0.9rem; font-weight: 600; text-align: center;">سعر العبوة</th>
                            @foreach($sizes as $size)
                                <th class="border-0" style="padding: 20px 15px; font-size: 0.9rem; font-weight: 600; text-align: center;">{{ $size->label }}</th>
                            @endforeach
                            <th class="border-0 text-center" style="padding: 20px 15px; font-size: 0.9rem; font-weight: 600; text-align: center;">الإجراءات</th>
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
                            <td class="fw-bold" style="padding: 20px 15px; font-size: 0.95rem; text-align: center;">{{ $perfume->id }}</td>
                            <td style="padding: 20px 15px; text-align: center;">
                                <span class="badge px-3 py-2 fw-semibold" style="border-radius: 15px; background: linear-gradient(135deg, #667eea, #764ba2); color: #fff;">
                                    <i class="fas fa-spray-can me-1"></i>{{ $perfume->name }}
                                </span>
                                @if($bottleSize)
                                    <div class="mt-1"><small class="text-muted">{{ $bottleSize }}</small></div>
                                @endif
                            </td>
                            <td style="padding: 20px 15px; text-align: center;">
                                @php
                                    $bottlePriceRegular = $perfumePrices->first()->bottle_price_regular;
                                    $bottlePriceVip = $perfumePrices->first()->bottle_price_vip;
                                @endphp
                                @if($bottlePriceRegular || $bottlePriceVip)
                                    @if($bottlePriceRegular)
                                        <div class="mb-1">
                                            <span class="badge px-2 py-1" style="border-radius: 10px; background:#e8f5e8; color:#28a745; font-weight:600; font-size:0.8rem;">
                                                عادي: {{ number_format($bottlePriceRegular, 2) }}
                                            </span>
                                        </div>
                                    @endif
                                    @if($bottlePriceVip)
                                        <div>
                                            <span class="badge px-2 py-1" style="border-radius: 10px; background:#fff3cd; color:#856404; font-weight:600; font-size:0.8rem;">
                                                VIP: {{ number_format($bottlePriceVip, 2) }}
                                            </span>
                                        </div>
                                    @endif
                                @else
                                    <span class="text-muted">غير محدد</span>
                                @endif
                            </td>
                            @foreach($sizes as $size)
                                @php
                                    $sizePrice = $perfumePrices->where('size_id', $size->id)->first();
                                @endphp
                                <td style="padding: 20px 15px; text-align: center;">
                                    @if($sizePrice)
                                        <div class="mb-1">
                                            <span class="badge px-2 py-1" style="border-radius: 10px; background:#e8f5e8; color:#28a745; font-weight:600; font-size:0.8rem;">
                                                عادي: {{ number_format($sizePrice->price_regular, 2) }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="badge px-2 py-1" style="border-radius: 10px; background:#fff3cd; color:#856404; font-weight:600; font-size:0.8rem;">
                                                VIP: {{ number_format($sizePrice->price_vip, 2) }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-muted">لا يوجد سعر</span>
                                    @endif
                                </td>
                            @endforeach
                            <td class="text-center" style="padding: 20px 15px; text-align: center;">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('prices.edit', $perfumePrices->first()) }}" class="btn btn-sm btn-warning d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%;" 
                                            data-bs-toggle="modal" data-bs-target="#deletePriceModal{{ $perfume->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @foreach($groupedPrices as $perfumeId => $perfumePrices)
    @php
        $perfume = $perfumePrices->first()->perfume;
    @endphp
    <div class="modal fade" id="deletePriceModal{{ $perfume->id }}" tabindex="-1" aria-labelledby="deletePriceModalLabel{{ $perfume->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                <div class="modal-header" style="border-bottom: none; padding: 24px 24px 0;">
                    <h5 class="modal-title fw-bold" id="deletePriceModalLabel{{ $perfume->id }}">تأكيد حذف الأسعار</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 16px 24px 24px;">
                    <div class="alert alert-warning d-flex align-items-center" style="border-radius: 12px; border: none;">
                        <i class="fas fa-info-circle me-3"></i>
                        <div>
                            سيتم حذف جميع أسعار العطر "<strong>{{ $perfume->name }}</strong>" نهائياً.
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: none; padding: 0 24px 24px;">
                    <div class="d-flex gap-3 w-100">
                        <button type="button" class="btn btn-secondary btn-modern flex-fill d-inline-flex align-items-center justify-content-center" data-bs-dismiss="modal" style="border-radius: 12px; padding: 12px; gap: 8px;">
                            <i class="fas fa-times"></i>
                            <span>إلغاء</span>
                        </button>
                        <form action="{{ route('prices.destroy', $perfumePrices->first()) }}" method="POST" class="flex-fill">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-modern w-100 d-inline-flex align-items-center justify-content-center" style="border-radius: 12px; padding: 12px; gap: 8px;">
                                <i class="fas fa-trash"></i>
                                <span>حذف نهائياً</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@else
    <div class="empty-state">
        <i class="fas fa-dollar-sign"></i>
        <h4 class="text-muted mb-3">لا توجد أسعار مضافة بعد</h4>
        <p class="text-muted mb-4">ابدأ بتعيين أسعار للعطور المختلفة</p>
    </div>
@endif
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