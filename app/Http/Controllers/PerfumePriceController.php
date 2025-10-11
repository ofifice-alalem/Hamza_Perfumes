<?php

namespace App\Http\Controllers;

use App\Models\PerfumePrice;
use App\Models\Perfume;
use App\Models\Size;
use Illuminate\Http\Request;

class PerfumePriceController extends Controller
{
    public function index()
    {
        $prices = PerfumePrice::with(['perfume', 'size'])->get();
        $sizes = Size::all();
        return view('prices.index', compact('prices', 'sizes'));
    }

    public function create()
    {
        $perfumes = Perfume::whereNull('category_id')->get();
        $sizes = Size::all();
        return view('prices.create', compact('perfumes', 'sizes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'perfume_id' => 'required|exists:perfumes,id',
            'bottle_size' => 'nullable|string|max:255',
            'bottle_price_regular' => 'nullable|numeric|min:0',
            'bottle_price_vip' => 'nullable|numeric|min:0',
            'sizes' => 'required|array',
            'sizes.*.price_regular' => 'nullable|numeric|min:0',
            'sizes.*.price_vip' => 'nullable|numeric|min:0'
        ]);

        // التحقق من أن العطر غير مصنف
        $perfume = Perfume::find($request->perfume_id);
        if ($perfume && $perfume->category_id !== null) {
            return back()->withErrors(['perfume_id' => 'لا يمكن إضافة أسعار للعطور المصنفة. العطور المصنفة لها أسعار ثابتة.'])->withInput();
        }

        $savedCount = 0;
        foreach ($request->sizes as $sizeId => $prices) {
            if (!empty($prices['price_regular']) && !empty($prices['price_vip'])) {
                // التحقق من عدم وجود سعر مكرر
                $exists = PerfumePrice::where('perfume_id', $request->perfume_id)
                    ->where('size_id', $sizeId)
                    ->exists();

                if (!$exists) {
                    PerfumePrice::create([
                        'perfume_id' => $request->perfume_id,
                        'size_id' => $sizeId,
                        'bottle_size' => $request->bottle_size,
                        'bottle_price_regular' => $request->bottle_price_regular,
                        'bottle_price_vip' => $request->bottle_price_vip,
                        'price_regular' => $prices['price_regular'],
                        'price_vip' => $prices['price_vip']
                    ]);
                    $savedCount++;
                }
            }
        }

        if ($savedCount > 0) {
            return redirect()->route('prices.index')->with('success', "تم إضافة {$savedCount} أسعار بنجاح");
        }

        return back()->with('error', 'لم يتم إضافة أي أسعار. تأكد من ملء الحقول المطلوبة.');
    }

    public function edit(PerfumePrice $price)
    {
        $perfumes = Perfume::all();
        $sizes = Size::all();
        return view('prices.edit', compact('price', 'perfumes', 'sizes'));
    }

    public function update(Request $request, PerfumePrice $price)
    {
        $request->validate([
            'perfume_id' => 'required|exists:perfumes,id',
            'bottle_size' => 'nullable|string|max:255',
            'bottle_price_regular' => 'nullable|numeric|min:0',
            'bottle_price_vip' => 'nullable|numeric|min:0',
            'sizes' => 'required|array',
            'sizes.*.price_regular' => 'nullable|numeric|min:0',
            'sizes.*.price_vip' => 'nullable|numeric|min:0'
        ]);

        $updatedCount = 0;
        foreach ($request->sizes as $sizeId => $priceData) {
            if (!empty($priceData['price_regular']) && !empty($priceData['price_vip'])) {
                if (isset($priceData['price_id'])) {
                    // تحديث سعر موجود
                    PerfumePrice::where('id', $priceData['price_id'])->update([
                        'bottle_size' => $request->bottle_size,
                        'bottle_price_regular' => $request->bottle_price_regular,
                        'bottle_price_vip' => $request->bottle_price_vip,
                        'price_regular' => $priceData['price_regular'],
                        'price_vip' => $priceData['price_vip']
                    ]);
                } else {
                    // إضافة سعر جديد
                    PerfumePrice::create([
                        'perfume_id' => $request->perfume_id,
                        'size_id' => $sizeId,
                        'bottle_size' => $request->bottle_size,
                        'bottle_price_regular' => $request->bottle_price_regular,
                        'bottle_price_vip' => $request->bottle_price_vip,
                        'price_regular' => $priceData['price_regular'],
                        'price_vip' => $priceData['price_vip']
                    ]);
                }
                $updatedCount++;
            } elseif (isset($priceData['price_id'])) {
                // حذف السعر إذا كانت الحقول فارغة
                PerfumePrice::where('id', $priceData['price_id'])->delete();
            }
        }

        return redirect()->route('prices.index')->with('success', "تم تحديث الأسعار بنجاح");
    }

    public function destroy(PerfumePrice $price)
    {
        $price->delete();
        return redirect()->route('prices.index')->with('success', 'تم حذف السعر بنجاح');
    }

    public function getPerfumePrices($perfumeId)
    {
        $prices = PerfumePrice::where('perfume_id', $perfumeId)->get();
        return response()->json($prices);
    }
}
