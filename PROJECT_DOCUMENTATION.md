# نظام إدارة العطور - عطر التاجوري

## نظرة عامة
نظام إدارة شامل لمتجر العطور يتيح إدارة المخزون، المبيعات، الأسعار، والمستخدمين مع واجهة عربية حديثة.

## المتطلبات التقنية
- PHP 8.1+
- Laravel 11
- MySQL 8.0+
- Composer
- Node.js & NPM

## التثبيت

### 1. إعداد المشروع
```bash
composer create-project laravel/laravel perfume-system
cd perfume-system
```

### 2. إعداد قاعدة البيانات
```bash
# في ملف .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hamza_perfumes
DB_USERNAME=root
DB_PASSWORD=
```

### 3. تشغيل Migrations و Seeders
```bash
php artisan migrate
php artisan db:seed
```

## هيكل قاعدة البيانات

### 1. جدول المستخدمين (users)
| العمود | النوع | الوصف |
|--------|-------|-------|
| id | bigint | المعرف الأساسي |
| name | varchar(255) | اسم المستخدم |
| username | varchar(255) | اسم المستخدم للدخول |
| email | varchar(255) | البريد الإلكتروني |
| password | varchar(255) | كلمة المرور مشفرة |
| role | enum | الصلاحية (super-admin, admin, saler) |
| created_at | timestamp | تاريخ الإنشاء |
| updated_at | timestamp | تاريخ التحديث |

### 2. جدول التصنيفات (categories)
| العمود | النوع | الوصف |
|--------|-------|-------|
| id | bigint | المعرف الأساسي |
| name | varchar(255) | اسم التصنيف |
| created_at | timestamp | تاريخ الإنشاء |
| updated_at | timestamp | تاريخ التحديث |

### 3. جدول العطور (perfumes)
| العمود | النوع | الوصف |
|--------|-------|-------|
| id | bigint | المعرف الأساسي |
| name | varchar(255) | اسم العطر |
| category_id | bigint nullable | معرف التصنيف |
| created_at | timestamp | تاريخ الإنشاء |
| updated_at | timestamp | تاريخ التحديث |

### 4. جدول الأحجام (sizes)
| العمود | النوع | الوصف |
|--------|-------|-------|
| id | bigint | المعرف الأساسي |
| label | varchar(255) | تسمية الحجم (مثل: 10مل) |
| created_at | timestamp | تاريخ الإنشاء |
| updated_at | timestamp | تاريخ التحديث |

### 5. جدول أسعار العطور (perfume_prices)
| العمود | النوع | الوصف |
|--------|-------|-------|
| id | bigint | المعرف الأساسي |
| perfume_id | bigint | معرف العطر |
| size_id | bigint | معرف الحجم |
| price_regular | decimal(8,2) | السعر للعملاء العاديين |
| price_vip | decimal(8,2) | السعر لعملاء VIP |
| bottle_size | varchar nullable | حجم العبوة الكاملة |
| bottle_price_regular | decimal nullable | سعر العبوة للعاديين |
| bottle_price_vip | decimal nullable | سعر العبوة لـ VIP |
| created_at | timestamp | تاريخ الإنشاء |
| updated_at | timestamp | تاريخ التحديث |

### 6. جدول أسعار التصنيفات (category_prices)
| العمود | النوع | الوصف |
|--------|-------|-------|
| id | bigint | المعرف الأساسي |
| category_id | bigint | معرف التصنيف |
| size_id | bigint | معرف الحجم |
| price_regular | decimal(8,2) | السعر للعملاء العاديين |
| price_vip | decimal(8,2) | السعر لعملاء VIP |
| created_at | timestamp | تاريخ الإنشاء |
| updated_at | timestamp | تاريخ التحديث |

### 7. جدول المبيعات (sales)
| العمود | النوع | الوصف |
|--------|-------|-------|
| id | bigint | المعرف الأساسي |
| user_id | bigint nullable | معرف البائع |
| perfume_id | bigint | معرف العطر |
| size_id | bigint nullable | معرف الحجم |
| customer_type | enum | نوع العميل (regular, vip) |
| is_full_bottle | boolean | هل هي عبوة كاملة |
| price | decimal(8,2) | سعر البيع |
| created_at | timestamp | تاريخ البيع |
| updated_at | timestamp | تاريخ التحديث |

## الصفحات والوظائف

### 1. صفحة تسجيل الدخول (/login)
**Controller:** `AuthController`
**View:** `resources/views/auth/login.blade.php`
**Model:** `User`

**الوصف:** صفحة تسجيل دخول حديثة مقسمة لجزئين
- **الجانب الأيسر:** صورة ترحيبية مع رسالة
- **الجانب الأيمن:** نموذج تسجيل الدخول
- **الحقول:** اسم المستخدم، كلمة المرور
- **التصميم:** متجاوب مع تأثيرات بصرية حديثة
- **المسارات:**
  - `GET /login` - عرض صفحة تسجيل الدخول
  - `POST /login` - معالجة تسجيل الدخول
  - `POST /logout` - تسجيل الخروج

### 2. لوحة التحكم (/dashboard)
**Controller:** `DashboardController`
**View:** `resources/views/dashboard.blade.php`
**Models:** `Sale`, `User`, `Category`, `Perfume`

**الوصف:** صفحة رئيسية تعرض إحصائيات شاملة
- **الفلاتر:** التاريخ، نوع العميل، التصنيف، البائع، الترتيب
- **الإحصائيات:** إجمالي المبيعات، عدد العملاء، الكمية، متوسط البيع
- **الجدول:** تحليل مفصل للمبيعات حسب العطر
- **التصدير:** CSV, JSON, XML
- **الصلاحيات:** super-admin فقط
- **المسارات:**
  - `GET /dashboard` - عرض لوحة التحكم
  - `GET /api/sales-analytics` - جلب بيانات الإحصائيات
  - `GET /api/export-sales-analytics` - تصدير التقارير

### 3. إدارة المستخدمين (/users)
**Controller:** `UserController`
**Views:** 
- `resources/views/users/index.blade.php` - قائمة المستخدمين
- `resources/views/users/create.blade.php` - إضافة مستخدم
- `resources/views/users/edit.blade.php` - تعديل مستخدم
**Model:** `User`

**الوصف:** إدارة شاملة للمستخدمين
- **العرض:** جدول بالمستخدمين مع الأدوار
- **الإضافة:** نموذج إضافة مستخدم جديد
- **التعديل:** تحديث بيانات المستخدم
- **الحذف:** حذف مع تأكيد متقدم
- **الصلاحيات:** super-admin فقط
- **المسارات:**
  - `GET /users` - قائمة المستخدمين
  - `GET /users/create` - نموذج إضافة مستخدم
  - `POST /users` - حفظ مستخدم جديد
  - `GET /users/{id}/edit` - نموذج تعديل مستخدم
  - `PUT /users/{id}` - تحديث مستخدم
  - `DELETE /users/{id}` - حذف مستخدم

### 4. إدارة العطور (/perfumes)
**Controller:** `PerfumeController`
**Views:**
- `resources/views/perfumes/index.blade.php` - قائمة العطور
- `resources/views/perfumes/create.blade.php` - إضافة عطر
- `resources/views/perfumes/edit.blade.php` - تعديل عطر
**Models:** `Perfume`, `Category`, `PerfumePrice`

**الوصف:** إدارة مخزون العطور
- **العرض:** جدول العطور مع التصنيفات
- **البحث:** بحث سريع بالاسم مع API
- **الإضافة:** إضافة عطر جديد مع تصنيف
- **التعديل:** تحديث اسم العطر والتصنيف
- **الحذف:** حذف مع التحقق من المبيعات
- **الصلاحيات:** super-admin, admin
- **المسارات:**
  - `GET /perfumes` - قائمة العطور
  - `GET /perfumes/create` - نموذج إضافة عطر
  - `POST /perfumes` - حفظ عطر جديد
  - `GET /perfumes/{id}/edit` - نموذج تعديل عطر
  - `PUT /perfumes/{id}` - تحديث عطر
  - `DELETE /perfumes/{id}` - حذف عطر
  - `GET /perfumes/search` - البحث في العطور

### 5. إدارة التصنيفات (/categories)
**Controller:** `CategoryController`
**Views:**
- `resources/views/categories/index.blade.php` - قائمة التصنيفات
- `resources/views/categories/create.blade.php` - إضافة تصنيف
- `resources/views/categories/edit.blade.php` - تعديل تصنيف
**Models:** `Category`, `Perfume`, `CategoryPrice`

**الوصف:** إدارة تصنيفات العطور
- **العرض:** قائمة التصنيفات مع عدد العطور
- **الإضافة:** إضافة تصنيف جديد
- **التعديل:** تحديث اسم التصنيف
- **الحذف:** حذف مع نقل العطور لغير مصنف
- **الصلاحيات:** super-admin, admin
- **المسارات:**
  - `GET /categories` - قائمة التصنيفات
  - `GET /categories/create` - نموذج إضافة تصنيف
  - `POST /categories` - حفظ تصنيف جديد
  - `GET /categories/{id}/edit` - نموذج تعديل تصنيف
  - `PUT /categories/{id}` - تحديث تصنيف
  - `DELETE /categories/{id}` - حذف تصنيف

### 6. إدارة الأحجام (/sizes)
**Controller:** `SizeController`
**Views:**
- `resources/views/sizes/index.blade.php` - قائمة الأحجام
- `resources/views/sizes/create.blade.php` - إضافة حجم
- `resources/views/sizes/edit.blade.php` - تعديل حجم
**Models:** `Size`, `PerfumePrice`, `CategoryPrice`

**الوصف:** إدارة أحجام العطور
- **العرض:** قائمة الأحجام المتاحة
- **الإضافة:** إضافة حجم جديد
- **التعديل:** تحديث تسمية الحجم
- **الحذف:** حذف مع التحقق من الاستخدام
- **الصلاحيات:** super-admin, admin
- **المسارات:**
  - `GET /sizes` - قائمة الأحجام
  - `GET /sizes/create` - نموذج إضافة حجم
  - `POST /sizes` - حفظ حجم جديد
  - `GET /sizes/{id}/edit` - نموذج تعديل حجم
  - `PUT /sizes/{id}` - تحديث حجم
  - `DELETE /sizes/{id}` - حذف حجم

### 7. إدارة الأسعار (/prices)
**Controller:** `PriceController`
**Views:**
- `resources/views/prices/index.blade.php` - إدارة الأسعار (تبويبات)
**Models:** `PerfumePrice`, `CategoryPrice`, `Perfume`, `Category`, `Size`

**الوصف:** إدارة أسعار العطور والتصنيفات
- **التبويبات:** أسعار العطور، أسعار التصنيفات
- **العرض:** جداول الأسعار مع الفلترة
- **التعديل:** تحديث الأسعار للعاديين و VIP
- **العبوات الكاملة:** إدارة أسعار العبوات الكاملة
- **الصلاحيات:** super-admin, admin
- **المسارات:**
  - `GET /prices` - صفحة إدارة الأسعار
  - `POST /prices/perfume` - تحديث أسعار العطور
  - `POST /prices/category` - تحديث أسعار التصنيفات

### 8. إدارة المبيعات (/sales)
**Controller:** `SaleController`
**Views:**
- `resources/views/sales/index.blade.php` - صفحة المبيعات
**Models:** `Sale`, `Perfume`, `Size`, `User`, `PerfumePrice`, `CategoryPrice`

