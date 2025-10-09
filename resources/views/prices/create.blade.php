@extends('layouts.app')

@section('title', 'إضافة أسعار جديدة')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-plus me-2"></i>إضافة أسعار جديدة</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('prices.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="perfume_id" class="form-label">العطر</label>
                        <select class="form-select @error('perfume_id') is-invalid @enderror" 
                                id="perfume_id" name="perfume_id" required>
                            <option value="">اختر العطر</option>
                            @foreach($perfumes as $perfume)
                                <option value="{{ $perfume->id }}" {{ old('perfume_id') == $perfume->id ? 'selected' : '' }}>
                                    {{ $perfume->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('perfume_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <h5 class="mb-3">الأسعار حسب الأحجام</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>الحجم</th>
                                    <th>السعر العادي (ريال)</th>
                                    <th>سعر VIP (ريال)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sizes as $size)
                                <tr>
                                    <td class="align-middle">
                                        <strong>{{ $size->label }}</strong>
                                        <input type="hidden" name="sizes[{{ $size->id }}][size_id]" value="{{ $size->id }}">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" 
                                               class="form-control" 
                                               name="sizes[{{ $size->id }}][price_regular]" 
                                               value="{{ old('sizes.'.$size->id.'.price_regular') }}"
                                               placeholder="0.00">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" 
                                               class="form-control" 
                                               name="sizes[{{ $size->id }}][price_vip]" 
                                               value="{{ old('sizes.'.$size->id.'.price_vip') }}"
                                               placeholder="0.00">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>حفظ جميع الأسعار
                        </button>
                        <a href="{{ route('prices.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right me-2"></i>رجوع
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('perfume_id').addEventListener('change', function() {
    const perfumeId = this.value;
    if (perfumeId) {
        fetch(`/api/get-perfume-prices/${perfumeId}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(price => {
                    const regularInput = document.querySelector(`input[name="sizes[${price.size_id}][price_regular]"]`);
                    const vipInput = document.querySelector(`input[name="sizes[${price.size_id}][price_vip]"]`);
                    if (regularInput) regularInput.value = price.price_regular;
                    if (vipInput) vipInput.value = price.price_vip;
                });
            })
            .catch(() => {});
    } else {
        document.querySelectorAll('input[name*="[price_regular]"], input[name*="[price_vip]"]').forEach(input => {
            input.value = '';
        });
    }
});
</script>
@endsection