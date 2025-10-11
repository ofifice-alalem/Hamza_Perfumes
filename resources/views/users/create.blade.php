@extends('layouts.app')

@section('title', 'إضافة مستخدم جديد')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="fas fa-user-plus me-2 text-primary"></i>إضافة مستخدم جديد</h2>
        <p class="text-muted mb-0">إضافة مستخدم جديد للنظام</p>
    </div>
    <a href="{{ route('users.index') }}" class="btn btn-secondary btn-modern">
        <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-modern">
            <div class="card-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 15px 15px 0 0; padding: 20px;">
                <h5 class="mb-0 fw-bold"><i class="fas fa-user-plus me-2"></i>بيانات المستخدم الجديد</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="username" class="form-label fw-semibold">
                                <i class="fas fa-user me-2 text-primary"></i>اسم المستخدم
                            </label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" required style="border-radius: 15px; padding: 12px 20px; border: 2px solid #e9ecef; transition: all 0.3s;">
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="role" class="form-label fw-semibold">
                                <i class="fas fa-shield-alt me-2 text-warning"></i>الصلاحية
                            </label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required style="border-radius: 15px; padding: 12px 20px; border: 2px solid #e9ecef; transition: all 0.3s;">
                                <option value="">اختر الصلاحية</option>
                                <option value="super-admin">مدير عام</option>
                                <option value="admin">مدير</option>
                                <option value="saler">بائع</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="password" class="form-label fw-semibold">
                                <i class="fas fa-lock me-2 text-danger"></i>كلمة المرور
                            </label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required style="border-radius: 15px; padding: 12px 20px; border: 2px solid #e9ecef; transition: all 0.3s;">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-5">
                        <button type="submit" class="btn btn-success btn-modern flex-fill" style="border-radius: 15px; padding: 12px; font-weight: 600; background: linear-gradient(135deg, #28a745, #20c997);">
                            <i class="fas fa-save me-2"></i>حفظ المستخدم
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-modern flex-fill" style="border-radius: 15px; padding: 12px; font-weight: 600;">
                            <i class="fas fa-times me-2"></i>إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.form-control:focus, .form-select:focus {
    border-color: #667eea !important;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
}
</style>

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
                usernameInput.classList.add('is-invalid');
                showUsernameError('اسم المستخدم موجود مسبقاً');
            } else {
                usernameInput.classList.remove('is-invalid');
                usernameInput.classList.add('is-valid');
                hideUsernameError();
            }
        } catch (error) {
            console.error('Error checking username:', error);
        }
    }
    
    function showUsernameError(message) {
        let errorDiv = usernameInput.parentNode.querySelector('.invalid-feedback');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            usernameInput.parentNode.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
    }
    
    function hideUsernameError() {
        const errorDiv = usernameInput.parentNode.querySelector('.invalid-feedback');
        if (errorDiv && !errorDiv.textContent.includes('هذا الحقل مطلوب')) {
            errorDiv.remove();
        }
    }
    
    function resetUsernameValidation() {
        usernameInput.classList.remove('is-invalid', 'is-valid');
        hideUsernameError();
    }
    
    form.addEventListener('submit', function(e) {
        if (usernameInput.classList.contains('is-invalid')) {
            e.preventDefault();
            usernameInput.focus();
        }
    });
});
</script>
@endsection