**الوصف:** تسجيل ومتابعة المبيعات
- **نموذج البيع:** بحث العطر، اختيار الحجم، نوع العميل
- **عرض السعر:** عرض تلقائي للسعر حسب الاختيار
- **سجل المبيعات:** جدول المبيعات اليومية
- **الإحصائيات:** إحصائيات سريعة للمبيعات
- **تسجيل البائع:** تسجيل تلقائي للمستخدم الحالي
- **الصلاحيات:** جميع المستخدمين
- **المسارات:**
  - `GET /sales` - صفحة المبيعات
  - `POST /sales` - تسجيل بيع جديد
  - `GET /api/get-price` - جلب سعر العطر
  - `GET /api/get-available-sizes/{perfume}` - جلب الأحجام المتاحة

## نظام الصلاحيات

### Super Admin
- الوصول لجميع الصفحات
- إدارة المستخدمين
- عرض لوحة التحكم والإحصائيات
- إدارة العطور والتصنيفات والأسعار
- تسجيل المبيعات

### Admin
- إدارة العطور والتصنيفات والأسعار
- تسجيل المبيعات
- لا يمكن إدارة المستخدمين
- لا يمكن عرض لوحة التحكم

### Saler (البائع)
- تسجيل المبيعات فقط
- عرض المبيعات اليومية
- لا يمكن الوصول للإدارة

## المميزات التقنية

### 1. البحث المتقدم
- بحث فوري في العطور
- اقتراحات تلقائية
- فلترة حسب التصنيف

### 2. إدارة الأسعار الذكية
- أسعار حسب التصنيف (افتراضي)
- أسعار مخصصة للعطور
- أسعار العبوات الكاملة
- أسعار مختلفة للعاديين و VIP

### 3. تتبع المبيعات
- تسجيل البائع لكل عملية
- إحصائيات مفصلة
- تصدير التقارير
- فلترة متقدمة

### 4. واجهة المستخدم
- تصميم عربي متجاوب
- تأثيرات بصرية حديثة
- رسائل تأكيد تفاعلية
- تجربة مستخدم محسنة

## ملفات التكوين المهمة

## Routes (web.php)
```php
// المصادقة
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// الصفحات المحمية
Route::middleware('auth')->group(function () {
    // لوحة التحكم - super-admin فقط
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // إدارة المستخدمين - super-admin فقط
    Route::resource('users', UserController::class);
    
    // إدارة العطور - super-admin, admin
    Route::resource('perfumes', PerfumeController::class);
    Route::get('/perfumes/search', [PerfumeController::class, 'search'])->name('perfumes.search');
    
    // إدارة التصنيفات - super-admin, admin
    Route::resource('categories', CategoryController::class);
    
    // إدارة الأحجام - super-admin, admin
    Route::resource('sizes', SizeController::class);
    
    // إدارة الأسعار - super-admin, admin
    Route::get('/prices', [PriceController::class, 'index'])->name('prices.index');
    Route::post('/prices/perfume', [PriceController::class, 'updatePerfumePrice'])->name('prices.perfume.update');
    Route::post('/prices/category', [PriceController::class, 'updateCategoryPrice'])->name('prices.category.update');
    
    // إدارة المبيعات - جميع المستخدمين
    Route::resource('sales', SaleController::class)->only(['index', 'store']);
});

// API Routes
Route::middleware('auth')->prefix('api')->group(function () {
    Route::get('/sales-analytics', [DashboardController::class, 'getSalesAnalytics']);
    Route::get('/export-sales-analytics', [DashboardController::class, 'exportSalesAnalytics']);
    Route::get('/get-price', [SaleController::class, 'getPrice']);
    Route::get('/get-available-sizes/{perfume}', [SaleController::class, 'getAvailableSizes']);
});
```

## Middleware المستخدمة

### AuthMiddleware
**الملف:** `app/Http/Middleware/Authenticate.php`
- التحقق من تسجيل الدخول
- إعادة توجيه لصفحة تسجيل الدخول

