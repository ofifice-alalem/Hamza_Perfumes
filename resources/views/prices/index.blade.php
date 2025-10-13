@extends('layouts.app')

@section('title', 'الأسعار')
@section('page-title', 'الأسعار')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
            <i class="fas fa-dollar-sign ml-2 text-blue-600"></i>الأسعار
        </h2>
        <p class="text-gray-600 dark:text-gray-300">إدارة أسعار العطور حسب الحجم ونوع العميل</p>
    </div>
    <a href="{{ route('prices.create') }}" class="btn-primary">
        <i class="fas fa-plus ml-2"></i>إضافة سعر جديد
    </a>
</div>

@if($prices->count() > 0)
    <!-- إحصائيات سريعة -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="stats-card bg-gradient-to-r from-blue-500 to-blue-600">
            <div class="stats-value">{{ $prices->count() }}</div>
            <div class="stats-title">إجمالي الأسعار</div>
        </div>
        <div class="stats-card bg-gradient-to-r from-orange-500 to-red-500">
            <div class="stats-value">{{ number_format($prices->avg('price_regular'), 0) }}</div>
            <div class="stats-title">متوسط السعر العادي</div>
        </div>
        <div class="stats-card bg-gradient-to-r from-green-500 to-emerald-500">
            <div class="stats-value">{{ number_format($prices->avg('price_vip'), 0) }}</div>
            <div class="stats-title">متوسط سعر VIP</div>
        </div>
        <div class="stats-card bg-gradient-to-r from-indigo-500 to-purple-600">
            <div class="stats-value">{{ $prices->unique('perfume_id')->count() }}</div>
            <div class="stats-title">عطور مسعرة</div>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">العطر</th>
                            <th class="text-center">سعر العبوة</th>
                            @foreach($sizes as $size)
                                <th class="text-center">{{ $size->label }}</th>
                            @endforeach
                            <th class="text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $groupedPrices = $prices->groupBy('perfume_id');
                        @endphp
                        @foreach($groupedPrices as $perfumeId => $perfumePrices)
                        @php
                            $perfume = $perfumePrices->first()->perfume;
                            $bottlePrice = $perfumePrices->first()->bottle_price;
                            $bottleSize = $perfumePrices->first()->bottle_size;
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            <td class="text-center font-bold">{{ $perfume->id }}</td>
                            <td class="text-right">
                                <span class="text-gray-900 dark:text-white font-medium">
                                    {{ $perfume->name }}
                                </span>
                                @if($bottleSize)
                                    <div class="mt-1 text-xs text-gray-500">{{ $bottleSize }}</div>
                                @endif
                            </td>
                            <td class="text-center">
                                @php
                                    $bottlePriceRegular = $perfumePrices->first()->bottle_price_regular;
                                    $bottlePriceVip = $perfumePrices->first()->bottle_price_vip;
                                @endphp
                                @if($bottlePriceRegular || $bottlePriceVip)
                                    @if($bottlePriceRegular)
                                        <div class="mb-1">
                                            <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold">
                                                عادي: {{ number_format($bottlePriceRegular, 2) }}
                                            </span>
                                        </div>
                                    @endif
                                    @if($bottlePriceVip)
                                        <div>
                                            <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-semibold">
                                                VIP: {{ number_format($bottlePriceVip, 2) }}
                                            </span>
                                        </div>
                                    @endif
                                @else
                                    <span class="text-gray-400">غير محدد</span>
                                @endif
                            </td>
                            @foreach($sizes as $size)
                                @php
                                    $sizePrice = $perfumePrices->where('size_id', $size->id)->first();
                                @endphp
                                <td class="text-center">
                                    @if($sizePrice)
                                        <div class="mb-1">
                                            <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold">
                                                عادي: {{ number_format($sizePrice->price_regular, 2) }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-semibold">
                                                VIP: {{ number_format($sizePrice->price_vip, 2) }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-gray-400">لا يوجد سعر</span>
                                    @endif
                                </td>
                            @endforeach
                            <td class="text-center">
                                <div class="flex gap-2 justify-center">
                                    <a href="{{ route('prices.edit', $perfumePrices->first()) }}" class="w-8 h-8 bg-yellow-500 hover:bg-yellow-600 text-white rounded-full flex items-center justify-center transition-colors">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    <button type="button" class="w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition-colors" onclick="openDeleteModal({{ $perfume->id }})">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full animate-scale-in">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center ml-4">
                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">تأكيد الحذف</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">هذا الإجراء لا يمكن التراجع عنه</p>
                </div>
            </div>
            
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-4">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-yellow-600 dark:text-yellow-400 ml-3 mt-0.5"></i>
                    <div class="text-sm">
                        <strong class="text-yellow-800 dark:text-yellow-200">تحذير:</strong>
                        <span class="text-yellow-700 dark:text-yellow-300">سيتم حذف جميع أسعار العطر "<span id="deletePerfumeName" class="font-bold"></span>" نهائياً.</span>
                    </div>
                </div>
            </div>
            
            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 btn-secondary">
                    <i class="fas fa-times ml-2"></i>إلغاء
                </button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full btn-danger">
                        <i class="fas fa-trash ml-2"></i>حذف نهائياً
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openDeleteModal(perfumeId) {
    const groupedPrices = @json($groupedPrices ?? []);
    const perfumePrices = groupedPrices[perfumeId];
    
    if (perfumePrices && perfumePrices.length > 0) {
        const perfume = perfumePrices[0].perfume;
        document.getElementById('deletePerfumeName').textContent = perfume.name;
        document.getElementById('deleteForm').action = `/prices/${perfumePrices[0].id}`;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.body.style.overflow = '';
}

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeDeleteModal();
});
</script>
@else
    <div class="text-center py-12">
        <i class="fas fa-dollar-sign text-6xl text-gray-400 mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">لا توجد أسعار</h3>
        <p class="text-gray-500 dark:text-gray-400 mb-6">ابدأ بتعيين أسعار للعطور المختلفة</p>
        <a href="{{ route('prices.create') }}" class="btn-primary">
            <i class="fas fa-plus ml-2"></i>إضافة سعر جديد
        </a>
    </div>
@endif

@endsection