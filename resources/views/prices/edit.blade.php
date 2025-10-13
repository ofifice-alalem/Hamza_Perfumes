@extends('layouts.app')

@section('title', 'تعديل الأسعار')
@section('page-title', 'تعديل الأسعار')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
            <i class="fas fa-edit ml-2 text-blue-600"></i>تعديل الأسعار
        </h2>
        <p class="text-gray-600 dark:text-gray-300">تعديل أسعار العطر</p>
    </div>
    <a href="{{ route('prices.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-right ml-2"></i>العودة للقائمة
    </a>
</div>

<div class="max-w-4xl mx-auto">
    <div class="card">
        <div class="card-header bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
            <h5 class="text-lg font-bold">
                <i class="fas fa-edit ml-2"></i>تعديل أسعار {{ $price->perfume->name }}
            </h5>
        </div>
        <div class="card-body">
                <form action="{{ route('prices.update', $price) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="perfume_id" value="{{ $price->perfume_id }}">
                    
                    <div class="mb-6">
                        <label class="form-label">العطر</label>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border-2 border-gray-200 dark:border-gray-600">
                            <span class="text-gray-900 dark:text-white font-medium">
                                {{ $price->perfume->name }}
                            </span>
                        </div>
                    </div>

                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">معلومات العبوة الكاملة</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label for="bottle_size" class="form-label">حجم العبوة الكاملة</label>
                            <select class="form-select @error('bottle_size') error @enderror" id="bottle_size" name="bottle_size">
                                <option value="">اختر حجم العبوة</option>
                                @foreach($sizes as $size)
                                    <option value="{{ $size->label }}" {{ old('bottle_size', $price->bottle_size) == $size->label ? 'selected' : '' }}>
                                        {{ $size->label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bottle_size')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="bottle_price_regular" class="form-label">سعر العبوة - عادي (دينار)</label>
                            <input type="number" step="0.01" class="form-input @error('bottle_price_regular') error @enderror" 
                                   id="bottle_price_regular" name="bottle_price_regular" 
                                   value="{{ old('bottle_price_regular', $price->bottle_price_regular) }}" placeholder="0.00">
                            @error('bottle_price_regular')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="bottle_price_vip" class="form-label">سعر العبوة - VIP (دينار)</label>
                            <input type="number" step="0.01" class="form-input @error('bottle_price_vip') error @enderror" 
                                   id="bottle_price_vip" name="bottle_price_vip" 
                                   value="{{ old('bottle_price_vip', $price->bottle_price_vip) }}" placeholder="0.00">
                            @error('bottle_price_vip')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">الأسعار حسب الأحجام</h4>
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>الحجم</th>
                                    <th>السعر العادي (ر.س)</th>
                                    <th>سعر VIP (ر.س)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sizes as $size)
                                @php
                                    $existingPrice = $price->perfume->prices->where('size_id', $size->id)->first();
                                @endphp
                                <tr>
                                    <td>
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                            {{ $size->label }}
                                        </span>
                                        <input type="hidden" name="sizes[{{ $size->id }}][size_id]" value="{{ $size->id }}">
                                        @if($existingPrice)
                                            <input type="hidden" name="sizes[{{ $size->id }}][price_id]" value="{{ $existingPrice->id }}">
                                        @endif
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" 
                                               class="form-input @error('sizes.'.$size->id.'.price_regular') error @enderror" 
                                               name="sizes[{{ $size->id }}][price_regular]" 
                                               value="{{ $existingPrice ? $existingPrice->price_regular : '' }}"
                                               placeholder="0.00">
                                        @error('sizes.'.$size->id.'.price_regular')
                                            <div class="form-error">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" 
                                               class="form-input @error('sizes.'.$size->id.'.price_vip') error @enderror" 
                                               name="sizes[{{ $size->id }}][price_vip]" 
                                               value="{{ $existingPrice ? $existingPrice->price_vip : '' }}"
                                               placeholder="0.00">
                                        @error('sizes.'.$size->id.'.price_vip')
                                            <div class="form-error">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="flex gap-3 mt-6">
                        <button type="submit" class="flex-1 btn-warning">
                            <i class="fas fa-save ml-2"></i>تحديث جميع الأسعار
                        </button>
                        <a href="{{ route('prices.index') }}" class="flex-1 btn-secondary text-center">
                            <i class="fas fa-arrow-right ml-2"></i>رجوع للقائمة
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection