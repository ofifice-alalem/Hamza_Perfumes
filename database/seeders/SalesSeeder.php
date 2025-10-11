<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sale;
use App\Models\Perfume;
use App\Models\Size;
use App\Models\PerfumePrice;
use App\Models\CategoryPrice;

class SalesSeeder extends Seeder
{
    public function run(): void
    {
        $perfumes = Perfume::all();
        $sizes = Size::all();
        $customerTypes = ['regular', 'vip'];
        
        if ($perfumes->isEmpty() || $sizes->isEmpty()) {
            $this->command->info('لا توجد عطور أو أحجام لإنشاء مبيعات');
            return;
        }

        for ($i = 0; $i < 50; $i++) {
            $perfume = $perfumes->random();
            $size = $sizes->random();
            $customerType = $customerTypes[array_rand($customerTypes)];
            $isFullBottle = rand(0, 100) < 20; // 20% احتمال عبوة كاملة
            
            // الحصول على السعر
            $price = $this->getPrice($perfume, $size, $customerType, $isFullBottle);
            
            if ($price) {
                Sale::create([
                    'perfume_id' => $perfume->id,
                    'size_id' => $size->id,
                    'customer_type' => $customerType,
                    'is_full_bottle' => $isFullBottle,
                    'price' => $price,
                    'created_at' => now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59))
                ]);
            }
        }
        
        $this->command->info('تم إنشاء 50 عملية بيع وهمية');
    }
    
    private function getPrice($perfume, $size, $customerType, $isFullBottle)
    {
        if ($isFullBottle) {
            // البحث عن سعر العبوة الكاملة
            $bottlePrice = PerfumePrice::where('perfume_id', $perfume->id)
                ->where(function($query) {
                    $query->whereNotNull('bottle_price_regular')
                          ->orWhereNotNull('bottle_price_vip');
                })
                ->first();
            
            if ($bottlePrice) {
                return $customerType === 'vip' ? 
                    ($bottlePrice->bottle_price_vip ?? $bottlePrice->bottle_price_regular ?? rand(800, 2000)) :
                    ($bottlePrice->bottle_price_regular ?? rand(800, 2000));
            }
        }
        
        // البحث في أسعار التصنيف أولاً
        if ($perfume->category_id) {
            $categoryPrice = CategoryPrice::where('category_id', $perfume->category_id)
                ->where('size_id', $size->id)
                ->first();
            
            if ($categoryPrice) {
                return $customerType === 'vip' ? $categoryPrice->price_vip : $categoryPrice->price_regular;
            }
        }
        
        // البحث في الأسعار المخصصة
        $perfumePrice = PerfumePrice::where('perfume_id', $perfume->id)
            ->where('size_id', $size->id)
            ->first();
        
        if ($perfumePrice) {
            return $customerType === 'vip' ? $perfumePrice->price_vip : $perfumePrice->price_regular;
        }
        
        // سعر افتراضي إذا لم يوجد سعر محدد
        $basePrice = rand(50, 500);
        return $customerType === 'vip' ? $basePrice * 0.9 : $basePrice;
    }
}