### RoleMiddleware (مخصص)
**الملف:** `app/Http/Middleware/CheckRole.php`
- فحص صلاحيات المستخدم
- منع الوصول غير المصرح به
- إعادة توجيه للصفحة المناسبة

## Seeders

### DatabaseSeeder
**الملف:** `database/seeders/DatabaseSeeder.php`
- تشغيل جميع Seeders بالترتيب الصحيح
- إنشاء البيانات الأساسية

### UserSeeder
**الملف:** `database/seeders/UserSeeder.php`
- إنشاء المستخدمين الافتراضيين
- تعيين الأدوار والصلاحيات

### SalesSeeder
**الملف:** `database/seeders/SalesSeeder.php`
- إنشاء مبيعات وهمية للتجربة
- ربط المبيعات بالمستخدمين

### Middleware
- **auth:** التحقق من تسجيل الدخول
- **role:** التحقق من الصلاحيات

## Controllers والوظائف

### AuthController
- `showLogin()` - عرض صفحة تسجيل الدخول
- `login()` - معالجة تسجيل الدخول
- `logout()` - تسجيل الخروج

### DashboardController
- `index()` - عرض لوحة التحكم
- `getSalesAnalytics()` - جلب بيانات الإحصائيات
- `exportSalesAnalytics()` - تصدير التقارير
- `getSalesData()` - معالجة بيانات المبيعات مع الفلاتر

### UserController (Resource Controller)
- `index()` - عرض قائمة المستخدمين
- `create()` - عرض نموذج إضافة مستخدم
- `store()` - حفظ مستخدم جديد
- `edit()` - عرض نموذج تعديل مستخدم
- `update()` - تحديث بيانات مستخدم
- `destroy()` - حذف مستخدم

### PerfumeController (Resource Controller)
- `index()` - عرض قائمة العطور
- `create()` - عرض نموذج إضافة عطر
- `store()` - حفظ عطر جديد
- `edit()` - عرض نموذج تعديل عطر
- `update()` - تحديث بيانات عطر
- `destroy()` - حذف عطر
- `search()` - البحث في العطور

### CategoryController (Resource Controller)
- `index()` - عرض قائمة التصنيفات
- `create()` - عرض نموذج إضافة تصنيف
- `store()` - حفظ تصنيف جديد
- `edit()` - عرض نموذج تعديل تصنيف
- `update()` - تحديث بيانات تصنيف
- `destroy()` - حذف تصنيف

### SizeController (Resource Controller)
- `index()` - عرض قائمة الأحجام
- `create()` - عرض نموذج إضافة حجم
- `store()` - حفظ حجم جديد
- `edit()` - عرض نموذج تعديل حجم
- `update()` - تحديث بيانات حجم
- `destroy()` - حذف حجم

### PriceController
- `index()` - عرض صفحة إدارة الأسعار
- `updatePerfumePrice()` - تحديث أسعار العطور
- `updateCategoryPrice()` - تحديث أسعار التصنيفات

### SaleController
- `index()` - عرض صفحة المبيعات
- `store()` - تسجيل بيع جديد
- `getPrice()` - جلب سعر العطر
- `getAvailableSizes()` - جلب الأحجام المتاحة

## Models والعلاقات

### User Model
**الملف:** `app/Models/User.php`
**العلاقات:**
- `sales()` - hasMany(Sale::class)
**الوظائف:**
- `isSuperAdmin()` - فحص صلاحية المدير العام
- `isAdmin()` - فحص صلاحية المدير
- `isSaler()` - فحص صلاحية البائع

### Perfume Model
**الملف:** `app/Models/Perfume.php`
**العلاقات:**
- `category()` - belongsTo(Category::class)
- `prices()` - hasMany(PerfumePrice::class)
- `sales()` - hasMany(Sale::class)

