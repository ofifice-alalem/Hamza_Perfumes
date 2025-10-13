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
        $sales = Sale::with(['perfume', 'size', 'user'])->whereDate('created_at', today())->latest()->get();
        
        $showStats = !auth()->user()->isSaler();
        
        $data = compact('perfumes', 'sizes', 'sales', 'showStats');
        
        if ($showStats) {
            $totalSales = Sale::sum('price');
            $totalCustomers = Sale::count();
            $regularCustomers = Sale::where('customer_type', 'regular')->count();
            $vipCustomers = Sale::where('customer_type', 'vip')->count();
            $regularAmount = Sale::where('customer_type', 'regular')->sum('price');
            $vipAmount = Sale::where('customer_type', 'vip')->sum('price');
            
            $data = array_merge($data, compact(
                'totalSales', 'totalCustomers', 'regularCustomers', 
                'vipCustomers', 'regularAmount', 'vipAmount'
            ));
        }
        
        return view('sales.index', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'perfume_id' => 'required|exists:perfumes,id',
            'size_id' => 'required',
            'customer_type' => 'required|in:regular,vip'
        ]);

        $perfume = Perfume::find($request->perfume_id);
        $finalPrice = null;
        
        // إذا كان الحجم هو العبوة الكاملة
        if (strpos($request->size_id, 'bottle_') === 0) {
            $bottlePrice = PerfumePrice::where('perfume_id', $request->perfume_id)
                ->where(function($query) {
                    $query->whereNotNull('bottle_price_regular')
                          ->orWhereNotNull('bottle_price_vip');
                })
                ->first();
            
            if ($bottlePrice) {
                $finalPrice = $request->customer_type === 'vip' ? $bottlePrice->bottle_price_vip : $bottlePrice->bottle_price_regular;
                // استخراج ID الحجم الفعلي
                $actualSizeId = str_replace('bottle_', '', $request->size_id);
                $request->merge(['size_id' => $actualSizeId, 'is_full_bottle' => true]);
            }
        } else {
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

            if ($price) {
                $finalPrice = $request->customer_type === 'vip' ? $price->price_vip : $price->price_regular;
            }
        }

        if (!$finalPrice) {
            return back()->with('error', 'السعر غير محدد لهذا العطر والحجم');
        }

        Sale::create([
            'user_id' => auth()->id(),
            'perfume_id' => $request->perfume_id,
            'size_id' => $request->size_id,
            'customer_type' => $request->customer_type,
            'is_full_bottle' => $request->get('is_full_bottle', false),
            'price' => $finalPrice
        ]);

        return redirect()->route('sales.index')->with('success', 'تم تسجيل البيع بنجاح');
    }

    public function getPrice(Request $request)
    {
        $perfume = Perfume::find($request->perfume_id);
        
        // إذا كان الحجم هو العبوة الكاملة
        if (strpos($request->size_id, 'bottle_') === 0) {
            $bottlePrice = PerfumePrice::where('perfume_id', $request->perfume_id)
                ->where(function($query) {
                    $query->whereNotNull('bottle_price_regular')
                          ->orWhereNotNull('bottle_price_vip');
                })
                ->first();
            
            if ($bottlePrice) {
                return response()->json([
                    'regular' => $bottlePrice->bottle_price_regular,
                    'vip' => $bottlePrice->bottle_price_vip
                ]);
            }
        }
        
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
    
    public function getAvailableSizes($perfumeId)
    {
        $perfume = Perfume::find($perfumeId);
        
        if (!$perfume) {
            return response()->json([]);
        }
        
        $sizes = [];
        
        // إذا كان العطر مصنف، جلب الأحجام من أسعار التصنيف
        if ($perfume->category_id) {
            $sizes = Size::whereHas('categoryPrices', function($query) use ($perfume) {
                $query->where('category_id', $perfume->category_id);
            })->get(['id', 'label']);
        } else {
            // إذا كان العطر غير مصنف، جلب الأحجام من أسعار العطر
            $sizes = Size::whereHas('perfumePrices', function($query) use ($perfumeId) {
                $query->where('perfume_id', $perfumeId);
            })->get(['id', 'label']);
            
            // التحقق من وجود أسعار العبوة الكاملة
            $bottlePrice = PerfumePrice::where('perfume_id', $perfumeId)
                ->where(function($query) {
                    $query->whereNotNull('bottle_price_regular')
                          ->orWhereNotNull('bottle_price_vip');
                })
                ->first();
            
            if ($bottlePrice && $bottlePrice->bottle_size) {
                // البحث عن الحجم المطابق لحجم العبوة
                $bottleSize = Size::where('label', $bottlePrice->bottle_size)->first();
                if ($bottleSize) {
                    $sizes->push((object)[
                        'id' => 'bottle_' . $bottleSize->id,
                        'label' => 'العبوة الكاملة (' . $bottlePrice->bottle_size . ')'
                    ]);
                }
            }
        }
        
        return response()->json($sizes);
    }
}
