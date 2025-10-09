@extends('layouts.app')

@section('title', 'إضافة حجم جديد')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-plus me-2"></i>إضافة حجم جديد</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('sizes.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="label" class="form-label">الحجم</label>
                        <input type="text" class="form-control @error('label') is-invalid @enderror" 
                               id="label" name="label" value="{{ old('label') }}" 
                               placeholder="مثال: 3ml, 7ml, 15ml" required>
                        @error('label')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>حفظ
                        </button>
                        <a href="{{ route('sizes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right me-2"></i>رجوع
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection