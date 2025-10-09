<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Perfume;
use App\Models\Size;
use App\Models\PerfumePrice;
use App\Models\CategoryPrice;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        $perfumes = Perfume::all();
        $sizes = Size::all();
        $sales = Sale::with(['perfume', 'size'])->latest()->get();
        return view('sales.index', compact('perfumes', 'sizes', 'sales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'perfume_id' => 'required|exists:perfumes,id',
            'size_id' => 'required|exists:sizes,id',
            'customer_type' => 'required|in:regular,vip'
        ]);

        $perfume = Perfume::find($request->perfume_id);
        
        // البحث في أسعار التصنيف أولاً
        $price = null;
        if ($perfume->category_id) {
            $price = CategoryPrice::where('category_id', $perfume->category_id)
                ->where('size_id', $request->size_id)
                ->first();
        }
        
        // إذا لم يوجد في التصنيف، ابحث في الأسعار المخصصة
        if (!$price) {
            $price = PerfumePrice::where('perfume_id', $request->perfume_id)
                ->where('size_id', $request->size_id)
                ->first();
        }

        if (!$price) {
            return back()->with('error', 'السعر غير محدد لهذا العطر والحجم');
        }

        $finalPrice = $request->customer_type === 'vip' ? $price->price_vip : $price->price_regular;

        Sale::create([
            'perfume_id' => $request->perfume_id,
            'size_id' => $request->size_id,
            'customer_type' => $request->customer_type,
            'price' => $finalPrice
        ]);

        return redirect()->route('sales.index')->with('success', 'تم تسجيل البيع بنجاح');
    }

    public function getPrice(Request $request)
    {
        $perfume = Perfume::find($request->perfume_id);
        
        // البحث في أسعار التصنيف أولاً
        $price = null;
        if ($perfume && $perfume->category_id) {
            $price = CategoryPrice::where('category_id', $perfume->category_id)
                ->where('size_id', $request->size_id)
                ->first();
        }
        
        // إذا لم يوجد في التصنيف، ابحث في الأسعار المخصصة
        if (!$price) {
            $price = PerfumePrice::where('perfume_id', $request->perfume_id)
                ->where('size_id', $request->size_id)
                ->first();
        }

        if ($price) {
            return response()->json([
                'regular' => $price->price_regular,
                'vip' => $price->price_vip
            ]);
        }

        return response()->json(['error' => 'السعر غير محدد'], 404);
    }
}
