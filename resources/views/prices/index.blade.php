@extends('layouts.app')

@section('title', 'الأسعار')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-tags me-2"></i>الأسعار</h2>
    <a href="{{ route('prices.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>إضافة سعر جديد
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($prices->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>العطر</th>
                            <th>التصنيف</th>
                            <th>الحجم</th>
                            <th>السعر العادي</th>
                            <th>سعر VIP</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prices as $price)
                        <tr>
                            <td>{{ $price->id }}</td>
                            <td>{{ $price->perfume->name }}</td>
                            <td>
                                @if($price->perfume->category)
                                    <span class="badge bg-info">{{ $price->perfume->category->name }}</span>
                                @else
                                    <span class="text-muted">غير محدد</span>
                                @endif
                            </td>
                            <td>{{ $price->size->label }}</td>
                            <td>{{ number_format($price->price_regular, 2) }} ريال</td>
                            <td>{{ number_format($price->price_vip, 2) }} ريال</td>
                            <td>
                                <a href="{{ route('prices.edit', $price) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('prices.destroy', $price) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد؟')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                <p class="text-muted">لا توجد أسعار مضافة بعد</p>
                <a href="{{ route('prices.create') }}" class="btn btn-primary">إضافة أول سعر</a>
            </div>
        @endif
    </div>
</div>
@endsection