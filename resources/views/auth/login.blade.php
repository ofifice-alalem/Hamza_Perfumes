<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - حمزة عطور</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            opacity: 0.1;
            z-index: -1;
        }
        
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            border: 1px solid #f0f0f0;
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
            transition: all 0.3s ease;
            display: flex;
            min-height: 600px;
        }
        
        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(0,0,0,0.15);
        }
        
        .image-section {
            flex: 1;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            overflow: hidden;
            background: #1a1a2e;
        }
        
        .image-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('{{ asset('images/login-1.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: brightness(0.4) contrast(1.2);
            z-index: 1;
            transition: all 0.3s ease;
        }
        
        .image-section:hover::before {
            transform: scale(1.05);
            filter: brightness(0.5) contrast(1.3);
        }
        
        .image-section::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at center, rgba(102, 126, 234, 0.3) 0%, rgba(118, 75, 162, 0.7) 100%);
            z-index: 2;
        }
        
        .form-section {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .login-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
        }
        
        .form-floating {
            position: relative;
            margin-bottom: 25px;
        }
        
        .form-floating .form-control {
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 20px 15px 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #fafbfc;
        }
        
        .form-floating .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: white;
        }
        
        .form-floating label {
            padding: 15px;
            color: #6c757d;
            font-weight: 500;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 15px;
            padding: 15px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .alert-modern {
            border: none;
            border-radius: 15px;
            padding: 15px 20px;
            margin-bottom: 25px;
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
        }
        
        .brand-text {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .brand-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            font-weight: 400;
        }
        
        .welcome-content {
            z-index: 3;
            position: relative;
            text-align: center;
            animation: fadeInUp 1s ease-out;
        }
        
        .welcome-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, rgba(255,255,255,0.25), rgba(255,255,255,0.1));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 40px;
            backdrop-filter: blur(20px);
            border: 3px solid rgba(255,255,255,0.4);
            box-shadow: 0 8px 32px rgba(0,0,0,0.3), inset 0 1px 0 rgba(255,255,255,0.2);
            transition: all 0.3s ease;
            position: relative;
        }
        
        .welcome-icon::before {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.3), rgba(118, 75, 162, 0.3));
            z-index: -1;
            opacity: 0;
            transition: all 0.3s ease;
        }
        
        .welcome-icon:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 40px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.3);
        }
        
        .welcome-icon:hover::before {
            opacity: 1;
        }
        
        .welcome-text {
            color: white;
            font-size: 1.6rem;
            font-weight: 700;
            text-shadow: 0 4px 20px rgba(0,0,0,0.5);
            max-width: 350px;
            line-height: 1.4;
            margin-bottom: 20px;
            letter-spacing: 0.5px;
        }
        
        .welcome-subtitle {
            color: rgba(255,255,255,0.8);
            font-size: 1rem;
            font-weight: 400;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
            opacity: 0.9;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 2;
            pointer-events: none;
        }
        
        .floating-element {
            position: absolute;
            width: 6px;
            height: 6px;
            background: rgba(255,255,255,0.3);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        
        .floating-element:nth-child(1) { top: 20%; left: 10%; animation-delay: 0s; }
        .floating-element:nth-child(2) { top: 60%; left: 20%; animation-delay: 2s; }
        .floating-element:nth-child(3) { top: 30%; right: 15%; animation-delay: 4s; }
        .floating-element:nth-child(4) { bottom: 40%; right: 25%; animation-delay: 1s; }
        .floating-element:nth-child(5) { bottom: 20%; left: 30%; animation-delay: 3s; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) scale(1); opacity: 0.3; }
            50% { transform: translateY(-20px) scale(1.2); opacity: 0.8; }
        }
        
        @media (max-width: 768px) {
            .login-card {
                flex-direction: column;
                max-width: 450px;
            }
            
            .image-section {
                min-height: 200px;
                flex: none;
            }
            
            .form-section {
                padding: 40px 30px;
            }
            

        }
        
        @media (max-width: 576px) {
            .form-section {
                padding: 30px 25px;
            }
            
            .brand-text {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="image-section">
                <div class="floating-elements">
                    <div class="floating-element"></div>
                    <div class="floating-element"></div>
                    <div class="floating-element"></div>
                    <div class="floating-element"></div>
                    <div class="floating-element"></div>
                </div>
                
                <div class="welcome-content">
                    <div class="welcome-icon">
                        <i class="fas fa-spray-can text-white" style="font-size: 2.2rem;"></i>
                    </div>
                    <div class="welcome-text">
                        مرحباً بك في<br>
                        نظام إدارة العطور
                    </div>
                    <div class="welcome-subtitle">
                        استمتع بتجربة إدارة متقدمة وسهلة
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <div class="login-header">
                    <div class="brand-text">عطر التاجوري</div>
                    <div class="brand-subtitle" style="color: #6c757d;">تسجيل الدخول</div>
                </div>
                
                @if ($errors->any())
                    <div class="alert-modern">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="form-floating">
                        <input type="text" class="form-control" id="username" name="username" 
                               value="{{ old('username') }}" placeholder="اسم المستخدم" required>
                        <label for="username">
                            <i class="fas fa-user me-2"></i>اسم المستخدم
                        </label>
                    </div>

                    <div class="form-floating">
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="كلمة المرور" required>
                        <label for="password">
                            <i class="fas fa-lock me-2"></i>كلمة المرور
                        </label>
                    </div>

                    <button type="submit" class="btn btn-login w-100 text-white">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        تسجيل الدخول
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>