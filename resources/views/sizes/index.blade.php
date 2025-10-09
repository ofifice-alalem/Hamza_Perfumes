@extends('layouts.app')

@section('title', 'الأحجام')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-ruler me-2"></i>الأحجام</h2>
    <a href="{{ route('sizes.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>إضافة حجم جديد
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($sizes->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الحجم</th>
                            <th>تاريخ الإضافة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sizes as $size)
                        <tr>
                            <td>{{ $size->id }}</td>
                            <td>{{ $size->label }}</td>
                            <td>{{ $size->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('sizes.edit', $size) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('sizes.destroy', $size) }}" method="POST" class="d-inline">
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
                <i class="fas fa-ruler fa-3x text-muted mb-3"></i>
                <p class="text-muted">لا توجد أحجام مضافة بعد</p>
                <a href="{{ route('sizes.create') }}" class="btn btn-primary">إضافة أول حجم</a>
            </div>
        @endif
    </div>
</div>
@endsection