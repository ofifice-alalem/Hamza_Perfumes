@extends('layouts.app')

@section('title', 'إضافة حجم جديد')
@section('page-title', 'إضافة حجم جديد')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
            <i class="fas fa-ruler mr-3 text-blue-600"></i>إضافة حجم جديد
        </h2>
        <p class="text-gray-600 dark:text-gray-300">إضافة حجم جديد للعطور</p>
    </div>
    <a href="{{ route('sizes.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-right mr-3"></i>العودة للقائمة
    </a>
</div>

<div class="max-w-2xl mx-auto">
    <div class="card">
        <div class="card-header bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
            <h5 class="text-lg font-bold">
                <i class="fas fa-ruler mr-3"></i>بيانات الحجم الجديد
            </h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('sizes.store') }}">
                @csrf
                
                <div class="mb-6">
                    <label for="label" class="form-label">الحجم</label>
                    <input type="text" id="label" name="label" class="form-input @error('label') error @enderror" value="{{ old('label') }}" required placeholder="مثال: 3ml, 7ml, 15ml">
                    @error('label')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="flex-1 btn-success">
                        <i class="fas fa-save mr-3"></i>حفظ الحجم
                    </button>
                    <a href="{{ route('sizes.index') }}" class="flex-1 btn-secondary text-center">
                        <i class="fas fa-times mr-3"></i>إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection