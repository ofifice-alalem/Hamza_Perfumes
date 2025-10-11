@extends('layouts.app')

@section('title', 'تعديل العطر')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
        <div class="card-modern mb-4">
            <div class="card-header d-flex align-items-center" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 15px 15px 0 0;">
                <h5 class="mb-0"></h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('perfumes.update', $perfume) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <h4 class="mb-0 fw-bold">تعديل العطر</h4>
                    </div>

                    <!-- اسم العطر -->
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">اسم العطر</label>
                        <div class="position-relative">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $perfume->name) }}" required
                                   placeholder="مثال: عطر المسك الأبيض" 
                                   style="border-radius: 12px; padding: 12px 14px;">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- التصنيف -->
                    <div class="mb-4">
                        <label for="category_id" class="form-label fw-semibold">التصنيف</label>
                        <div class="position-relative">
                            <i class="fas fa-tags position-absolute" style="right: 12px; top: 50%; transform: translateY(-50%); color:#6c757d; pointer-events: none;"></i>
                            <select class="form-select pretty-select @error('category_id') is-invalid @enderror" 
                                    id="category_id" name="category_id"
                                    style="border-radius: 14px; padding: 12px 44px 12px 36px; appearance: none; -webkit-appearance: none; -moz-appearance: none; background: #f8f9fb; border: 2px solid #edf0f3;">
                                <option value="">&nbsp;&nbsp;&nbsp;&nbsp;اختر التصنيف (اختياري)</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $perfume->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down position-absolute" style="left: 12px; top: 50%; transform: translateY(-50%); color:#6c757d; pointer-events: none;"></i>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-modern d-inline-flex align-items-center gap-2" style="border-radius: 12px;">
                            تحديث <i class="fas fa-save"></i>
                        </button>
                        <a href="{{ route('perfumes.index') }}" class="btn btn-secondary btn-modern d-inline-flex align-items-center gap-2" style="border-radius: 12px;">
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

.pretty-select { transition: border-color .2s ease, box-shadow .2s ease, background .2s ease; }
.pretty-select:hover { border-color: #dfe3e8; }
.pretty-select:focus { border-color: #667eea; box-shadow: 0 0 0 4px rgba(102,126,234,0.12); background: #ffffff; }
.pretty-select option { padding: 8px; }
</style>
@endsection