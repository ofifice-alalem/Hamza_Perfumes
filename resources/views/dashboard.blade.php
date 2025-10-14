@extends('layouts.app')

@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2 flex items-center">
            <img src="{{ asset('images/dashboard.png') }}" alt="لوحة التحكم" class="w-7 h-7 mr-3 object-contain">
            <span>لوحة التحكم</span>
        </h2>
        <p class="text-gray-600 dark:text-gray-300">نظرة شاملة على العطور والمبيعات</p>
    </div>
</div>

<!-- فلاتر المبيعات -->
<div class="card mb-6">
    <div class="card-body">
        <form id="salesFilters">
            <!-- الصف الأول -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
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
            </div>
            
            <!-- الصف الثاني -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                    <label class="form-label">طريقة الدفع</label>
                    <select class="form-select" id="payment_method" name="payment_method">
                        <option value="">الكل</option>
                        <option value="cash">كاش</option>
                        <option value="card">بطاقة</option>
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
            </div>
        </form>
    </div>
</div>

<!-- إحصائيات سريعة -->
<div class="flex gap-6 mb-6 w-full" id="salesStats">
    <div class="stats-card bg-gradient-to-r from-blue-500 to-purple-600 flex-1">
        <div class="stats-value" id="totalCustomers">0</div>
        <div class="stats-title">عدد العملاء</div>
        <div class="stats-change">عميل</div>
    </div>
    <div class="stats-card bg-gradient-to-r from-orange-500 to-red-500 flex-1">
        <div class="stats-value" id="totalML">0</div>
        <div class="stats-title">إجمالي الكمية</div>
        <div class="stats-change">مل</div>
    </div>
    <div class="stats-card bg-gradient-to-r from-green-600 to-emerald-600 flex-1">
        <div class="stats-value" id="totalCash">0</div>
        <div class="stats-title"> إجمالي الكاش</div>
        <div class="stats-change">دينار</div>
    </div>
    <div class="stats-card bg-gradient-to-r from-purple-500 to-indigo-600 flex-1">
        <div class="stats-value" id="totalCard">0</div>
        <div class="stats-title"> إجمالي البطاقة</div>
        <div class="stats-change">دينار</div>
    </div>
    <div class="stats-card bg-gradient-to-r from-green-500 to-emerald-500 flex-1">
        <div class="stats-value" id="totalSales">0</div>
        <div class="stats-title">الاجمالي</div>
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
                        <th class="text-right text-sm font-medium">الحجم</th>
                        <th class="text-right text-sm font-medium">نوع العميل</th>
                        <th class="text-right text-sm font-medium">طريقة الدفع</th>
                        <th class="text-right text-sm font-medium">البائع</th>
                        <th class="text-right text-sm font-medium">السعر</th>
                        <th class="text-right text-sm font-medium">التاريخ</th>
                        <th class="text-right text-sm font-medium">الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="salesTableBody">
                    <!-- سيتم تحميل البيانات بـ JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Sale Modal -->
<div id="editSaleModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">تعديل البيع</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editSaleForm">
                <input type="hidden" id="editSaleId">
                <input type="hidden" id="editPerfumeId">
                <div class="mb-4">
                    <label class="form-label">الحجم</label>
                    <select id="editSizeId" class="form-select">
                        <option value="">جاري تحميل الأحجام...</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="form-label">نوع العميل</label>
                    <select id="editCustomerType" class="form-select">
                        <option value="regular">عادي</option>
                        <option value="vip">VIP</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="form-label">السعر</label>
                    <div class="flex items-center h-12 px-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg" id="editPriceDisplay">
                        <i class="fas fa-info-circle ml-2 text-blue-600"></i>
                        <span class="text-blue-800 dark:text-blue-200 text-sm">اختر الحجم ونوع العميل</span>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label">طريقة الدفع</label>
                    <div class="flex gap-2 mt-2">
                        <label class="flex items-center cursor-pointer flex-1">
                            <input type="radio" name="edit_payment_method" value="cash" class="sr-only">
                            <div class="payment-option w-full" data-value="cash">
                                <i class="fas fa-money-bill-wave ml-2"></i>كاش
                            </div>
                        </label>
                        <label class="flex items-center cursor-pointer flex-1">
                            <input type="radio" name="edit_payment_method" value="card" class="sr-only">
                            <div class="payment-option w-full" data-value="card">
                                <i class="fas fa-credit-card ml-2"></i>بطاقة
                            </div>
                        </label>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="button" onclick="updateSale()" class="btn-success flex-1">
                        <i class="fas fa-save ml-2"></i>حفظ
                    </button>
                    <button type="button" onclick="closeEditModal()" class="btn-secondary flex-1">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Sale Modal -->
<div id="deleteSaleModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full animate-scale-in">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center ml-4">
                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">تأكيد الحذف</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">هذا الإجراء لا يمكن التراجع عنه</p>
                </div>
            </div>
            
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-4">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-yellow-600 dark:text-yellow-400 ml-3 mt-0.5"></i>
                    <div class="text-sm">
                        <strong class="text-yellow-800 dark:text-yellow-200">تحذير:</strong>
                        <span class="text-yellow-700 dark:text-yellow-300">سيتم حذف عملية البيع رقم "<span id="deleteSaleNumber" class="font-bold"></span>" نهائياً.</span>
                    </div>
                </div>
            </div>
            
            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteSaleModal()" class="flex-1 btn-secondary">
                    <i class="fas fa-times ml-2"></i>إلغاء
                </button>
                <button type="button" onclick="confirmDeleteSale()" class="flex-1 btn-danger">
                    <i class="fas fa-trash ml-2"></i>حذف نهائياً
                </button>
            </div>
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
            document.getElementById('totalCash').textContent = data.stats.total_cash.toLocaleString();
            document.getElementById('totalCard').textContent = data.stats.total_card.toLocaleString();
            
            // تحديث عداد النتائج
            document.getElementById('resultsCount').textContent = `${data.total_count} مبيعة`;
            
            // تحديث الجدول
            const tbody = document.getElementById('salesTableBody');
            if (data.sales.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="9" class="text-center py-12">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-shopping-cart text-gray-400 text-5xl mb-4"></i>
                                <h5 class="text-gray-500 text-lg font-semibold mb-2">لا توجد مبيعات</h5>
                                <p class="text-gray-400">لا توجد مبيعات تطابق الفلاتر المحددة</p>
                            </div>
                        </td>
                    </tr>
                `;
            } else {
                tbody.innerHTML = data.sales.map((sale, index) => `
                    <tr class="hover:bg-gray-50 transition-colors border-b border-gray-100">
                        <td class="text-right text-sm font-medium py-3">${sale.id}</td>
                        <td class="text-right text-sm py-3">
                            <span class="font-medium text-gray-900">${sale.perfume_name}</span>
                        </td>
                        <td class="text-right text-sm py-3">
                            ${sale.size_label ? `<span class="px-2 py-1 text-xs rounded-full ${sale.is_full_bottle ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800'}">
                                ${sale.is_full_bottle ? '<i class="fas fa-wine-bottle ml-1"></i>' : ''}${sale.size_label}${sale.is_full_bottle ? ' (عبوة كاملة)' : ''}
                            </span>` : '<span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800"><i class="fas fa-wine-bottle ml-1"></i>عبوة كاملة</span>'}
                        </td>
                        <td class="text-right text-sm py-3">
                            <span class="px-2 py-1 text-xs rounded-full ${sale.customer_type === 'vip' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800'}">
                                <i class="fas ${sale.customer_type === 'vip' ? 'fa-crown' : 'fa-user'} ml-1"></i>
                                ${sale.customer_type === 'vip' ? 'VIP' : 'عادي'}
                            </span>
                        </td>
                        <td class="text-right text-sm py-3">
                            <span class="px-2 py-1 text-xs rounded-full ${sale.payment_method === 'card' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800'}">
                                <i class="fas ${sale.payment_method === 'card' ? 'fa-credit-card' : 'fa-money-bill-wave'} ml-1"></i>
                                ${sale.payment_method === 'card' ? 'بطاقة' : 'كاش'}
                            </span>
                        </td>
                        <td class="text-right text-sm py-3">
                            <span class="px-2 py-1 text-xs rounded-full bg-indigo-100 text-indigo-800">
                                ${sale.user_name || 'غير محدد'}
                            </span>
                        </td>
                        <td class="text-right text-sm py-3">
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 font-semibold">
                                ${parseFloat(sale.price).toLocaleString()} دينار
                            </span>
                        </td>
                        <td class="text-right text-sm py-3 text-gray-500">
                            ${new Date(sale.created_at).toLocaleDateString('en-GB')} ${new Date(sale.created_at).toLocaleTimeString('en-GB', {hour: '2-digit', minute: '2-digit'})}
                        </td>
                        <td class="text-right text-sm py-3">
                            <div class="flex gap-2 justify-center">
                                <button onclick="editSale(${sale.id}, ${sale.perfume_id}, ${sale.size_id}, '${sale.customer_type}', '${sale.payment_method}', ${sale.is_full_bottle})" class="w-8 h-8 bg-yellow-500 hover:bg-yellow-600 text-white rounded-full flex items-center justify-center transition-colors">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                <button onclick="deleteSale(${sale.id})" class="w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition-colors">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </div>
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

// Toast notification function
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white transform transition-all duration-300 translate-x-full ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);
    
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