### Category Model
**الملف:** `app/Models/Category.php`
**العلاقات:**
- `perfumes()` - hasMany(Perfume::class)
- `prices()` - hasMany(CategoryPrice::class)

### Size Model
**الملف:** `app/Models/Size.php`
**العلاقات:**
- `perfumePrices()` - hasMany(PerfumePrice::class)
- `categoryPrices()` - hasMany(CategoryPrice::class)
- `sales()` - hasMany(Sale::class)

### Sale Model
**الملف:** `app/Models/Sale.php`
**العلاقات:**
- `user()` - belongsTo(User::class)
- `perfume()` - belongsTo(Perfume::class)
- `size()` - belongsTo(Size::class)

### PerfumePrice Model
**الملف:** `app/Models/PerfumePrice.php`
**العلاقات:**
- `perfume()` - belongsTo(Perfume::class)
- `size()` - belongsTo(Size::class)

### CategoryPrice Model
**الملف:** `app/Models/CategoryPrice.php`
**العلاقات:**
- `category()` - belongsTo(Category::class)
- `size()` - belongsTo(Size::class)

## البيانات الافتراضية

### المستخدمون
| اسم المستخدم | كلمة المرور | الاسم | الدور |
|---------------|-------------|-------|-------|
| admin | password | المدير العام | super-admin |
| ahmed | password | أحمد محمد | saler |
| fatima | password | فاطمة علي | saler |
| mohammed | password | محمد حسن | admin |

### البيانات الأساسية
- **العطور:** 500+ عطر مع تصنيفات مختلفة
- **التصنيفات:** A, B, C, غير مصنف
- **الأحجام:** 10مل، 20مل، 30مل، 50مل، 100مل
- **الأسعار:** أسعار شاملة لجميع العطور والتصنيفات
- **المبيعات:** 1200+ عملية بيع وهمية موزعة على 365 يوم

## ملفات Views الرئيسية

### Layout الأساسي
**الملف:** `resources/views/layouts/app.blade.php`
- التخطيط الأساسي للنظام
- القائمة الجانبية والعلوية
- نظام الصلاحيات في القوائم
- البحث المتقدم في العطور

### صفحات المصادقة
- `resources/views/auth/login.blade.php` - صفحة تسجيل الدخول

### صفحات المستخدمين
- `resources/views/users/index.blade.php` - قائمة المستخدمين
- `resources/views/users/create.blade.php` - إضافة مستخدم
- `resources/views/users/edit.blade.php` - تعديل مستخدم

### صفحات العطور
- `resources/views/perfumes/index.blade.php` - قائمة العطور
- `resources/views/perfumes/create.blade.php` - إضافة عطر
- `resources/views/perfumes/edit.blade.php` - تعديل عطر

### صفحات التصنيفات
- `resources/views/categories/index.blade.php` - قائمة التصنيفات
- `resources/views/categories/create.blade.php` - إضافة تصنيف
- `resources/views/categories/edit.blade.php` - تعديل تصنيف

### صفحات الأحجام
- `resources/views/sizes/index.blade.php` - قائمة الأحجام
- `resources/views/sizes/create.blade.php` - إضافة حجم
- `resources/views/sizes/edit.blade.php` - تعديل حجم

### صفحات الأسعار
- `resources/views/prices/index.blade.php` - إدارة الأسعار

### صفحات المبيعات
- `resources/views/sales/index.blade.php` - صفحة المبيعات

### لوحة التحكم
- `resources/views/dashboard.blade.php` - لوحة التحكم والإحصائيات

## التشغيل

```bash
# تشغيل الخادم
php artisan serve

# الوصول للنظام
http://localhost:8000

# تسجيل الدخول
Username: admin
Password: password
```

## الصيانة والتطوير

### إضافة مستخدم جديد
```bash
php artisan tinker
User::create([
    'name' => 'اسم المستخدم',
    'username' => 'username',
    'email' => 'email@example.com',
    'password' => Hash::make('password'),
    'role' => 'saler' // أو admin أو super-admin
]);
```

### إعادة تعيين البيانات
```bash
php artisan migrate:fresh --seed
```

### تشغيل Seeders منفردة
```bash
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=SalesSeeder
```

### النسخ الاحتياطي
```bash
mysqldump -u root -p hamza_perfumes > backup.sql
```

### استعادة النسخة الاحتياطية
```bash
mysql -u root -p hamza_perfumes < backup.sql
```

### تحديث الأسعار بشكل جماعي
```bash
php artisan tinker
// تحديث أسعار تصنيف معين
CategoryPrice::where('category_id', 1)->update([
    'price_regular' => DB::raw('price_regular * 1.1'),
    'price_vip' => DB::raw('price_vip * 1.1')
]);
```

## API Endpoints

### المصادقة
- `POST /login` - تسجيل الدخول
- `POST /logout` - تسجيل الخروج

### الإحصائيات
- `GET /api/sales-analytics` - جلب إحصائيات المبيعات
- `GET /api/export-sales-analytics` - تصدير التقارير

### المبيعات
- `GET /api/get-price` - جلب سعر عطر معين
- `GET /api/get-available-sizes/{perfume}` - جلب الأحجام المتاحة

### البحث
- `GET /perfumes/search` - البحث في العطور

## JavaScript المستخدم

### البحث المتقدم
- بحث فوري في العطور
- عرض النتائج في dropdown
- تحديث الأحجام المتاحة

### إدارة الأسعار
- جلب الأسعار تلقائياً
- تحديث السعر حسب نوع العميل
- التحقق من صحة البيانات

### الإحصائيات
- تحديث البيانات بـ AJAX
- فلترة متقدمة
- تصدير التقارير

### تأكيد الحذف
- نوافذ تأكيد متقدمة
- عرض تفاصيل العنصر المراد حذفه
- منع الحذف العرضي

## الأمان

### كلمات المرور
- مشفرة باستخدام Hash::make()
- تحقق باستخدام Hash::check()

### الصلاحيات
- فحص الأدوار في كل صفحة
- منع الوصول غير المصرح به
- تسجيل العمليات بالمستخدم

### الحماية
- CSRF Protection
- SQL Injection Prevention
- XSS Protection

## الملفات المهمة للتخصيص

### التصميم والألوان
- `resources/views/layouts/app.blade.php` - التخطيط الأساسي
- `public/css/dashboard.css` - ملف CSS مخصص
- الألوان الأساسية: `#667eea`, `#764ba2`, `#ff6b35`

### الإعدادات
- `.env` - إعدادات قاعدة البيانات والتطبيق
- `config/app.php` - إعدادات التطبيق الأساسية
- `config/database.php` - إعدادات قاعدة البيانات

### اللغة والترجمة
- `resources/lang/ar/` - ملفات الترجمة العربية
- `config/app.php` - تعيين اللغة الافتراضية

## نصائح للتطوير

### إضافة صفحة جديدة
1. إنشاء Controller: `php artisan make:controller NewController`
2. إضافة Route في `web.php`
3. إنشاء View في `resources/views/`
4. إضافة رابط في القائمة `layouts/app.blade.php`

### إضافة جدول جديد
1. إنشاء Migration: `php artisan make:migration create_table_name`
2. إنشاء Model: `php artisan make:model ModelName`
3. تعريف العلاقات في Models
4. إنشاء Seeder: `php artisan make:seeder TableSeeder`

### إضافة صلاحية جديدة
1. إضافة الدور في enum جدول users
2. إضافة وظيفة فحص في User Model
3. تحديث Middleware للتحقق من الصلاحية
4. تحديث القوائم في Layout

---

**تم إنشاء هذا النظام بواسطة Laravel 11 مع تصميم عربي حديث ومتجاوب**

**آخر تحديث:** ديسمبر 2024
**الإصدار:** 1.0.0