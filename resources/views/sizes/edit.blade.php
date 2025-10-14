@extends('layouts.app')

@section('title', 'تعديل حجم')
@section('page-title', 'تعديل حجم')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
            <i class="fas fa-edit mr-3 text-blue-600"></i>تعديل حجم
        </h2>
        <p class="text-gray-600 dark:text-gray-300">تعديل بيانات الحجم</p>
    </div>
    <a href="{{ route('sizes.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-right ml-2"></i>العودة للقائمة
    </a>
</div>

<div class="max-w-2xl mx-auto">
    <div class="card">
        <div class="card-header bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
            <h5 class="text-lg font-bold">
                <i class="fas fa-edit mr-3"></i>تعديل بيانات {{ $size->label }}
            </h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('sizes.update', $size) }}">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label for="label" class="form-label">الحجم</label>
                    <input type="text" id="label" name="label" class="form-input @error('label') error @enderror" value="{{ old('label', $size->label) }}" required placeholder="مثال: 3ml, 7ml, 15ml">
                    @error('label')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="flex-1 btn-warning">
                        <i class="fas fa-save mr-3"></i>حفظ التغييرات
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