@extends('layouts.app')

@section('title', 'تعديل الأسعار')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
        <div class="mb-4">
            <h4 class="mb-3 fw-bold"><i class="fas fa-edit me-2 text-primary"></i>تعديل الأسعار</h4>
        </div>
        <div class="card-modern mb-4">
            <div class="card-body p-4">
                <form action="{{ route('prices.update', $price) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="perfume_id" value="{{ $price->perfume_id }}">
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">العطر</label>
                        <div class="p-3" style="background: #f8f9fa; border-radius: 12px; border: 2px solid #e9ecef;">
                            <span class="badge px-3 py-2 fw-semibold" style="border-radius: 15px; background: linear-gradient(135deg, #667eea, #764ba2); color: #fff;">
                                <i class="fas fa-spray-can me-1"></i>{{ $price->perfume->name }}
                            </span>
                        </div>
                    </div>

                    <!-- معلومات العبوة الكاملة -->
                    <h5 class="mb-4 fw-bold">معلومات العبوة الكاملة</h5>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="bottle_size" class="form-label fw-semibold">حجم العبوة الكاملة</label>
                            <select class="form-select @error('bottle_size') is-invalid @enderror" 
                                    id="bottle_size" 
                                    name="bottle_size" 
                                    style="border-radius: 12px; padding: 12px 14px;">
                                <option value="">اختر حجم العبوة</option>
                                @foreach($sizes as $size)
                                    <option value="{{ $size->label }}" {{ old('bottle_size', $price->bottle_size) == $size->label ? 'selected' : '' }}>
                                        {{ $size->label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bottle_size')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="bottle_price_regular" class="form-label fw-semibold">سعر العبوة - عادي (دينار)</label>
                            <input type="number" step="0.01" 
                                   class="form-control @error('bottle_price_regular') is-invalid @enderror" 
                                   id="bottle_price_regular" 
                                   name="bottle_price_regular" 
                                   value="{{ old('bottle_price_regular', $price->bottle_price_regular) }}"
                                   placeholder="0.00"
                                   style="border-radius: 12px; padding: 12px 14px;">
                            @error('bottle_price_regular')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="bottle_price_vip" class="form-label fw-semibold">سعر العبوة - VIP (دينار)</label>
                            <input type="number" step="0.01" 
                                   class="form-control @error('bottle_price_vip') is-invalid @enderror" 
                                   id="bottle_price_vip" 
                                   name="bottle_price_vip" 
                                   value="{{ old('bottle_price_vip', $price->bottle_price_vip) }}"
                                   placeholder="0.00"
                                   style="border-radius: 12px; padding: 12px 14px;">
                            @error('bottle_price_vip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <h5 class="mb-4 fw-bold">الأسعار حسب الأحجام المقسمة</h5>
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
                                    $existingPrice = $price->perfume->prices->where('size_id', $size->id)->first();
                                @endphp
                                <tr>
                                    <td class="align-middle">
                                        <span class="badge bg-secondary px-3 py-2 fw-semibold" style="border-radius: 15px;">
                                            <i class="fas fa-ruler me-1"></i>{{ $size->label }}
                                        </span>
                                        <input type="hidden" name="sizes[{{ $size->id }}][size_id]" value="{{ $size->id }}">
                                        @if($existingPrice)
                                            <input type="hidden" name="sizes[{{ $size->id }}][price_id]" value="{{ $existingPrice->id }}">
                                        @endif
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
                            تحديث جميع الأسعار <i class="fas fa-save"></i>
                        </button>
                        <a href="{{ route('prices.index') }}" class="btn btn-secondary btn-modern d-inline-flex align-items-center gap-2" style="border-radius: 12px;">
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