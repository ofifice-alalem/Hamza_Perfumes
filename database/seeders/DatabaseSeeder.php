<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CategoryPrice;
use App\Models\Perfume;
use App\Models\Size;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء الأحجام
        $sizes = ['3ml', '8ml', '15ml', '30ml', '50ml'];
        foreach ($sizes as $sizeLabel) {
            Size::create(['label' => $sizeLabel]);
        }

        // إنشاء التصنيفات
        $categories = [
            'A' => [['3ml' => 5, '8ml' => 10, '15ml' => 18, '30ml' => 35, '50ml' => 55], ['3ml' => 4, '8ml' => 8, '15ml' => 15, '30ml' => 28, '50ml' => 45]],
            'B' => [['3ml' => 8, '8ml' => 15, '15ml' => 25, '30ml' => 45, '50ml' => 70], ['3ml' => 6, '8ml' => 12, '15ml' => 20, '30ml' => 36, '50ml' => 56]],
            'C' => [['3ml' => 12, '8ml' => 22, '15ml' => 35, '30ml' => 60, '50ml' => 90], ['3ml' => 10, '8ml' => 18, '15ml' => 28, '30ml' => 48, '50ml' => 72]]
        ];

        foreach ($categories as $categoryName => [$regularPrices, $vipPrices]) {
            $category = Category::create(['name' => $categoryName]);
            
            foreach ($regularPrices as $sizeLabel => $regularPrice) {
                $size = Size::where('label', $sizeLabel)->first();
                CategoryPrice::create([
                    'category_id' => $category->id,
                    'size_id' => $size->id,
                    'price_regular' => $regularPrice,
                    'price_vip' => $vipPrices[$sizeLabel]
                ]);
            }
        }

        // إنشاء عطور تجريبية
        $perfumesData = [
            ['عود كمبودي', 'A'],
            ['عنبر أسود', 'A'],
            ['ورد طائفي', 'B'],
            ['مسك أبيض', 'B'],
            ['عود هندي', 'C'],
            ['عنبر ملكي', 'C'],
            ['عطر خاص', null] // عطر بدون تصنيف
        ];

        foreach ($perfumesData as [$perfumeName, $categoryName]) {
            $categoryId = $categoryName ? Category::where('name', $categoryName)->first()->id : null;
            Perfume::create([
                'name' => $perfumeName,
                'category_id' => $categoryId
            ]);
        }
    }
}