// Sale Edit Functions
function editSale(id, perfumeId, sizeId, customerType, paymentMethod, isFullBottle) {
    document.getElementById('editSaleId').value = id;
    document.getElementById('editPerfumeId').value = perfumeId;
    document.getElementById('editCustomerType').value = customerType;
    
    // Set payment method
    document.querySelector(`input[name="edit_payment_method"][value="${paymentMethod}"]`).checked = true;
    updateEditPaymentOptions();
    
    // Load available sizes
    loadEditSizes(perfumeId, sizeId, isFullBottle);
    
    document.getElementById('editSaleModal').classList.remove('hidden');
}

function loadEditSizes(perfumeId, currentSizeId, isFullBottle) {
    const sizeSelect = document.getElementById('editSizeId');
    sizeSelect.innerHTML = '<option value="">جاري تحميل الأحجام...</option>';
    
    fetch(`/api/get-available-sizes/${perfumeId}`)
        .then(response => response.json())
        .then(data => {
            sizeSelect.innerHTML = '';
            
            if (data && data.length > 0) {
                data.forEach(size => {
                    const option = document.createElement('option');
                    option.value = size.id;
                    option.textContent = size.label;
                    
                    if ((isFullBottle && size.id === `bottle_${currentSizeId}`) || 
                        (!isFullBottle && size.id == currentSizeId)) {
                        option.selected = true;
                    }
                    
                    sizeSelect.appendChild(option);
                });
            } else {
                sizeSelect.innerHTML = '<option value="">لا توجد أحجام متاحة</option>';
            }
        })
        .finally(() => {
            setTimeout(updateEditPrice, 100);
        });
    
    document.getElementById('editSizeId').addEventListener('change', updateEditPrice);
    document.getElementById('editCustomerType').addEventListener('change', updateEditPrice);
}

