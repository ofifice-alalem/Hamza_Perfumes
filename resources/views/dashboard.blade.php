@extends('layouts.app')

@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
            <i class="fas fa-tachometer-alt ml-2 text-blue-600"></i>لوحة التحكم
        </h2>
        <p class="text-gray-600 dark:text-gray-300">نظرة شاملة على العطور والمبيعات</p>
    </div>
</div>

<!-- فلاتر المبيعات -->
<div class="card mb-6">
    <div class="card-body">
        <form id="salesFilters" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
            <div>
                <label class="form-label">من تاريخ</label>
                <input type="date" class="form-input" id="date_from" name="date_from">
            </div>
            <div>
                <label class="form-label">إلى تاريخ</label>
                <input type="date" class="form-input" id="date_to" name="date_to">
            </div>
            <div>
                <label class="form-label">نوع العميل</label>
                <select class="form-select" id="customer_type" name="customer_type">
                    <option value="">الكل</option>
                    <option value="regular">عادي</option>
                    <option value="vip">VIP</option>
                </select>
            </div>
            <div>
                <label class="form-label">الصنف</label>
                <select class="form-select" id="category_id" name="category_id">
                    <option value="">جميع الأصناف</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                    <option value="uncategorized">غير مصنف</option>
                </select>
            </div>
            <div>
                <label class="form-label">البائع</label>
                <select class="form-select" id="user_id" name="user_id">
                    <option value="">جميع البائعين</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">ترتيب حسب</label>
                <select class="form-select" id="sort_by" name="sort_by">
                    <option value="sales_count">عدد المبيعات</option>
                    <option value="total_amount">إجمالي المبلغ</option>
                    <option value="total_ml">إجمالي الكمية (مل)</option>
                </select>
            </div>
        </form>
    </div>
</div>

<!-- إحصائيات سريعة -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6" id="salesStats">
    <div class="stats-card bg-gradient-to-r from-green-500 to-emerald-500">
        <div class="stats-value" id="totalSales">0</div>
        <div class="stats-title">إجمالي المبيعات</div>
        <div class="stats-change">دينار</div>
    </div>
    <div class="stats-card bg-gradient-to-r from-blue-500 to-purple-600">
        <div class="stats-value" id="totalCustomers">0</div>
        <div class="stats-title">عدد العملاء</div>
        <div class="stats-change">عميل</div>
    </div>
    <div class="stats-card bg-gradient-to-r from-orange-500 to-red-500">
        <div class="stats-value" id="totalML">0</div>
        <div class="stats-title">إجمالي الكمية</div>
        <div class="stats-change">مل</div>
    </div>
    <div class="stats-card bg-gradient-to-r from-indigo-500 to-purple-600">
        <div class="stats-value" id="avgSale">0</div>
        <div class="stats-title">متوسط البيع</div>
        <div class="stats-change">دينار</div>
    </div>
</div>

<!-- جدول المبيعات -->
<div class="card">
    <div class="card-header bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
        <div class="flex justify-between items-center">
            <h5 class="text-lg font-bold">
                <i class="fas fa-chart-bar ml-2"></i>تحليل المبيعات حسب العطر
            </h5>
            <div class="flex items-center gap-3">
                <span class="bg-white/20 px-4 py-2 rounded-full text-sm font-medium" id="resultsCount">0 نتيجة</span>
                <div class="relative">
                    <button id="exportButton" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-full text-sm font-medium transition-colors" onclick="toggleExportMenu()">
                        <i class="fas fa-download ml-2"></i>تصدير
                    </button>
                    <div id="exportMenu" class="hidden absolute left-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-lg shadow-lg z-50 border border-gray-200 dark:border-gray-600">
                        <a href="#" onclick="exportData('csv')" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 rounded-t-lg transition-colors">
                            <i class="fas fa-file-csv ml-3 text-green-500"></i>CSV
                        </a>
                        <hr class="border-gray-200 dark:border-gray-600">
                        <a href="#" onclick="exportData('json')" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            <i class="fas fa-file-code ml-3 text-blue-500"></i>JSON
                        </a>
                        <hr class="border-gray-200 dark:border-gray-600">
                        <a href="#" onclick="exportData('xml')" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 rounded-b-lg transition-colors">
                            <i class="fas fa-file-code ml-3 text-yellow-500"></i>XML
                        </a>
                        <hr class="border-gray-200 dark:border-gray-600">
                        <a href="#" onclick="exportData('excel')" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 rounded-b-lg transition-colors">
                            <i class="fas fa-file-excel ml-3 text-green-600"></i>Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="overflow-x-auto">
            <table class="table" id="salesTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-right text-sm font-medium">#</th>
                        <th class="text-right text-sm font-medium">العطر</th>
                        <th class="text-right text-sm font-medium">الصنف</th>
                        <th class="text-right text-sm font-medium">عدد المبيعات</th>
                        <th class="text-right text-sm font-medium">الزبائن العاديين</th>
                        <th class="text-right text-sm font-medium">VIP</th>
                        <th class="text-right text-sm font-medium">إجمالي الكمية</th>
                        <th class="text-right text-sm font-medium">إجمالي المبلغ</th>
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
    window.exportData = function(format, event) {
        event?.preventDefault();
        
        const formData = new FormData(document.getElementById('salesFilters'));
        formData.append('format', format);
        const params = new URLSearchParams(formData);
        
        // إظهار رسالة تحميل
        const button = document.getElementById('exportButton');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin ml-2"></i>جاري التصدير...';
        button.disabled = true;
        
        // محاولة التصدير
        try {
            window.location.href = `/api/export-sales-analytics?${params}`;
        } catch (error) {
            console.error('خطأ في التصدير:', error);
            alert('حدث خطأ أثناء التصدير');
        }
        
        // إعادة تعيين الزر بعد ثانيتين
        setTimeout(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        }, 2000);
        
        // إغلاق القائمة
        document.getElementById('exportMenu').classList.add('hidden');
    };
    
    // تبديل قائمة التصدير
    window.toggleExportMenu = function(event) {
        event?.stopPropagation();
        const menu = document.getElementById('exportMenu');
        const isHidden = menu.classList.contains('hidden');
        
        if (isHidden) {
            menu.classList.remove('hidden');
            menu.style.animation = 'fadeInDown 0.2s ease';
        } else {
            menu.classList.add('hidden');
        }
    };
    
    // إغلاق قائمة التصدير عند النقر خارجها
    document.addEventListener('click', function(event) {
        const menu = document.getElementById('exportMenu');
        const button = document.getElementById('exportButton');
        
        if (!menu.contains(event.target) && !button.contains(event.target)) {
            menu.classList.add('hidden');
        }
    });
    
    // إغلاق القائمة بمفتاح Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            document.getElementById('exportMenu').classList.add('hidden');
        }
    });
    
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
            paginationHtml = '<div class="pagination-container flex justify-center my-6"><div class="flex gap-2 items-center">';
            
            // زر الصفحة الأولى
            const firstDisabled = currentPage === 1;
            const firstClass = firstDisabled ? 'bg-gray-300 cursor-not-allowed' : 'bg-blue-500 hover:bg-blue-600 text-white';
            paginationHtml += `<button class="w-10 h-10 rounded-full flex items-center justify-center ${firstClass} transition-colors" onclick="loadPage(1)" ${firstDisabled ? 'disabled' : ''}><i class="fas fa-angle-double-right"></i></button>`;
            
            // زر السابق
            const prevDisabled = currentPage === 1;
            const prevClass = prevDisabled ? 'bg-gray-300 cursor-not-allowed' : 'bg-blue-500 hover:bg-blue-600 text-white';
            paginationHtml += `<button class="w-10 h-10 rounded-full flex items-center justify-center ${prevClass} transition-colors" onclick="loadPage(${currentPage - 1})" ${prevDisabled ? 'disabled' : ''}><i class="fas fa-chevron-right"></i></button>`;
            
            // أرقام الصفحات
            const startPage = Math.max(1, currentPage - 2);
            const endPage = Math.min(totalPages, currentPage + 2);
            
            for (let i = startPage; i <= endPage; i++) {
                const isActive = i === currentPage;
                const btnClass = isActive ? 'bg-blue-600 text-white' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50';
                paginationHtml += `<button class="w-10 h-10 rounded-full flex items-center justify-center ${btnClass} transition-colors font-semibold" onclick="loadPage(${i})">${i}</button>`;
            }
            
            // زر التالي
            const nextDisabled = currentPage === totalPages;
            const nextClass = nextDisabled ? 'bg-gray-300 cursor-not-allowed' : 'bg-blue-500 hover:bg-blue-600 text-white';
            paginationHtml += `<button class="w-10 h-10 rounded-full flex items-center justify-center ${nextClass} transition-colors" onclick="loadPage(${currentPage + 1})" ${nextDisabled ? 'disabled' : ''}><i class="fas fa-chevron-left"></i></button>`;
            
            // زر الصفحة الأخيرة
            const lastDisabled = currentPage === totalPages;
            const lastClass = lastDisabled ? 'bg-gray-300 cursor-not-allowed' : 'bg-blue-500 hover:bg-blue-600 text-white';
            paginationHtml += `<button class="w-10 h-10 rounded-full flex items-center justify-center ${lastClass} transition-colors" onclick="loadPage(${totalPages})" ${lastDisabled ? 'disabled' : ''}><i class="fas fa-angle-double-left"></i></button>`;
            
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
                        <td colspan="8" class="text-center py-12">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-chart-line text-gray-400 text-5xl mb-4"></i>
                                <h5 class="text-gray-500 text-lg font-semibold mb-2">لا توجد مبيعات</h5>
                                <p class="text-gray-400">لا توجد مبيعات تطابق الفلاتر المحددة</p>
                            </div>
                        </td>
                    </tr>
                `;
            } else {
                tbody.innerHTML = data.perfumes.map((perfume, index) => `
                    <tr class="hover:bg-gray-50 transition-colors border-b border-gray-100">
                        <td class="text-right text-sm font-medium py-3">${index + 1}</td>
                        <td class="text-right text-sm py-3">
                            <span class="font-medium text-gray-900">${perfume.name}</span>
                        </td>
                        <td class="text-right text-sm py-3">
                            <span class="inline-block bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">
                                ${perfume.category_name || 'غير مصنف'}
                            </span>
                        </td>
                        <td class="text-right text-sm py-3">
                            <span class="inline-block bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">
                                ${perfume.sales_count}
                            </span>
                        </td>
                        <td class="text-right text-sm py-3">
                            <div class="font-medium text-blue-600">${perfume.regular_count}</div>
                            <div class="text-gray-400 text-xs">عميل</div>
                        </td>
                        <td class="text-right text-sm py-3">
                            <div class="font-medium text-yellow-600">${perfume.vip_count}</div>
                            <div class="text-gray-400 text-xs">عميل</div>
                        </td>
                        <td class="text-right text-sm py-3">
                            <div class="font-medium text-cyan-600">${perfume.total_ml.toLocaleString()}</div>
                            <div class="text-gray-400 text-xs">مل</div>
                        </td>
                        <td class="text-right text-sm py-3">
                            <div class="font-semibold text-green-600">${perfume.total_amount.toLocaleString()}</div>
                            <div class="text-gray-400 text-xs">دينار</div>
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
/* تحسينات إضافية للتصميم */
.stats-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

#exportMenu {
    animation: fadeInDown 0.2s ease;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

#exportMenu a:hover {
    transform: translateX(2px);
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.pagination-container {
    animation: fadeInUp 0.3s ease;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* تحسين الجدول للشاشات الصغيرة */
@media (max-width: 768px) {
    .table th,
    .table td {
        padding: 6px 8px;
        font-size: 0.8rem;
    }
    
    .stats-card {
        margin-bottom: 1rem;
    }
}

/* تحسين مظهر الجدول */
.table {
    border-collapse: separate;
    border-spacing: 0;
}

.table th {
    background-color: #f8fafc;
    border-bottom: 2px solid #e2e8f0;
    padding: 12px 16px;
    font-weight: 500;
    color: #475569;
}

.table td {
    padding: 8px 16px;
    vertical-align: middle;
}
</style>
@endsection