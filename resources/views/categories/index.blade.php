@extends('layouts.app')

@section('title', 'التصنيفات')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="fas fa-tags me-2 text-primary"></i>التصنيفات</h2>
        <p class="text-muted mb-0">تنظيم العطور حسب التصنيفات المختلفة</p>
    </div>
    <a href="{{ route('categories.create') }}" class="btn btn-primary btn-modern">
        <i class="fas fa-plus me-2"></i>إضافة تصنيف جديد
    </a>
</div>

@if($categories->count() > 0)
    <!-- إحصائيات سريعة -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stats-card">
                <div class="stats-number">{{ $categories->count() }}</div>
                <div class="stats-label">إجمالي التصنيفات</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card" style="background: linear-gradient(135deg, #ff6b35, #f7931e);">
                <div class="stats-number">{{ $categories->sum('perfumes_count') }}</div>
                <div class="stats-label">إجمالي العطور المصنفة</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card" style="background: linear-gradient(135deg, #28a745, #20c997);">
                <div class="stats-number">{{ $categories->where('perfumes_count', '>', 0)->count() }}</div>
                <div class="stats-label">تصنيفات نشطة</div>
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
                            <th class="border-0" style="padding: 20px 15px; font-size: 0.9rem; font-weight: 600; text-align: center;">اسم التصنيف</th>
                            <th class="border-0" style="padding: 20px 15px; font-size: 0.9rem; font-weight: 600; text-align: center;">عدد العطور</th>
                            <th class="border-0 text-center" style="padding: 20px 15px; font-size: 0.9rem; font-weight: 600; text-align: center;">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>
                            <td class="fw-bold" style="padding: 20px 15px; font-size: 0.95rem; text-align: center;">{{ $category->id }}</td>
                            <td style="padding: 20px 15px; text-align: center;">
                                <span class="badge px-3 py-2 fw-semibold" style="border-radius: 15px; background: linear-gradient(135deg, #ff6b35, #f7931e); color: #fff;">
                                    <i class="fas fa-tag me-1"></i>{{ $category->name }}
                                </span>
                            </td>
                            <td style="padding: 20px 15px; text-align: center;">
                                <span class="badge px-3 py-2" style="border-radius: 15px; background:#eef2ff; color:#374151; font-weight:600;">
                                    <i class="fas fa-spray-can me-1" style="color:#6366f1;"></i>{{ $category->perfumes_count }} عطر
                                </span>
                            </td>
                            <td class="text-center" style="padding: 20px 15px; text-align: center;">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-warning d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%;" 
                                            data-bs-toggle="modal" data-bs-target="#deleteCategoryModal{{ $category->id }}">
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
    @foreach($categories as $category)
    <div class="modal fade" id="deleteCategoryModal{{ $category->id }}" tabindex="-1" aria-labelledby="deleteCategoryModalLabel{{ $category->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                <div class="modal-header" style="border-bottom: none; padding: 24px 24px 0;">
                    <h5 class="modal-title fw-bold" id="deleteCategoryModalLabel{{ $category->id }}">تأكيد حذف التصنيف</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 16px 24px 24px;">
                    <div class="alert alert-warning d-flex align-items-center" style="border-radius: 12px; border: none;">
                        <i class="fas fa-info-circle me-3"></i>
                        <div>
                            سيتم حذف التصنيف "<strong>{{ $category->name }}</strong>" نهائياً.
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: none; padding: 0 24px 24px;">
                    <div class="d-flex gap-3 w-100">
                        <button type="button" class="btn btn-secondary btn-modern flex-fill d-inline-flex align-items-center justify-content-center" data-bs-dismiss="modal" style="border-radius: 12px; padding: 12px; gap: 8px;">
                            <i class="fas fa-times"></i>
                            <span>إلغاء</span>
                        </button>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="flex-fill">
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
        <i class="fas fa-tags"></i>
        <h4 class="text-muted mb-3">لا توجد تصنيفات مضافة بعد</h4>
        <p class="text-muted mb-4">ابدأ بإنشاء تصنيفات لتنظيم عطورك</p>
        <a href="{{ route('categories.create') }}" class="btn btn-primary btn-modern btn-lg">
            <i class="fas fa-plus me-2"></i>إضافة أول تصنيف
        </a>
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