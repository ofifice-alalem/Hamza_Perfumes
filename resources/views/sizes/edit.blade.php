@extends('layouts.app')

@section('title', 'تعديل الحجم')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-edit me-2"></i>تعديل الحجم</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('sizes.update', $size) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="label" class="form-label">الحجم</label>
                        <input type="text" class="form-control @error('label') is-invalid @enderror" 
                               id="label" name="label" value="{{ old('label', $size->label) }}" required>
                        @error('label')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>تحديث
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