@extends('layouts.app')

@section('title', 'العطور')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-spray-can me-2"></i>العطور</h2>
    <a href="{{ route('perfumes.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>إضافة عطر جديد
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($perfumes->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم العطر</th>
                            <th>التصنيف</th>
                            <th>تاريخ الإضافة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($perfumes as $perfume)
                        <tr>
                            <td>{{ $perfume->id }}</td>
                            <td>{{ $perfume->name }}</td>
                            <td>
                                @if($perfume->category)
                                    <span class="badge bg-info">{{ $perfume->category->name }}</span>
                                @else
                                    <span class="text-muted">غير محدد</span>
                                @endif
                            </td>
                            <td>{{ $perfume->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('perfumes.edit', $perfume) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('perfumes.destroy', $perfume) }}" method="POST" class="d-inline">
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
                <i class="fas fa-spray-can fa-3x text-muted mb-3"></i>
                <p class="text-muted">لا توجد عطور مضافة بعد</p>
                <a href="{{ route('perfumes.create') }}" class="btn btn-primary">إضافة أول عطر</a>
            </div>
        @endif
    </div>
</div>
@endsection