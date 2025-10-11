@extends('layouts.app')

@section('title', 'الأحجام')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="fas fa-ruler me-2 text-primary"></i>الأحجام</h2>
        <p class="text-muted mb-0">إدارة أحجام العطور المختلفة</p>
    </div>
    <a href="{{ route('sizes.create') }}" class="btn btn-primary btn-modern">
        <i class="fas fa-plus me-2"></i>إضافة حجم جديد
    </a>
</div>

@if($sizes->count() > 0)
    <!-- إحصائيات سريعة -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="stats-card">
                <div class="stats-number">{{ $sizes->count() }}</div>
                <div class="stats-label">إجمالي الأحجام</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stats-card" style="background: linear-gradient(135deg, #ff6b35, #f7931e);">
                <div class="stats-number">{{ $sizes->where('created_at', '>=', now()->subDays(7))->count() }}</div>
                <div class="stats-label">مضاف هذا الأسبوع</div>
            </div>
        </div>
    </div>

    <div class="card-modern">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th class="border-0" style="padding: 20px 15px; font-size: 0.9rem; font-weight: 600; text-align: center;">ID</th>
                            <th class="border-0" style="padding: 20px 15px; font-size: 0.9rem; font-weight: 600; text-align: center;">الحجم</th>
                            <th class="border-0" style="padding: 20px 15px; font-size: 0.9rem; font-weight: 600; text-align: center;">تاريخ الإضافة</th>
                            <th class="border-0 text-center" style="padding: 20px 15px; font-size: 0.9rem; font-weight: 600; text-align: center;">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sizes as $size)
                        <tr>
                            <td class="fw-bold" style="padding: 20px 15px; font-size: 0.95rem; text-align: center;">{{ $size->id }}</td>
                            <td style="padding: 20px 15px; text-align: center;">
                                <span class="badge bg-primary px-3 py-2 fw-semibold" style="border-radius: 15px;">
                                    <i class="fas fa-ruler me-1"></i>{{ $size->label }}
                                </span>
                            </td>
                            <td style="padding: 20px 15px; font-size: 0.95rem; text-align: center;">
                                <span class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>{{ $size->created_at->format('Y-m-d') }}
                                </span>
                            </td>
                            <td class="text-center" style="padding: 20px 15px; text-align: center;">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('sizes.edit', $size) }}" class="btn btn-sm btn-warning d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%;" onclick="showDeleteModal({{ $size->id }}, '{{ $size->label }}')">
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
@else
    <div class="empty-state">
        <i class="fas fa-ruler"></i>
        <h4 class="text-muted mb-3">لا توجد أحجام مضافة بعد</h4>
        <p class="text-muted mb-4">ابدأ بإضافة أحجام مختلفة للعطور</p>
        <a href="{{ route('sizes.create') }}" class="btn btn-primary btn-modern btn-lg">
            <i class="fas fa-plus me-2"></i>إضافة أول حجم
        </a>
    </div>
@endif

<!-- Modal حذف الحجم -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
            <div class="modal-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #ff6b6b, #ee5a52); color: white; border-radius: 20px 20px 0 0; border: none;">
                <h5 class="modal-title fw-bold mb-0" id="deleteModalLabel" style="flex: 1;">
                    <i class="fas fa-exclamation-triangle me-2"></i>تأكيد الحذف
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <i class="fas fa-trash-alt text-danger" style="font-size: 3rem;"></i>
                </div>
                <h5 class="mb-3">هل أنت متأكد من حذف هذا الحجم؟</h5>
                <div class="alert alert-warning" style="border-radius: 12px;">
                    <strong>الحجم:</strong> <span id="sizeName"></span>
                </div>
                <p class="text-muted mb-0">هذا الإجراء لا يمكن التراجع عنه</p>
            </div>
            <div class="modal-footer" style="border: none; padding: 0 1.5rem 1.5rem;">
                <form id="deleteForm" method="POST" class="w-100">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex gap-3">
                        <button type="button" class="btn btn-secondary btn-modern gap-2 flex-fill d-flex align-items-center justify-content-center" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>إلغاء
                        </button>
                        <button type="submit" class="btn btn-danger btn-modern gap-2 flex-fill d-flex align-items-center justify-content-center">
                            <i class="fas fa-trash me-2"></i>حذف نهائياً
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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

<script>
function showDeleteModal(sizeId, sizeLabel) {
    document.getElementById('sizeName').textContent = sizeLabel;
    document.getElementById('deleteForm').action = `/sizes/${sizeId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endsection