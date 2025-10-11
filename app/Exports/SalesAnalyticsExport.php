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
            
            // الإحصائيات
            fputcsv($file, ['الإحصائيات']);
            fputcsv($file, ['إجمالي المبيعات', number_format($this->data['stats']['total_sales'], 2)]);
            fputcsv($file, ['عدد العملاء', $this->data['stats']['total_customers']]);
            fputcsv($file, ['إجمالي الكمية', number_format($this->data['stats']['total_ml'])]);
            fputcsv($file, ['متوسط البيع', number_format($this->data['stats']['avg_sale'], 2)]);
            fputcsv($file, []);
            
            // تحليل المبيعات
            fputcsv($file, ['تحليل المبيعات']);
            fputcsv($file, [
                'الترتيب',
                'اسم العطر',
                'الصنف',
                'عدد المبيعات',
                'الزبائن العاديين',
                'VIP',
                'إجمالي الكمية (مل)',
                'إجمالي المبلغ (دينار)'
            ]);
            
            foreach ($this->data['perfumes'] as $index => $perfume) {
                fputcsv($file, [
                    $index + 1,
                    $perfume['name'],
                    $perfume['category_name'] ?? 'غير مصنف',
                    $perfume['sales_count'],
                    $perfume['regular_count'],
                    $perfume['vip_count'],
                    number_format($perfume['total_ml']),
                    number_format($perfume['total_amount'], 2)
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
            'stats' => $this->data['stats'],
            'perfumes' => $this->data['perfumes']
        ];
        
        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function generateXML()
    {
        $filename = 'تحليل_المبيعات_' . now()->format('Y-m-d_H-i-s') . '.xml';
        
        $xml = new \SimpleXMLElement('<sales_analytics/>');
        
        $stats = $xml->addChild('stats');
        $stats->addChild('total_sales', $this->data['stats']['total_sales']);
        $stats->addChild('total_customers', $this->data['stats']['total_customers']);
        $stats->addChild('total_ml', $this->data['stats']['total_ml']);
        $stats->addChild('avg_sale', $this->data['stats']['avg_sale']);
        
        $perfumes = $xml->addChild('perfumes');
        foreach ($this->data['perfumes'] as $perfume) {
            $perfumeNode = $perfumes->addChild('perfume');
            $perfumeNode->addChild('name', htmlspecialchars($perfume['name']));
            $perfumeNode->addChild('category_name', htmlspecialchars($perfume['category_name'] ?? 'غير مصنف'));
            $perfumeNode->addChild('sales_count', $perfume['sales_count']);
            $perfumeNode->addChild('regular_count', $perfume['regular_count']);
            $perfumeNode->addChild('vip_count', $perfume['vip_count']);
            $perfumeNode->addChild('total_ml', $perfume['total_ml']);
            $perfumeNode->addChild('total_amount', $perfume['total_amount']);
        }
        
        return response($xml->asXML())
            ->header('Content-Type', 'application/xml')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}