@extends('layouts.app')

@section('title', 'تعديل الحجم')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
        <div class="card-modern mb-4">
            <div class="card-header d-flex align-items-center" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 15px 15px 0 0;">
                <h5 class="mb-0"></h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('sizes.update', $size) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <h4 class="mb-0 fw-bold">تعديل الحجم: {{ $size->label }}</h4>
                    </div>
                    
                    <!-- الحجم -->
                    <div class="mb-4">
                        <label for="label" class="form-label fw-semibold">الحجم</label>
                        <div class="position-relative">
                            <input type="text" class="form-control @error('label') is-invalid @enderror" 
                                   id="label" name="label" value="{{ old('label', $size->label) }}" 
                                   placeholder="مثال: 3ml, 7ml, 15ml" required
                                   style="border-radius: 12px; padding: 12px 14px;">
                            @error('label')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-modern d-inline-flex align-items-center gap-2" style="border-radius: 12px;">
                            تحديث الحجم <i class="fas fa-save"></i>
                        </button>
                        <a href="{{ route('sizes.index') }}" class="btn btn-secondary btn-modern d-inline-flex align-items-center gap-2" style="border-radius: 12px;">
                            رجوع للقائمة <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.btn-modern {
    border-radius: 12px;
    padding: 10px 20px;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
}
.btn-modern i { font-size: .95rem; }
.btn-modern:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(0,0,0,0.12); }
.btn-primary.btn-modern { background: linear-gradient(135deg, #667eea, #764ba2); border: none; }
.btn-primary.btn-modern:hover { background: linear-gradient(135deg, #5a6fe0, #6b3fb1); }
.btn-secondary.btn-modern { background: #f1f3f5; color: #343a40; border: none; }
.btn-secondary.btn-modern:hover { background: #e9ecef; }
</style>
@endsection