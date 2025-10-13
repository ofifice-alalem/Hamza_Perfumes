@extends('layouts.app')

@section('title', 'إضافة مستخدم جديد')
@section('page-title', 'إضافة مستخدم جديد')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
            <i class="fas fa-user-plus ml-2 text-blue-600"></i>إضافة مستخدم جديد
        </h2>
        <p class="text-gray-600 dark:text-gray-300">إضافة مستخدم جديد للنظام</p>
    </div>
    <a href="{{ route('users.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-right ml-2"></i>العودة للقائمة
    </a>
</div>

<div class="max-w-2xl mx-auto">
    <div class="card">
        <div class="card-header bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
            <h5 class="text-lg font-bold">
                <i class="fas fa-user-plus ml-2"></i>بيانات المستخدم الجديد
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-1">
                        <label for="username" class="form-label">
                            <i class="fas fa-user ml-2 text-blue-600"></i>اسم المستخدم
                        </label>
                        <input type="text" class="form-input @error('username') error @enderror" id="username" name="username" required>
                        @error('username')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="md:col-span-1">
                        <label for="role" class="form-label">
                            <i class="fas fa-shield-alt ml-2 text-yellow-600"></i>الصلاحية
                        </label>
                        <select class="form-select @error('role') error @enderror" id="role" name="role" required>
                            <option value="">اختر الصلاحية</option>
                            <option value="super-admin">مدير عام</option>
                            <option value="admin">مدير</option>
                            <option value="saler">بائع</option>
                        </select>
                        @error('role')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock ml-2 text-red-600"></i>كلمة المرور
                        </label>
                        <input type="password" class="form-input @error('password') error @enderror" id="password" name="password" required>
                        @error('password')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="submit" class="flex-1 btn-success">
                        <i class="fas fa-save ml-2"></i>حفظ المستخدم
                    </button>
                    <a href="{{ route('users.index') }}" class="flex-1 btn-secondary text-center">
                        <i class="fas fa-times ml-2"></i>إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const usernameInput = document.getElementById('username');
    const form = document.querySelector('form');
    let checkTimeout;
    
    usernameInput.addEventListener('input', function() {
        clearTimeout(checkTimeout);
        const username = this.value.trim();
        
        if (username.length >= 3) {
            checkTimeout = setTimeout(() => checkUsername(username), 500);
        } else {
            resetUsernameValidation();
        }
    });
    
    async function checkUsername(username) {
        try {
            const response = await fetch(`/users/check-username?username=${encodeURIComponent(username)}`);
            const data = await response.json();
            
            if (data.exists) {
                usernameInput.classList.add('error');
                usernameInput.classList.remove('success');
                showUsernameError('اسم المستخدم موجود مسبقاً');
            } else {
                usernameInput.classList.remove('error');
                usernameInput.classList.add('success');
                hideUsernameError();
            }
        } catch (error) {
            console.error('Error checking username:', error);
        }
    }
    
    function showUsernameError(message) {
        let errorDiv = usernameInput.parentNode.querySelector('.form-error');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'form-error';
            usernameInput.parentNode.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
    }
    
    function hideUsernameError() {
        const errorDiv = usernameInput.parentNode.querySelector('.form-error');
        if (errorDiv && !errorDiv.textContent.includes('هذا الحقل مطلوب')) {
            errorDiv.remove();
        }
    }
    
    function resetUsernameValidation() {
        usernameInput.classList.remove('error', 'success');
        hideUsernameError();
    }
    
    form.addEventListener('submit', function(e) {
        if (usernameInput.classList.contains('error')) {
            e.preventDefault();
            usernameInput.focus();
        }
    });
});
</script>
@endsection