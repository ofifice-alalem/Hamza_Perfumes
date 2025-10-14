@extends('layouts.app')

@section('title', 'تعديل تصنيف')
@section('page-title', 'تعديل تصنيف')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">
            <i class="fas fa-edit mr-3 text-blue-600"></i>تعديل تصنيف
        </h2>
        <p class="text-gray-600">تعديل بيانات التصنيف</p>
    </div>
    <a href="{{ route('categories.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-right ml-4"></i>العودة للقائمة
    </a>
</div>

<div class="max-w-4xl mx-auto">
    <div class="card">
        <div class="card-header bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
            <h5 class="text-lg font-bold">
                <i class="fas fa-edit mr-3"></i>تعديل بيانات {{ $category->name }}
            </h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('categories.update', $category) }}">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label for="name" class="form-label">اسم التصنيف</label>
                    <input type="text" id="name" name="name" class="form-input @error('name') error @enderror" value="{{ old('name', $category->name) }}" required>
                    @error('name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <h4 class="text-lg font-semibold text-gray-900 mb-4">الأسعار حسب الأحجام</h4>
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>الحجم</th>
                                <th>السعر العادي (دينار)</th>
                                <th>سعر VIP (دينار)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sizes as $size)
                            @php
                                $existingPrice = $category->prices->where('size_id', $size->id)->first();
                            @endphp
                            <tr>
                                <td>
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">
                                        {{ $size->label }}
                                    </span>
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
                        <i class="fas fa-save mr-3"></i>حفظ التغييرات
                    </button>
                    <a href="{{ route('categories.index') }}" class="flex-1 btn-secondary text-center">
                        <i class="fas fa-times mr-3"></i>إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