function updateEditPrice() {
    const perfumeId = document.getElementById('editPerfumeId').value;
    const sizeId = document.getElementById('editSizeId').value;
    const customerType = document.getElementById('editCustomerType').value;
    const priceDisplay = document.getElementById('editPriceDisplay');

    if (perfumeId && sizeId && customerType) {
        fetch(`/api/get-price?perfume_id=${perfumeId}&size_id=${sizeId}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    priceDisplay.innerHTML = '<i class="fas fa-exclamation-triangle ml-2 text-red-600"></i><span class="text-red-600">السعر غير محدد لهذا الحجم</span>';
                } else {
                    const price = customerType === 'vip' ? data.vip : data.regular;
                    priceDisplay.innerHTML = `<i class="fas fa-tag ml-2 text-green-600"></i><span class="text-green-800 dark:text-green-200 font-semibold">${price} دينار</span>`;
                }
            })
            .catch(error => {
                priceDisplay.innerHTML = '<i class="fas fa-exclamation-triangle ml-2 text-red-600"></i><span class="text-red-600">خطأ في جلب السعر</span>';
            });
    }
}

function closeEditModal() {
    document.getElementById('editSaleModal').classList.add('hidden');
}

function updateEditPaymentOptions() {
    const paymentOptions = document.querySelectorAll('#editSaleModal .payment-option');
    const checkedInput = document.querySelector('input[name="edit_payment_method"]:checked');
    
    paymentOptions.forEach(option => {
        option.classList.remove('active');
        if (option.dataset.value === checkedInput?.value) {
            option.classList.add('active');
        }
    });
}

function updateSale() {
    const id = document.getElementById('editSaleId').value;
    const sizeId = document.getElementById('editSizeId').value;
    const customerType = document.getElementById('editCustomerType').value;
    const paymentMethod = document.querySelector('input[name="edit_payment_method"]:checked').value;
    
    if (!sizeId) {
        alert('يرجى اختيار الحجم');
        return;
    }
    
    fetch(`/sales/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            size_id: sizeId,
            customer_type: customerType,
            payment_method: paymentMethod
        })
    })
    .then(response => response.text())
    .then(text => {
        try {
            // فك ترميز HTML entities
            const decodedText = text.replace(/&quot;/g, '"').replace(/&amp;/g, '&');
            const data = JSON.parse(decodedText);
            if (data.success) {
                closeEditModal();
                showToast('تم تعديل البيع بنجاح', 'success');
                setTimeout(() => loadSalesData(), 100);
                return;
            } else {
                alert(data.message || 'خطأ في تحديث البيع');
                return;
            }
        } catch (e) {
            // إذا فشل في الparse لكن العملية نجحت
            if (text.includes('success') || text.includes('تم تحديث')) {
                closeEditModal();
                showToast('تم تعديل البيع بنجاح', 'success');
                setTimeout(() => loadSalesData(), 100);
                return;
            }
        }
    })
    .catch(error => {
        // في حالة فشل الشبكة، جرب إعادة تحميل البيانات
        closeEditModal();
        setTimeout(() => loadSalesData(), 100);
    });
}

// Sale Delete Functions
let saleToDelete = null;

function deleteSale(id) {
    saleToDelete = id;
    document.getElementById('deleteSaleNumber').textContent = id;
    document.getElementById('deleteSaleModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteSaleModal() {
    document.getElementById('deleteSaleModal').classList.add('hidden');
    document.body.style.overflow = '';
    saleToDelete = null;
}

function confirmDeleteSale() {
    if (saleToDelete) {
        fetch(`/sales/${saleToDelete}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadSalesData();
            } else {
                alert('خطأ في حذف البيع');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('خطأ في حذف البيع');
        })
        .finally(() => {
            closeDeleteSaleModal();
        });
    }
}

// Add click handlers for edit modal payment options
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#editSaleModal .payment-option').forEach(option => {
        option.addEventListener('click', function() {
            const value = this.dataset.value;
            const input = document.querySelector(`input[name="edit_payment_method"][value="${value}"]`);
            input.checked = true;
            updateEditPaymentOptions();
        });
    });
});

// إغلاق modals عند النقر خارجها
document.getElementById('editSaleModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});

document.getElementById('deleteSaleModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteSaleModal();
});

// إغلاق modals بمفتاح Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeEditModal();
        closeDeleteSaleModal();
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

.payment-option {
    padding: 8px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 0.5rem;
    background: white;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.875rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
}

.dark .payment-option {
    background: #374151;
    border-color: #4b5563;
    color: #d1d5db;
}

.payment-option:hover {
    border-color: #3b82f6;
    background: #eff6ff;
}

.dark .payment-option:hover {
    border-color: #3b82f6;
    background: #1e3a8a;
}

.payment-option.active {
    border-color: #3b82f6;
    background: #3b82f6;
    color: white;
}

.dark .payment-option.active {
    border-color: #3b82f6;
    background: #3b82f6;
    color: white;
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