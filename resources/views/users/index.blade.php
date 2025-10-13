@extends('layouts.app')

@section('title', 'المستخدمين')
@section('page-title', 'المستخدمين')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
            <i class="fas fa-users ml-2 text-blue-600"></i>المستخدمين
        </h2>
        <p class="text-gray-600 dark:text-gray-300">إدارة مستخدمي النظام</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn-primary">
        <i class="fas fa-plus ml-2"></i>إضافة مستخدم جديد
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="overflow-x-auto">
            <table class="table">
                <thead class="bg-gray-50 dark:bg-gray-600">
                    <tr>
                        <th class="text-center">#</th>
                        <th>الاسم</th>
                        <th>اسم المستخدم</th>
                        <th class="text-sm">الصلاحية</th>
                        <th class="text-sm">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        <td class="text-center font-bold">{{ $user->id }}</td>
                        <td class="font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</td>
                        <td class="text-gray-700 dark:text-gray-300">{{ $user->username }}</td>
                        <td>
                            @if($user->role === 'super-admin')
                                <span class="inline-block bg-red-500 text-white px-2 py-1 rounded-full text-xs font-medium">مدير عام</span>
                            @elseif($user->role === 'admin')
                                <span class="inline-block bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-medium">مدير</span>
                            @else
                                <span class="inline-block bg-blue-500 text-white px-2 py-1 rounded-full text-xs font-medium">بائع</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex gap-2">
                                <a href="{{ route('users.edit', $user) }}" class="w-8 h-8 bg-yellow-500 hover:bg-yellow-600 text-white rounded-full flex items-center justify-center transition-colors">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                <button type="button" class="w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition-colors" onclick="openDeleteModal({{ $user->id }})">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
                        <span class="text-yellow-700 dark:text-yellow-300">سيتم حذف المستخدم "<span id="deleteUserName" class="font-bold"></span>" نهائياً من النظام.</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                <h6 class="font-bold text-gray-900 dark:text-white mb-3">تفاصيل المستخدم:</h6>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">اسم المستخدم:</span>
                        <span id="deleteUserUsername" class="font-semibold text-gray-900 dark:text-white mr-2"></span>
                    </div>
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">الصلاحية:</span>
                        <span id="deleteUserRole" class="font-semibold text-gray-900 dark:text-white mr-2"></span>
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
function openDeleteModal(userId) {
    const users = @json($users);
    const user = users.find(u => u.id === userId);
    
    if (user) {
        document.getElementById('deleteUserName').textContent = user.name;
        document.getElementById('deleteUserUsername').textContent = user.username;
        
        let roleText = '';
        if (user.role === 'super-admin') roleText = 'مدير عام';
        else if (user.role === 'admin') roleText = 'مدير';
        else roleText = 'بائع';
        
        document.getElementById('deleteUserRole').textContent = roleText;
        document.getElementById('deleteForm').action = `/users/${userId}`;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// إغلاق المودال عند النقر خارجه
 document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// إغلاق بمفتاح Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endsection