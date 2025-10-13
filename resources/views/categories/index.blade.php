@extends('layouts.app')

@section('title', 'التصنيفات - حمزة عطور')
@section('page-title', 'إدارة التصنيفات')

@section('content')
<div class="space-y-6">
    <!-- Add Category Button -->
    <div class="flex justify-end">
        <a href="{{ route('categories.create') }}" class="btn-primary">
            <i class="fas fa-plus ml-2"></i>
            إضافة تصنيف جديد
        </a>
    </div>

    <!-- Categories Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">قائمة التصنيفات</h3>
        </div>
        <div class="card-body p-0">
            @if($categories->count() > 0)
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>التصنيف</th>
                                <th>عدد العطور</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td class="font-medium text-gray-900 dark:text-white">{{ $category->name }}</td>
                                    <td>{{ $category->perfumes_count ?? 0 }}</td>
                                    <td class="text-sm text-gray-500 dark:text-gray-400">{{ $category->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="flex gap-2">
                                            <a href="{{ route('categories.edit', $category) }}" class="w-8 h-8 bg-yellow-500 hover:bg-yellow-600 text-white rounded-full flex items-center justify-center transition-colors">
                                                <i class="fas fa-edit text-xs"></i>
                                            </a>
                                            <button type="button" class="w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition-colors" onclick="openDeleteModal({{ $category->id }})">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-tags text-6xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">لا توجد تصنيفات</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">ابدأ بإضافة أول تصنيف</p>
                    <a href="{{ route('categories.create') }}" class="btn-primary">
                        <i class="fas fa-plus ml-2"></i>
                        إضافة تصنيف جديد
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
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
                        <span class="text-yellow-700 dark:text-yellow-300">سيتم حذف التصنيف "<span id="deleteCategoryName" class="font-bold"></span>" نهائياً من النظام.</span>
                    </div>
                </div>
            </div>
            
            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 btn-secondary">
                    <i class="fas fa-times ml-2"></i>إلغاء
                </button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full btn-danger">
                        <i class="fas fa-trash ml-2"></i>حذف نهائياً
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openDeleteModal(categoryId) {
    const categories = @json($categories);
    const category = categories.find(c => c.id === categoryId);
    
    if (category) {
        document.getElementById('deleteCategoryName').textContent = category.name;
        document.getElementById('deleteForm').action = `/categories/${categoryId}`;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.body.style.overflow = '';
}

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeDeleteModal();
});
</script>
@endsection