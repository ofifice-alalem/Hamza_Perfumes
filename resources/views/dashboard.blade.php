@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="fas fa-tachometer-alt me-2 text-primary"></i>لوحة التحكم</h2>
        <p class="text-muted mb-0">نظرة شاملة على العطور والمبيعات</p>
    </div>
</div>

<!-- تحليل المبيعات -->
        <!-- فلاتر المبيعات -->
        <div class="card-modern mb-4">
            <div class="card-body p-4">
                <form id="salesFilters" class="row g-3">
                    <div class="col">
                        <label class="form-label fw-semibold">من تاريخ</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" style="border-radius: 10px;">
                    </div>
                    <div class="col">
                        <label class="form-label fw-semibold">إلى تاريخ</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" style="border-radius: 10px;">
                    </div>
                    <div class="col">
                        <label class="form-label fw-semibold">نوع العميل</label>
                        <select class="form-select" id="customer_type" name="customer_type" style="border-radius: 10px;">
                            <option value="">الكل</option>
                            <option value="regular">عادي</option>
                            <option value="vip">VIP</option>
                        </select>
                    </div>
                    <div class="col">
                        <label class="form-label fw-semibold">الصنف</label>
                        <select class="form-select" id="category_id" name="category_id" style="border-radius: 10px;">
                            <option value="">جميع الأصناف</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                            <option value="uncategorized">غير مصنف</option>
                        </select>
                    </div>
                    <div class="col">
                        <label class="form-label fw-semibold">ترتيب حسب</label>
                        <select class="form-select" id="sort_by" name="sort_by" style="border-radius: 10px;">
                            <option value="sales_count">عدد المبيعات</option>
                            <option value="total_amount">إجمالي المبلغ</option>
                            <option value="total_ml">إجمالي الكمية (مل)</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <!-- إحصائيات سريعة -->
        <div class="row mb-4" id="salesStats">
            <div class="col-md-3">
                <div class="stats-card" style="background: linear-gradient(135deg, #28a745, #20c997);">
                    <div class="stats-number" id="totalSales">0</div>
                    <div class="stats-label">إجمالي المبيعات</div>
                    <div class="stats-sublabel">دينار</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card" style="background: linear-gradient(135deg, #17a2b8, #6f42c1);">
                    <div class="stats-number" id="totalCustomers">0</div>
                    <div class="stats-label">عدد العملاء</div>
                    <div class="stats-sublabel">عميل</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card" style="background: linear-gradient(135deg, #ff6b35, #f7931e);">
                    <div class="stats-number" id="totalML">0</div>
                    <div class="stats-label">إجمالي الكمية</div>
                    <div class="stats-sublabel">مل</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                    <div class="stats-number" id="avgSale">0</div>
                    <div class="stats-label">متوسط البيع</div>
                    <div class="stats-sublabel">دينار</div>
                </div>
            </div>
        </div>

        <!-- جدول المبيعات -->
        <div class="card-modern">
            <div class="card-header d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 15px 15px 0 0; padding: 20px;">
                <h5 class="mb-0 fw-bold"><i class="fas fa-chart-bar me-2"></i>تحليل المبيعات حسب العطر</h5>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge" style="background: rgba(255,255,255,0.2); padding: 8px 16px; border-radius: 20px;" id="resultsCount">0 نتيجة</span>
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" style="border-radius: 20px; padding: 8px 16px; font-weight: 600; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                            <i class="fas fa-download me-2"></i>تصدير
                        </button>
                        <ul class="dropdown-menu" style="border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); border: none; padding: 10px 0;">
                            <li><a class="dropdown-item text-center gap-2 d-flex align-items-center justify-content-center" href="#" onclick="exportData('csv')" style="padding: 12px 20px; border-radius: 10px; margin: 2px 8px; transition: all 0.3s; color: #495057;"><i class="fas fa-file-csv me-4 text-success"></i>CSV</a></li>
                            <li><hr class="dropdown-divider" style="margin: 4px 16px; border-color: rgba(0,0,0,0.08);"></li>
                            <li><a class="dropdown-item text-center gap-2 d-flex align-items-center justify-content-center" href="#" onclick="exportData('json')" style="padding: 12px 20px; border-radius: 10px; margin: 2px 8px; transition: all 0.3s; color: #495057;"><i class="fas fa-file-code me-4 text-primary"></i>JSON</a></li>
                            <li><hr class="dropdown-divider" style="margin: 4px 16px; border-color: rgba(0,0,0,0.08);"></li>
                            <li><a class="dropdown-item text-center gap-2 d-flex align-items-center justify-content-center" href="#" onclick="exportData('xml')" style="padding: 12px 20px; border-radius: 10px; margin: 2px 8px; transition: all 0.3s; color: #495057;"><i class="fas fa-file-code me-4 text-warning"></i>XML</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern mb-0" id="salesTable">
                        <thead>
                            <tr>
                                <th class="border-0" style="padding: 20px 15px; font-size: 0.9rem; font-weight: 600; text-align: center; background: #f8f9fa;">#</th>
                                <th class="border-0" style="padding: 20px 15px; font-size: 0.9rem; font-weight: 600; background: #f8f9fa;">العطر</th>
                                <th class="border-0" style="padding: 20px 15px; font-size: 0.9rem; font-weight: 600; text-align: center; background: #f8f9fa;">الصنف</th>
                                <th class="border-0" style="padding: 20px 15px; font-size: 0.9rem; font-weight: 600; text-align: center; background: #f8f9fa;">عدد المبيعات</th>
                                <th class="border-0" style="padding: 20px 15px; font-size: 0.9rem; font-weight: 600; text-align: center; background: #f8f9fa;">الزبائن العاديين</th>
                                <th class="border-0" style="padding: 20px 15px; font-size: 0.9rem; font-weight: 600; text-align: center; background: #f8f9fa;">VIP</th>
                                <th class="border-0" style="padding: 20px 15px; font-size: 0.9rem; font-weight: 600; text-align: center; background: #f8f9fa;">إجمالي الكمية</th>
                                <th class="border-0" style="padding: 20px 15px; font-size: 0.9rem; font-weight: 600; text-align: center; background: #f8f9fa;">إجمالي المبلغ</th>
                            </tr>
                        </thead>
                        <tbody id="salesTableBody">
                            <!-- سيتم تحميل البيانات بـ JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // تحميل بيانات المبيعات عند تحميل الصفحة
    loadSalesData();
    
    // تحديث البيانات عند تغيير الفلاتر
    document.querySelectorAll('#salesFilters input, #salesFilters select').forEach(element => {
        element.addEventListener('change', loadSalesData);
    });
    
    // تصدير البيانات
    window.exportData = function(format) {
        const formData = new FormData(document.getElementById('salesFilters'));
        formData.append('format', format);
        const params = new URLSearchParams(formData);
        
        window.location.href = `/api/export-sales-analytics?${params}`;
    };
    
    // إضافة pagination بسيطة
    let currentPage = 1;
    window.loadPage = function(page) {
        currentPage = page;
        loadSalesData();
    };
    
    function addPagination(totalCount) {
        const totalPages = Math.ceil(totalCount / 50);
        let paginationHtml = '';
        
        if (totalPages > 1) {
            paginationHtml = '<div class="pagination-container d-flex justify-content-center my-3"><div class="d-flex gap-2 align-items-center">';
            
            // زر الصفحة الأولى
            const firstDisabled = currentPage === 1;
            const firstClass = firstDisabled ? 'btn-secondary' : 'btn-outline-primary';
            paginationHtml += `<button class="btn btn-sm ${firstClass} d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%;" onclick="loadPage(1)" ${firstDisabled ? 'disabled' : ''}><i class="fas fa-angle-double-right"></i></button>`;
            
            // زر السابق
            const prevDisabled = currentPage === 1;
            const prevClass = prevDisabled ? 'btn-secondary' : 'btn-primary';
            paginationHtml += `<button class="btn btn-sm ${prevClass} d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%;" onclick="loadPage(${currentPage - 1})" ${prevDisabled ? 'disabled' : ''}><i class="fas fa-chevron-right"></i></button>`;
            
            // أرقام الصفحات
            const startPage = Math.max(1, currentPage - 2);
            const endPage = Math.min(totalPages, currentPage + 2);
            
            for (let i = startPage; i <= endPage; i++) {
                const isActive = i === currentPage;
                const btnClass = isActive ? 'btn-warning' : 'btn-outline-primary';
                paginationHtml += `<button class="btn btn-sm ${btnClass} d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%; font-weight: 600;" onclick="loadPage(${i})">${i}</button>`;
            }
            
            // زر التالي
            const nextDisabled = currentPage === totalPages;
            const nextClass = nextDisabled ? 'btn-secondary' : 'btn-primary';
            paginationHtml += `<button class="btn btn-sm ${nextClass} d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%;" onclick="loadPage(${currentPage + 1})" ${nextDisabled ? 'disabled' : ''}><i class="fas fa-chevron-left"></i></button>`;
            
            // زر الصفحة الأخيرة
            const lastDisabled = currentPage === totalPages;
            const lastClass = lastDisabled ? 'btn-secondary' : 'btn-outline-primary';
            paginationHtml += `<button class="btn btn-sm ${lastClass} d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%;" onclick="loadPage(${totalPages})" ${lastDisabled ? 'disabled' : ''}><i class="fas fa-angle-double-left"></i></button>`;
            
            paginationHtml += '</div></div>';
        }
        
        // إضافة pagination في نهاية الصفحة
        document.body.insertAdjacentHTML('beforeend', paginationHtml);
    }
    
    async function loadSalesData() {
        const formData = new FormData(document.getElementById('salesFilters'));
        const params = new URLSearchParams(formData);
        
        try {
            params.append('page', currentPage);
            const response = await fetch(`/api/sales-analytics?${params}`);
            const data = await response.json();
            
            // إزالة pagination القديم
            document.querySelectorAll('.pagination-container').forEach(el => el.remove());
            
            // تحديث الإحصائيات
            document.getElementById('totalSales').textContent = data.stats.total_sales.toLocaleString();
            document.getElementById('totalCustomers').textContent = data.stats.total_customers;
            document.getElementById('totalML').textContent = data.stats.total_ml.toLocaleString();
            document.getElementById('avgSale').textContent = data.stats.avg_sale.toFixed(2);
            
            // تحديث عداد النتائج
            document.getElementById('resultsCount').textContent = `${data.total_count} نتيجة`;
            
            // تحديث الجدول
            const tbody = document.getElementById('salesTableBody');
            if (data.perfumes.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="empty-state-mini">
                                <i class="fas fa-chart-line text-muted mb-3" style="font-size: 3rem;"></i>
                                <h5 class="text-muted">لا توجد مبيعات</h5>
                                <p class="text-muted mb-0">لا توجد مبيعات تطابق الفلاتر المحددة</p>
                            </div>
                        </td>
                    </tr>
                `;
            } else {
                tbody.innerHTML = data.perfumes.map((perfume, index) => `
                    <tr style="transition: all 0.3s ease;" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor='transparent'">
                        <td class="fw-bold text-center" style="padding: 20px 15px; font-size: 0.95rem;">${index + 1}</td>
                        <td style="padding: 20px 15px;">
                            <span class="fw-semibold" style="font-size: 1rem;">${perfume.name}</span>
                        </td>
                        <td class="text-center" style="padding: 20px 15px;">
                            <span class="badge bg-secondary px-2 py-1" style="border-radius: 10px; font-size: 0.8rem;">
                                ${perfume.category_name || 'غير مصنف'}
                            </span>
                        </td>
                        <td class="text-center" style="padding: 20px 15px;">
                            <span class="badge px-3 py-2" style="border-radius: 15px; background: linear-gradient(135deg, #28a745, #20c997); color: white; font-weight: 600; font-size: 0.9rem;">
                                ${perfume.sales_count}
                            </span>
                        </td>
                        <td class="text-center" style="padding: 20px 15px;">
                            <div class="fw-semibold text-primary" style="font-size: 1.1rem;">${perfume.regular_count}</div>
                            <div class="text-muted small">عميل</div>
                        </td>
                        <td class="text-center" style="padding: 20px 15px;">
                            <div class="fw-semibold text-warning" style="font-size: 1.1rem;">${perfume.vip_count}</div>
                            <div class="text-muted small">عميل</div>
                        </td>
                        <td class="text-center" style="padding: 20px 15px;">
                            <div class="fw-semibold text-info" style="font-size: 1.1rem;">${perfume.total_ml.toLocaleString()}</div>
                            <div class="text-muted small">مل</div>
                        </td>
                        <td class="text-center" style="padding: 20px 15px;">
                            <div class="fw-bold text-success" style="font-size: 1.1rem;">${perfume.total_amount.toLocaleString()}</div>
                            <div class="text-muted small">دينار</div>
                        </td>
                    </tr>
                `).join('');
            }
            
            // إضافة pagination
            addPagination(data.total_count);
            
        } catch (error) {
            console.error('Error loading sales data:', error);
        }
    }
});
</script>

<style>
.nav-pills .nav-link {
    border-radius: 25px;
    padding: 12px 24px;
    font-weight: 600;
    transition: all 0.3s;
    border: 2px solid transparent;
}

.nav-pills .nav-link:not(.active) {
    color: #6c757d;
    background: #f8f9fa;
}

.nav-pills .nav-link.active {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-color: transparent;
}

.nav-pills .nav-link:hover:not(.active) {
    background: #e9ecef;
    transform: translateY(-1px);
}

.category-icon {
    transition: transform 0.3s ease;
}

.card-modern:hover .category-icon {
    transform: scale(1.1);
}

.perfume-item {
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0;
}

.perfume-item:last-child {
    border-bottom: none;
}

.empty-state-mini {
    padding: 40px 20px;
}

.perfume-icon {
    transition: transform 0.3s ease;
}

.perfume-icon:hover {
    transform: scale(1.1);
}

#salesTable tbody tr {
    border-bottom: 1px solid #f0f0f0;
}

#salesTable tbody tr:last-child {
    border-bottom: none;
}

#salesTable tbody tr:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transform: translateY(-1px);
}

.dropdown-item:hover {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef) !important;
    transform: translateX(5px) !important;
    color: #495057 !important;
}

.dropdown-menu {
    animation: fadeInUp 0.3s ease;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.pagination .page-link {
    border: none;
    margin: 0 2px;
    transition: all 0.3s ease;
}

.pagination .page-link:hover:not(.active) {
    background: #f8f9fa;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.pagination .page-item.disabled .page-link {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>
@endsection