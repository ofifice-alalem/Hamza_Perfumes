<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - حمزة عطور</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { 
            font-family: 'Tajawal', sans-serif; 
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                <div class="logo-icon">T</div>
                <div class="logo-text">TWISTY</div>
            </div>
            
            <div class="nav-links">
                <a href="#" class="nav-link active">الرئيسية</a>
                <a href="#" class="nav-link">الرسائل</a>
                <a href="#" class="nav-link">اكتشف</a>
                <a href="#" class="nav-link">المحفظة</a>
                <a href="#" class="nav-link">المشاريع</a>
            </div>
            
            <div class="search-section">
                <div class="search-box">
                    <input type="text" class="search-input" placeholder="أدخل طلب البحث...">
                    <i class="fas fa-search search-icon"></i>
                </div>
                <div class="header-icons">
                    <button class="icon-btn">
                        <i class="fas fa-bell"></i>
                    </button>
                    <button class="icon-btn">
                        <i class="fas fa-cog"></i>
                    </button>
                    <div class="user-avatar">س</div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="content-grid">
            <!-- Left Column -->
            <div class="left-column">
                <!-- Income Tracker Card -->
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h5 class="card-title">
                                <i class="fas fa-chart-line me-2"></i>
                                متتبع الدخل
                            </h5>
                            <p class="text-muted mb-0">تتبع تغيرات الدخل مع مرور الوقت والوصول إلى بيانات مفصلة</p>
                        </div>
                        <select class="week-selector">
                            <option>الأسبوع</option>
                        </select>
                    </div>
                    
                    <div class="income-chart">
                        <canvas id="incomeChart"></canvas>
                    </div>
                    
                    <div class="income-stats">
                        <div>
                            <div class="income-increase">+20%</div>
                            <div class="income-description">دخل هذا الأسبوع أعلى من الأسبوع الماضي</div>
                        </div>
                    </div>
                </div>

                <!-- Let's Connect Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">دعنا نتصل</h5>
                        <a href="#" class="see-all-link">عرض الكل</a>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-avatar">ر</div>
                        <div class="contact-info flex-grow-1">
                            <h6>راندى جوس</h6>
                            <p>أخصائي أمن سيبراني</p>
                        </div>
                        <span class="level-badge level-senior">Senior</span>
                        <button class="add-btn">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-avatar">ج</div>
                        <div class="contact-info flex-grow-1">
                            <h6>جيانا شلايفر</h6>
                            <p>مصمم واجهات المستخدم</p>
                        </div>
                        <span class="level-badge level-middle">Middle</span>
                        <button class="add-btn">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <!-- Premium Features Card -->
                <div class="card premium-card">
                    <h5 class="card-title">فتح الميزات المميزة</h5>
                    <p>احصل على وصول حصري للفوائد ووسع فرص العمل الحر الخاصة بك</p>
                    <button class="upgrade-btn">
                        ترقية الآن <i class="fas fa-arrow-left ms-2"></i>
                    </button>
                </div>
            </div>

            <!-- Right Column -->
            <div class="right-column">
                <!-- Recent Projects Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">مشاريعك الحديثة</h5>
                        <a href="#" class="see-all-link">عرض جميع المشاريع</a>
                    </div>
                    
                    <div class="project-item">
                        <div class="project-icon" style="background: #ff6b35;">W</div>
                        <div class="project-info flex-grow-1">
                            <h6>مشروع تطوير ويب</h6>
                            <span class="level-badge status-paid">مدفوع</span>
                            <p>$10/ساعة</p>
                            <div class="project-tags">
                                <span class="tag">عن بُعد</span>
                                <span class="tag">دوام جزئي</span>
                            </div>
                            <p class="small text-muted">هذا المشروع يتضمن تنفيذ وظائف الواجهة الأمامية والخلفية، بالإضافة إلى التكامل مع واجهات برمجة التطبيقات الخارجية.</p>
                            <div class="d-flex align-items-center gap-3 text-muted small">
                                <span><i class="fas fa-map-marker-alt me-1"></i>ألمانيا</span>
                                <span><i class="fas fa-clock me-1"></i>منذ ساعتين</span>
                            </div>
                        </div>
                        <button class="icon-btn">
                            <i class="fas fa-chevron-up"></i>
                        </button>
                    </div>
                    
                    <div class="project-item">
                        <div class="project-icon" style="background: #6c757d;">C</div>
                        <div class="project-info flex-grow-1">
                            <h6>مشروع حقوق الطبع</h6>
                            <span class="level-badge status-not-paid">غير مدفوع</span>
                            <p>$10/ساعة</p>
                        </div>
                        <button class="icon-btn">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    
                    <div class="project-item">
                        <div class="project-icon" style="background: #17a2b8;">W</div>
                        <div class="project-info flex-grow-1">
                            <h6>مشروع تصميم ويب</h6>
                            <span class="level-badge status-paid">مدفوع</span>
                            <p>$10/ساعة</p>
                        </div>
                        <button class="icon-btn">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                </div>

                <!-- Proposal Progress Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">تقدم المقترحات</h5>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted">11 أبريل، 2024</span>
                            <i class="fas fa-chevron-down text-muted"></i>
                        </div>
                    </div>
                    
                    <div class="progress-section">
                        <div class="progress-item">
                            <div class="progress-number">64</div>
                            <div class="progress-label">المقترحات المرسلة</div>
                            <div class="progress-bars">
                                <div class="progress-bar"></div>
                                <div class="progress-bar"></div>
                                <div class="progress-bar"></div>
                                <div class="progress-bar"></div>
                                <div class="progress-bar"></div>
                                <div class="progress-bar"></div>
                                <div class="progress-bar"></div>
                                <div class="progress-bar"></div>
                            </div>
                        </div>
                        
                        <div class="progress-item">
                            <div class="progress-number">12</div>
                            <div class="progress-label">المقابلات</div>
                            <div class="progress-bars">
                                <div class="progress-bar active"></div>
                                <div class="progress-bar active"></div>
                                <div class="progress-bar active"></div>
                                <div class="progress-bar"></div>
                                <div class="progress-bar"></div>
                                <div class="progress-bar"></div>
                                <div class="progress-bar"></div>
                                <div class="progress-bar"></div>
                            </div>
                        </div>
                        
                        <div class="progress-item">
                            <div class="progress-number">10</div>
                            <div class="progress-label">التوظيف</div>
                            <div class="progress-bars">
                                <div class="progress-bar completed"></div>
                                <div class="progress-bar completed"></div>
                                <div class="progress-bar completed"></div>
                                <div class="progress-bar completed"></div>
                                <div class="progress-bar completed"></div>
                                <div class="progress-bar"></div>
                                <div class="progress-bar"></div>
                                <div class="progress-bar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Income Chart
        const ctx = document.getElementById('incomeChart').getContext('2d');
        const incomeChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['S', 'M', 'T', 'W', 'T', 'F', 'S'],
                datasets: [{
                    data: [1200, 1900, 2567, 1800, 2200, 1500, 2000],
                    backgroundColor: function(context) {
                        if (context.dataIndex === 2) {
                            return '#ff6b35';
                        }
                        return '#e9ecef';
                    },
                    borderRadius: 4,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        display: true,
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        display: false
                    }
                }
            }
        });

        // Add hover effects
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.transition = 'transform 0.3s ease';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>
