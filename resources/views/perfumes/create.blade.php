@extends('layouts.app')

@section('title', 'إضافة عطر جديد')
@section('page-title', 'إضافة عطر جديد')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">
            <i class="fas fa-spray-can mr-3 text-blue-600"></i>إضافة عطر جديد
        </h2>
        <p class="text-gray-600">إضافة عطر جديد للنظام</p>
    </div>
    <a href="{{ route('perfumes.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-right ml-4"></i>العودة للقائمة
    </a>
</div>

<div class="max-w-2xl mx-auto">
    <div class="card">
        <div class="card-header bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
            <h5 class="text-lg font-bold">
                <i class="fas fa-spray-can mr-3"></i>بيانات العطر الجديد
            </h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('perfumes.store') }}">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="form-label">اسم العطر</label>
                        <input type="text" id="name" name="name" class="form-input @error('name') error @enderror" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="category_id" class="form-label">التصنيف</label>
                        <select id="category_id" name="category_id" class="form-select @error('category_id') error @enderror">
                            <option value="">اختر التصنيف</option>
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="submit" class="flex-1 btn-success">
                        <i class="fas fa-save mr-3"></i>حفظ العطر
                    </button>
                    <a href="{{ route('perfumes.index') }}" class="flex-1 btn-secondary text-center">
                        <i class="fas fa-times mr-3"></i>إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
