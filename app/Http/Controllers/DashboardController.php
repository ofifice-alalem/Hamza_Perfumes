<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Perfume;
use App\Models\Sale;
use App\Models\Size;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index()
    {
        $categories = Category::with('perfumes')->get();
        $uncategorizedPerfumes = Perfume::whereNull('category_id')->get();
        $users = User::all();
        
        return view('dashboard', compact('categories', 'uncategorizedPerfumes', 'users'));
    }
    
    public function getSalesAnalytics(Request $request)
    {
        $data = $this->getSalesData($request, true);
        return response()->json($data);
    }
    
    public function exportSalesAnalytics(Request $request)
    {
        $data = $this->getSalesData($request, false);
        $filters = $request->only(['date_from', 'date_to', 'customer_type', 'sort_by']);
        $format = $request->get('format', 'csv');
        
        $export = new \App\Exports\SalesAnalyticsExport($data, $filters);
        
        switch ($format) {
            case 'json':
                return $export->generateJSON();
            case 'xml':
                return $export->generateXML();
            default:
                return $export->generateCSV();
        }
    }
    
    private function getSalesData(Request $request, $paginate = false)
    {
        $query = Sale::with(['perfume.category', 'size', 'user']);
        
        // تحديد تاريخ البداية - إذا لم يُحدد، استخدم أقدم عملية بيع
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        } else {
            $oldestSale = Sale::orderBy('created_at', 'asc')->first();
            if ($oldestSale) {
                $query->whereDate('created_at', '>=', $oldestSale->created_at->toDateString());
            }
        }
        
        // تحديد تاريخ النهاية - إذا لم يُحدد، استخدم أحدث عملية بيع
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        } else {
            $newestSale = Sale::orderBy('created_at', 'desc')->first();
            if ($newestSale) {
                $query->whereDate('created_at', '<=', $newestSale->created_at->toDateString());
            }
        }
        
        if ($request->customer_type) {
            $query->where('customer_type', $request->customer_type);
        }
        
        if ($request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }
        
        if ($request->category_id) {
            if ($request->category_id === 'uncategorized') {
                $query->whereHas('perfume', function($q) {
                    $q->whereNull('category_id');
                });
            } else {
                $query->whereHas('perfume', function($q) use ($request) {
                    $q->where('category_id', $request->category_id);
                });
            }
        }
        
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        
        $sales = $query->get();
        
        $stats = [
            'total_sales' => $sales->sum('price'),
            'total_customers' => $sales->count(),
            'total_ml' => $this->calculateTotalML($sales),
            'total_cash' => $sales->where('payment_method', 'cash')->sum('price'),
            'total_card' => $sales->where('payment_method', 'card')->sum('price'),
            'avg_sale' => $sales->avg('price') ?? 0,
        ];
        
        // إحصائيات البائعين
        $sellerStats = $sales->groupBy('user_id')->map(function ($sellerSales) {
            $user = $sellerSales->first()->user;
            return [
                'name' => $user ? $user->name : 'غير محدد',
                'sales_count' => $sellerSales->count(),
                'total_amount' => $sellerSales->sum('price'),
                'avg_sale' => $sellerSales->avg('price')
            ];
        })->sortByDesc('total_amount')->values();
        
        // تحضير بيانات المبيعات الفردية
        $salesData = $sales->map(function ($sale) {
            return [
                'id' => $sale->id,
                'perfume_id' => $sale->perfume_id,
                'perfume_name' => $sale->perfume->name,
                'size_id' => $sale->size_id,
                'size_label' => $sale->size ? $sale->size->label : null,
                'customer_type' => $sale->customer_type,
                'payment_method' => $sale->payment_method,
                'is_full_bottle' => $sale->is_full_bottle,
                'price' => $sale->price,
                'user_name' => $sale->user ? $sale->user->name : null,
                'created_at' => $sale->created_at->toISOString()
            ];
        })->sortByDesc('created_at')->values();

        // تحليلات العطور لتقارير التصدير
        $perfumeAnalytics = $sales
            ->groupBy('perfume_id')
            ->map(function ($perfumeSales) {
                $first = $perfumeSales->first();
                $categoryName = $first && $first->perfume && $first->perfume->category
                    ? $first->perfume->category->name
                    : null;

                // إجمالي ML
                $totalMl = 0;
                foreach ($perfumeSales as $sale) {
                    if ($sale->size) {
                        $ml = (int) filter_var($sale->size->label, FILTER_SANITIZE_NUMBER_INT);
                        $totalMl += $ml;
                    }
                }

                return [
                    'name' => $first && $first->perfume ? $first->perfume->name : 'غير محدد',
                    'category_name' => $categoryName,
                    'sales_count' => $perfumeSales->count(),
                    'regular_count' => $perfumeSales->where('customer_type', 'regular')->count(),
                    'vip_count' => $perfumeSales->where('customer_type', 'vip')->count(),
                    'total_ml' => $totalMl,
                    'total_amount' => $perfumeSales->sum('price'),
                ];
            })
            ->sortByDesc('total_amount')
            ->values();
        
        $totalCount = $salesData->count();
        
        if ($paginate) {
            $page = $request->get('page', 1);
            $perPage = 50;
            $offset = ($page - 1) * $perPage;
            $salesData = $salesData->slice($offset, $perPage)->values();
        }
        
        return [
            'stats' => $stats,
            'sales' => $salesData,
            'sellers' => $sellerStats,
            'perfumes' => $perfumeAnalytics,
            'total_count' => $totalCount
        ];
    }
    
    private function calculateTotalML($sales)
    {
        $totalML = 0;
        
        foreach ($sales as $sale) {
            if ($sale->is_full_bottle && $sale->size) {
                // العبوة الكاملة - استخدم قيمة الحجم
                $ml = (int) filter_var($sale->size->label, FILTER_SANITIZE_NUMBER_INT);
                $totalML += $ml;
            } elseif ($sale->size) {
                // حجم عادي - استخدم قيمة الحجم
                $ml = (int) filter_var($sale->size->label, FILTER_SANITIZE_NUMBER_INT);
                $totalML += $ml;
            }
        }
        
        return $totalML;
    }
}