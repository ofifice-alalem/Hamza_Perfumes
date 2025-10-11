<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - نظام إدارة العطور</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <div class="container-fluid d-flex align-items-center justify-content-center min-vh-100">
        <div class="row w-100 justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-lg border-0" style="border-radius: 20px;">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <div class="login-icon mb-3" style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                <i class="fas fa-spray-can text-white" style="font-size: 2rem;"></i>
                            </div>
                            <h3 class="fw-bold text-dark">تسجيل الدخول</h3>
                            <p class="text-muted">نظام إدارة العطور</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger" style="border-radius: 15px;">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="username" class="form-label fw-semibold">اسم المستخدم</label>
                                <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required style="border-radius: 12px; border: 2px solid #e9ecef; padding: 12px;">
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">كلمة المرور</label>
                                <input type="password" class="form-control" id="password" name="password" required style="border-radius: 12px; border: 2px solid #e9ecef; padding: 12px;">
                            </div>

                            <button type="submit" class="btn w-100 text-white fw-bold py-3" style="background: linear-gradient(135deg, #667eea, #764ba2); border: none; border-radius: 15px;">
                                <i class="fas fa-sign-in-alt me-2"></i>دخول
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>