@extends('layouts.app')

@section('title', 'تعديل التصنيف والأسعار')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
        <div class="card-modern mb-4">
            <div class="card-header d-flex align-items-center" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 15px 15px 0 0;">
                <h5 class="mb-0"></h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <h4 class="mb-0 fw-bold">تعديل التصنيف: {{ $category->name }}</h4>
                    </div>
                    
                    <!-- اسم التصنيف -->
                    <div class="mb-4">
                        <label for="name" class="form-label fw-semibold">اسم التصنيف</label>
                        <div class="position-relative">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $category->name) }}" required
                                   placeholder="مثال: عطور نسائية" 
                                   style="border-radius: 12px; padding: 12px 14px;">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <h5 class="mb-4 fw-bold">الأسعار حسب الأحجام</h5>
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th class="border-0">الحجم</th>
                                    <th class="border-0">السعر العادي (دينار)</th>
                                    <th class="border-0">سعر VIP (دينار)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sizes as $size)
                                @php
                                    $existingPrice = $category->prices->where('size_id', $size->id)->first();
                                @endphp
                                <tr>
                                    <td class="align-middle">
                                        <span class="badge bg-secondary px-3 py-2 fw-semibold" style="border-radius: 15px;">
                                            <i class="fas fa-ruler me-1"></i>{{ $size->label }}
                                        </span>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" 
                                               class="form-control @error('sizes.'.$size->id.'.price_regular') is-invalid @enderror" 
                                               name="sizes[{{ $size->id }}][price_regular]" 
                                               value="{{ $existingPrice ? $existingPrice->price_regular : '' }}"
                                               placeholder="0.00"
                                               style="border-radius: 10px; padding: 10px 12px;">
                                        @error('sizes.'.$size->id.'.price_regular')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" 
                                               class="form-control @error('sizes.'.$size->id.'.price_vip') is-invalid @enderror" 
                                               name="sizes[{{ $size->id }}][price_vip]" 
                                               value="{{ $existingPrice ? $existingPrice->price_vip : '' }}"
                                               placeholder="0.00"
                                               style="border-radius: 10px; padding: 10px 12px;">
                                        @error('sizes.'.$size->id.'.price_vip')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-modern d-inline-flex align-items-center gap-2" style="border-radius: 12px;">
                            تحديث التصنيف والأسعار <i class="fas fa-save"></i>
                        </button>
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary btn-modern d-inline-flex align-items-center gap-2" style="border-radius: 12px;">
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

.table-modern {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.table-modern thead {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.table-modern thead th {
    padding: 20px 15px;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table-modern tbody tr {
    border-bottom: 1px solid #f0f0f0;
}

.table-modern tbody tr:last-child {
    border-bottom: none;
}

.table-modern tbody td {
    padding: 20px 15px;
    vertical-align: middle;
}

.table-modern tbody tr:hover {
    background-color: #f8f9fa;
}
</style>
@endsection