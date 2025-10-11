<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Perfume;
use App\Models\Size;
use App\Models\PerfumePrice;
use App\Models\Sale;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // مسح البيانات الموجودة
        Sale::query()->delete();
        PerfumePrice::query()->delete();
        \App\Models\CategoryPrice::query()->delete();
        Perfume::query()->delete();
        Category::query()->delete();
        Size::query()->delete();
        \App\Models\User::query()->delete();

        // إنشاء الأحجام
        $sizes = [
            ['label' => '10مل'],
            ['label' => '20مل'],
            ['label' => '30مل'],
            ['label' => '50مل'],
            ['label' => '100مل'],
        ];

        foreach ($sizes as $size) {
            Size::create($size);
        }

        // إنشاء التصنيفات
        $categories = ['A', 'B', 'C', 'غير مصنف'];

        foreach ($categories as $categoryName) {
            Category::create(['name' => $categoryName]);
        }

        $categoryIds = Category::pluck('id')->toArray();
        $sizeIds = Size::pluck('id')->toArray();

        // إضافة أسعار للتصنيفات
        foreach ($categoryIds as $categoryId) {
            foreach ($sizeIds as $sizeId) {
                \App\Models\CategoryPrice::create([
                    'category_id' => $categoryId,
                    'size_id' => $sizeId,
                    'price_regular' => rand(15, 120),
                    'price_vip' => rand(12, 100)
                ]);
            }
        }

        // إنشاء 500 عطر مع تصنيفات
        for ($i = 1; $i <= 500; $i++) {
            $perfume = Perfume::create([
                'name' => 'عطر ' . $i,
                'category_id' => $categoryIds[array_rand($categoryIds)]
            ]);

            // إضافة أسعار للعطر
            foreach ($sizeIds as $sizeId) {
                PerfumePrice::create([
                    'perfume_id' => $perfume->id,
                    'size_id' => $sizeId,
                    'price_regular' => rand(10, 100),
                    'price_vip' => rand(8, 80),
                    'bottle_price_regular' => rand(50, 200),
                    'bottle_price_vip' => rand(40, 160)
                ]);
            }
        }

        // إنشاء 30 عطر بدون تصنيف
        for ($i = 501; $i <= 530; $i++) {
            $perfume = Perfume::create([
                'name' => 'عطر غير مصنف ' . ($i - 500),
                'category_id' => null
            ]);

            // إضافة أسعار للعطر
            foreach ($sizeIds as $sizeId) {
                PerfumePrice::create([
                    'perfume_id' => $perfume->id,
                    'size_id' => $sizeId,
                    'price_regular' => rand(10, 100),
                    'price_vip' => rand(8, 80),
                    'bottle_price_regular' => rand(50, 200),
                    'bottle_price_vip' => rand(40, 160)
                ]);
            }
        }

        // إنشاء 1000+ مبيعة
        $perfumeIds = Perfume::pluck('id')->toArray();
        $customerTypes = ['regular', 'vip'];

        // إنشاء مستخدمين super-admin
        \App\Models\User::create([
            'name' => 'Super Admin',
            'username' => 'admin',
            'email' => 'admin@system.local',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'super-admin'
        ]);
        
        \App\Models\User::create([
            'name' => 'Boraq Admin',
            'username' => 'boraq',
            'email' => 'boraq@system.local',
            'password' => \Illuminate\Support\Facades\Hash::make('12255'),
            'role' => 'super-admin'
        ]);

        for ($i = 1; $i <= 1200; $i++) {
            $perfumeId = $perfumeIds[array_rand($perfumeIds)];
            $sizeId = $sizeIds[array_rand($sizeIds)];
            $customerType = $customerTypes[array_rand($customerTypes)];
            $isFullBottle = rand(0, 1);

            // الحصول على السعر المناسب
            $priceRecord = PerfumePrice::where('perfume_id', $perfumeId)
                ->where('size_id', $sizeId)
                ->first();

            if ($priceRecord) {
                if ($isFullBottle) {
                    $price = $customerType === 'vip' ? $priceRecord->bottle_price_vip : $priceRecord->bottle_price_regular;
                } else {
                    $price = $customerType === 'vip' ? $priceRecord->price_vip : $priceRecord->price_regular;
                }
            } else {
                $price = rand(10, 100);
            }

            Sale::create([
                'perfume_id' => $perfumeId,
                'size_id' => $sizeId,
                'customer_type' => $customerType,
                'price' => $price,
                'is_full_bottle' => $isFullBottle,
                'created_at' => now()->subDays(rand(0, 365))
            ]);
        }
    }
}