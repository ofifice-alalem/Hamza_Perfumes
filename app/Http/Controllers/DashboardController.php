<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Perfume;
use App\Models\Sale;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index()
    {
        $categories = Category::with('perfumes')->get();
        $uncategorizedPerfumes = Perfume::whereNull('category_id')->get();
        
        return view('dashboard', compact('categories', 'uncategorizedPerfumes'));
    }
    
    public function getSalesAnalytics(Request $request)
    {
        $data = $this->getSalesData($request);
        return response()->json($data);
    }
    
    public function exportSalesAnalytics(Request $request)
    {
        $data = $this->getSalesData($request);
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
    
    private function getSalesData(Request $request)
    {
        $query = Sale::with(['perfume.category', 'size']);
        
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        if ($request->customer_type) {
            $query->where('customer_type', $request->customer_type);
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
        
        $sales = $query->get();
        
        $stats = [
            'total_sales' => $sales->sum('price'),
            'total_customers' => $sales->count(),
            'total_ml' => $this->calculateTotalML($sales),
            'avg_sale' => $sales->count() > 0 ? $sales->avg('price') : 0
        ];
        
        $perfumeStats = $sales->groupBy('perfume_id')->map(function ($perfumeSales) {
            $perfume = $perfumeSales->first()->perfume;
            return [
                'name' => $perfume->name,
                'category_name' => $perfume->category ? $perfume->category->name : null,
                'sales_count' => $perfumeSales->count(),
                'regular_count' => $perfumeSales->where('customer_type', 'regular')->count(),
                'vip_count' => $perfumeSales->where('customer_type', 'vip')->count(),
                'total_amount' => $perfumeSales->sum('price'),
                'total_ml' => $this->calculateTotalML($perfumeSales),
                'avg_price' => $perfumeSales->avg('price')
            ];
        })->values();
        
        $sortBy = $request->sort_by ?? 'sales_count';
        $perfumeStats = $perfumeStats->sortByDesc($sortBy)->values();
        
        return [
            'stats' => $stats,
            'perfumes' => $perfumeStats
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