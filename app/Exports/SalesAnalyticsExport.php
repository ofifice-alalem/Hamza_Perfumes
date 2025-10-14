<?php

namespace App\Exports;

class SalesAnalyticsExport
{
    protected $data;
    protected $filters;

    public function __construct($data, $filters = [])
    {
        $this->data = $data;
        $this->filters = $filters;
        
        // تحديد التواريخ الفعلية المستخدمة
        $this->actualStartDate = $filters['date_from'] ?? null;
        $this->actualEndDate = $filters['date_to'] ?? null;
        
        // إذا لم يتم تحديد التواريخ، احصل على التواريخ من البيانات
        if (!$this->actualStartDate || !$this->actualEndDate) {
            $sales = $data['sales'] ?? [];
            if (!empty($sales)) {
                // تحويل Collection إلى array إذا لزم الأمر
                $salesArray = is_array($sales) ? $sales : $sales->toArray();
                $dates = array_column($salesArray, 'created_at');
                if (!$this->actualStartDate) {
                    $this->actualStartDate = min($dates);
                }
                if (!$this->actualEndDate) {
                    $this->actualEndDate = max($dates);
                }
            }
        }
    }

    public function generateCSV()
    {
        $filename = 'تحليل_المبيعات_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() {
            $file = fopen('php://output', 'w');
            
            fwrite($file, "\xEF\xBB\xBF");
            
            // معلومات التقرير
            fputcsv($file, ['تقرير المبيعات']);
            fputcsv($file, ['تاريخ التقرير', now()->format('Y-m-d H:i:s')]);
            fputcsv($file, ['البداية', $this->actualStartDate ? date('Y-m-d', strtotime($this->actualStartDate)) : 'غير محدد']);
            fputcsv($file, ['النهاية', $this->actualEndDate ? date('Y-m-d', strtotime($this->actualEndDate)) : 'غير محدد']);
            fputcsv($file, []);
            
            // الإحصائيات
            fputcsv($file, ['الإحصائيات']);
            fputcsv($file, ['إجمالي المبيعات', number_format($this->data['stats']['total_sales'], 2)]);
            fputcsv($file, ['عدد العملاء', $this->data['stats']['total_customers']]);
            fputcsv($file, ['إجمالي الكمية', number_format($this->data['stats']['total_ml'])]);
            fputcsv($file, ['متوسط البيع', number_format($this->data['stats']['avg_sale'], 2)]);
            fputcsv($file, []);
            
            // إحصائيات البائعين
            if (isset($this->data['sellers']) && count($this->data['sellers']) > 0) {
                fputcsv($file, ['البائعين']);
                fputcsv($file, ['اسم البائع', 'عدد المبيعات', 'إجمالي المبلغ', 'متوسط البيع']);
                foreach ($this->data['sellers'] as $seller) {
                    fputcsv($file, [
                        $seller['name'],
                        $seller['sales_count'],
                        number_format($seller['total_amount'], 2),
                        number_format($seller['avg_sale'], 2)
                    ]);
                }
                fputcsv($file, []);
            }
            
            // بيانات المبيعات الفردية
            fputcsv($file, ['بيانات المبيعات']);
            fputcsv($file, [
                '#',
                'العطر',
                'الحجم',
                'نوع العميل',
                'طريقة الدفع',
                'البائع',
                'السعر',
                'التاريخ'
            ]);
            
            foreach ($this->data['sales'] as $index => $sale) {
                fputcsv($file, [
                    $sale['id'],
                    $sale['perfume_name'],
                    $sale['size_label'] ?? ($sale['is_full_bottle'] ? 'عبوة كاملة' : 'غير محدد'),
                    $sale['customer_type'] === 'vip' ? 'VIP' : 'عادي',
                    $sale['payment_method'] === 'card' ? 'بطاقة' : 'كاش',
                    $sale['user_name'] ?? 'غير محدد',
                    number_format($sale['price'], 2),
                    date('Y-m-d H:i', strtotime($sale['created_at']))
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function generateJSON()
    {
        $filename = 'تحليل_المبيعات_' . now()->format('Y-m-d_H-i-s') . '.json';
        
        $data = [
            'report_info' => [
                'report_date' => now()->format('Y-m-d H:i:s'),
                'start_date' => $this->actualStartDate ? date('Y-m-d', strtotime($this->actualStartDate)) : 'غير محدد',
                'end_date' => $this->actualEndDate ? date('Y-m-d', strtotime($this->actualEndDate)) : 'غير محدد'
            ],
            'stats' => $this->data['stats'],
            'sales' => $this->data['sales'],
            'sellers' => $this->data['sellers'] ?? []
        ];
        
        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function generateXML()
    {
        $filename = 'تحليل_المبيعات_' . now()->format('Y-m-d_H-i-s') . '.xml';
        
        $xml = new \SimpleXMLElement('<sales_analytics/>');
        
        // معلومات التقرير
        $reportInfo = $xml->addChild('report_info');
        $reportInfo->addChild('report_date', now()->format('Y-m-d H:i:s'));
        $reportInfo->addChild('start_date', $this->actualStartDate ? date('Y-m-d', strtotime($this->actualStartDate)) : 'غير محدد');
        $reportInfo->addChild('end_date', $this->actualEndDate ? date('Y-m-d', strtotime($this->actualEndDate)) : 'غير محدد');
        
        $stats = $xml->addChild('stats');
        $stats->addChild('total_sales', $this->data['stats']['total_sales']);
        $stats->addChild('total_customers', $this->data['stats']['total_customers']);
        $stats->addChild('total_ml', $this->data['stats']['total_ml']);
        $stats->addChild('avg_sale', $this->data['stats']['avg_sale']);
        
        // إضافة إحصائيات البائعين
        if (isset($this->data['sellers']) && count($this->data['sellers']) > 0) {
            $sellers = $xml->addChild('sellers');
            foreach ($this->data['sellers'] as $seller) {
                $sellerNode = $sellers->addChild('seller');
                $sellerNode->addChild('name', htmlspecialchars($seller['name']));
                $sellerNode->addChild('sales_count', $seller['sales_count']);
                $sellerNode->addChild('total_amount', $seller['total_amount']);
                $sellerNode->addChild('avg_sale', $seller['avg_sale']);
            }
        }
        
        $sales = $xml->addChild('sales');
        foreach ($this->data['sales'] as $sale) {
            $saleNode = $sales->addChild('sale');
            $saleNode->addChild('id', $sale['id']);
            $saleNode->addChild('perfume_name', htmlspecialchars($sale['perfume_name']));
            $saleNode->addChild('size_label', htmlspecialchars($sale['size_label'] ?? ($sale['is_full_bottle'] ? 'عبوة كاملة' : 'غير محدد')));
            $saleNode->addChild('customer_type', $sale['customer_type'] === 'vip' ? 'VIP' : 'عادي');
            $saleNode->addChild('payment_method', $sale['payment_method'] === 'card' ? 'بطاقة' : 'كاش');
            $saleNode->addChild('user_name', htmlspecialchars($sale['user_name'] ?? 'غير محدد'));
            $saleNode->addChild('price', $sale['price']);
            $saleNode->addChild('created_at', date('Y-m-d H:i', strtotime($sale['created_at'])));
        }
        
        return response($xml->asXML())
            ->header('Content-Type', 'application/xml')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}