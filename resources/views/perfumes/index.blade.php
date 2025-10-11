@extends('layouts.app')

@section('title', 'العطور')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="fas fa-spray-can me-2 text-primary"></i>العطور</h2>
        <p class="text-muted mb-0">إدارة مجموعة العطور الخاصة بك</p>
    </div>
    <a href="{{ route('perfumes.create') }}" class="btn btn-primary btn-modern">
        <i class="fas fa-plus me-2"></i>إضافة عطر جديد
    </a>
</div>

@if($perfumes->count() > 0)
    <!-- إحصائيات سريعة -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number">{{ $perfumes->count() }}</div>
                <div class="stats-label">إجمالي العطور</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #ff6b35, #f7931e);">
                <div class="stats-number">{{ $perfumes->whereNotNull('category_id')->count() }}</div>
                <div class="stats-label">مصنفة</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #28a745, #20c997);">
                <div class="stats-number">{{ $perfumes->whereNull('category_id')->count() }}</div>
                <div class="stats-label">غير مصنفة</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #17a2b8, #6f42c1);">
                <div class="stats-number">{{ $perfumes->where('created_at', '>=', now()->subDays(7))->count() }}</div>
                <div class="stats-label">هذا الأسبوع</div>
            </div>
        </div>
    </div>

    <!-- Search Section -->
    <div class="card-modern mb-4">
        <div class="card-body p-4">
            <div class="search-container position-relative">
                <input type="text" 
                       class="form-control search-input w-100" 
                       id="perfumeSearch" 
                       placeholder="ابحث عن عطر بالاسم..."
                       style="border-radius: 25px; padding: 12px 45px 12px 20px; border: 2px solid #e9ecef; transition: all 0.3s;">
                <i class="fas fa-search search-icon" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
            </div>
        </div>
    </div>

    <div class="card-modern">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th class="border-0 text-center" style="width: 60px;">#</th>
                            <th class="border-0" style="width: 300px;">اسم العطر</th>
                            <th class="border-0" style="width: 200px;">التصنيف</th>
                            <th class="border-0" style="width: 150px;">تاريخ الإضافة</th>
                            <th class="border-0 text-center" style="width: 180px;">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($perfumes as $perfume)
                        <tr style="transition: all 0.3s ease;">
                            <td class="fw-bold text-center">
                                <span class="badge bg-light text-dark px-2 py-1" style="border-radius: 8px; font-size: 0.8rem;">
                                    {{ $perfume->id }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $perfume->name }}</span>
                            </td>
                            <td>
                                @if($perfume->category)
                                    <span class="badge bg-info px-3 py-2 fw-semibold" style="border-radius: 15px; font-size: 0.85rem;">
                                        <i class="fas fa-tag me-1"></i>{{ $perfume->category->name }}
                                    </span>
                                @else
                                    <span class="text-muted fw-medium">
                                        <i class="fas fa-question-circle me-1"></i>غير محدد
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="text-muted d-flex align-items-center">
                                    <i class="fas fa-calendar me-2"></i>
                                    <span class="fw-medium">{{ $perfume->created_at->format('Y-m-d') }}</span>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('perfumes.edit', $perfume) }}" class="btn btn-sm btn-warning d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%;" 
                                            data-bs-toggle="modal" data-bs-target="#deleteModal{{ $perfume->id }}">
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
        <i class="fas fa-spray-can"></i>
        <h4 class="text-muted mb-3">لا توجد عطور مضافة بعد</h4>
        <p class="text-muted mb-4">ابدأ ببناء مجموعتك من العطور المميزة</p>
        <a href="{{ route('perfumes.create') }}" class="btn btn-primary btn-modern btn-lg">
            <i class="fas fa-plus me-2"></i>إضافة أول عطر
        </a>
    </div>
@endif

<!-- Delete Modals -->
@foreach($perfumes as $perfume)
<div class="modal fade" id="deleteModal{{ $perfume->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $perfume->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
            <div class="modal-header" style="border-bottom: none; padding: 30px 30px 20px;">
                <div class="d-flex align-items-center">
                    <div class="delete-icon me-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, #ff6b6b, #ee5a52); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-exclamation-triangle text-white" style="font-size: 24px;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-1" id="deleteModalLabel{{ $perfume->id }}">تأكيد الحذف</h5>
                        <p class="text-muted mb-0">هذا الإجراء لا يمكن التراجع عنه</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background: none; border: none; font-size: 24px; color: #6c757d;"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <div class="alert alert-warning d-flex align-items-center" style="border-radius: 15px; border: none; background: linear-gradient(135deg, #fff3cd, #ffeaa7);">
                    <i class="fas fa-info-circle me-3" style="color: #856404; font-size: 20px;"></i>
                    <div>
                        <strong>تحذير:</strong> سيتم حذف العطر "<span class="fw-bold text-dark">{{ $perfume->name }}</span>" نهائياً من النظام.
                    </div>
                </div>
                <div class="perfume-details p-3" style="background: #f8f9fa; border-radius: 15px; margin-top: 15px;">
                    <h6 class="fw-bold mb-3 text-dark">تفاصيل العطر:</h6>
                    <div class="row">
                        <div class="col-6">
                            <div class="detail-item mb-2">
                                <span class="text-muted">اسم العطر:</span>
                                <span class="fw-semibold text-dark ms-2">{{ $perfume->name }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="detail-item mb-2">
                                <span class="text-muted">التصنيف:</span>
                                <span class="fw-semibold text-dark ms-2">
                                    @if($perfume->category)
                                        {{ $perfume->category->name }}
                                    @else
                                        غير محدد
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="detail-item mb-2">
                                <span class="text-muted">تاريخ الإضافة:</span>
                                <span class="fw-semibold text-dark ms-2">{{ $perfume->created_at->format('Y-m-d') }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="detail-item mb-2">
                                <span class="text-muted">معرف العطر:</span>
                                <span class="fw-semibold text-dark ms-2">#{{ $perfume->id }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: none; padding: 20px 30px 30px;">
                <div class="d-flex gap-3 w-100">
                    <button type="button" class="btn btn-secondary btn-modern flex-fill d-inline-flex align-items-center justify-content-center" data-bs-dismiss="modal" style="border-radius: 12px; padding: 12px; gap: 8px;">
                        <i class="fas fa-times"></i>
                        <span>إلغاء</span>
                    </button>
                    <form action="{{ route('perfumes.destroy', $perfume) }}" method="POST" class="flex-fill">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-modern w-100 d-inline-flex align-items-center justify-content-center" style="border-radius: 12px; padding: 12px; background: linear-gradient(135deg, #ff6b6b, #ee5a52); border: none; gap: 8px;">
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

<style>
.modal-backdrop {
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(5px);
}

.modal-content {
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px) scale(0.9);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.delete-icon {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
    }
}

.detail-item {
    display: flex;
    align-items: center;
    font-size: 0.9rem;
}

/* Table Row Styling */
.table-modern tbody tr {
    background-color: #ffffff;
    transition: background-color 0.2s ease;
}

.table-modern tbody tr:hover >*{
    background-color: #f5f5f5;
}

/* Enhanced Table Styling */
.table-modern {
    border-collapse: separate;
    border-spacing: 0;
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

.table-modern thead th {
    padding: 20px 15px;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Search Input Styling */
.search-input:focus {
    outline: none;
    border-color: #667eea !important;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
}

.search-input:hover {
    border-color: #667eea;
}

/* Search Results Highlighting */
.highlight {
    background-color: #fff3cd;
    padding: 2px 4px;
    border-radius: 4px;
    font-weight: bold;
}

/* No Results Message */
.no-results {
    text-align: center;
    padding: 40px 20px;
    color: #6c757d;
}

.no-results i {
    font-size: 3rem;
    margin-bottom: 15px;
    color: #dee2e6;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('perfumeSearch');
    const tableBody = document.querySelector('.table-modern tbody');
    const allRows = Array.from(tableBody.querySelectorAll('tr'));
    
    // Store original rows for reset
    const originalRows = allRows.map(row => row.cloneNode(true));
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        
        if (searchTerm === '') {
            // Reset to original state
            tableBody.innerHTML = '';
            originalRows.forEach(row => tableBody.appendChild(row));
            return;
        }
        
        // Filter rows
        const filteredRows = allRows.filter(row => {
            const perfumeName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            return perfumeName.includes(searchTerm);
        });
        
        // Clear table body
        tableBody.innerHTML = '';
        
        if (filteredRows.length === 0) {
            // Show no results message
            const noResultsRow = document.createElement('tr');
            noResultsRow.innerHTML = `
                <td colspan="5" class="no-results">
                    <i class="fas fa-search"></i>
                    <h5>لا توجد نتائج</h5>
                    <p>لم يتم العثور على عطور تطابق البحث "<strong>${searchTerm}</strong>"</p>
                </td>
            `;
            tableBody.appendChild(noResultsRow);
        } else {
            // Add filtered rows with highlighting
            filteredRows.forEach(row => {
                const clonedRow = row.cloneNode(true);
                const perfumeNameCell = clonedRow.querySelector('td:nth-child(2)');
                const originalName = perfumeNameCell.textContent;
                
                // Highlight search term
                const highlightedName = originalName.replace(
                    new RegExp(`(${searchTerm})`, 'gi'),
                    '<span class="highlight">$1</span>'
                );
                perfumeNameCell.innerHTML = highlightedName;
                
                tableBody.appendChild(clonedRow);
            });
        }
    });
    
    // Add search animation
    searchInput.addEventListener('focus', function() {
        this.style.transform = 'scale(1.02)';
    });
    
    searchInput.addEventListener('blur', function() {
        this.style.transform = 'scale(1)';
    });
});
</script>
@endsection