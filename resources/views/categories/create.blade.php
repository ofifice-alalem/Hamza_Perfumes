@extends('layouts.app')

@section('title', 'إضافة تصنيف جديد')
@section('page-title', 'إضافة تصنيف جديد')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
            <i class="fas fa-tags ml-2 text-blue-600"></i>إضافة تصنيف جديد
        </h2>
        <p class="text-gray-600 dark:text-gray-300">إضافة تصنيف جديد للعطور</p>
    </div>
    <a href="{{ route('categories.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-right ml-2"></i>العودة للقائمة
    </a>
</div>

<div class="max-w-2xl mx-auto">
    <div class="card">
        <div class="card-header bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
            <h5 class="text-lg font-bold">
                <i class="fas fa-tags ml-2"></i>بيانات التصنيف الجديد
            </h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('categories.store') }}">
                @csrf
                
                <div class="mb-6">
                    <label for="name" class="form-label">اسم التصنيف</label>
                    <input type="text" id="name" name="name" class="form-input @error('name') error @enderror" value="{{ old('name') }}" required placeholder="مثال: عطور نسائية">
                    @error('name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="flex-1 btn-success">
                        <i class="fas fa-save ml-2"></i>حفظ التصنيف
                    </button>
                    <a href="{{ route('categories.index') }}" class="flex-1 btn-secondary text-center">
                        <i class="fas fa-times ml-2"></i>إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection