@extends('layouts.app')

@section('title', 'تعديل التصنيف والأسعار')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-edit me-2"></i>تعديل التصنيف: {{ $category->name }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="name" class="form-label">اسم التصنيف</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $category->name) }}" required>
                        @error('name')
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
                                @php
                                    $existingPrice = $category->prices->where('size_id', $size->id)->first();
                                @endphp
                                <tr>
                                    <td class="align-middle">
                                        <strong>{{ $size->label }}</strong>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" 
                                               class="form-control" 
                                               name="sizes[{{ $size->id }}][price_regular]" 
                                               value="{{ $existingPrice ? $existingPrice->price_regular : '' }}"
                                               placeholder="0.00">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" 
                                               class="form-control" 
                                               name="sizes[{{ $size->id }}][price_vip]" 
                                               value="{{ $existingPrice ? $existingPrice->price_vip : '' }}"
                                               placeholder="0.00">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>تحديث التصنيف والأسعار
                        </button>
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right me-2"></i>رجوع
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection