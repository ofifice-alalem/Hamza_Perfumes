@extends('layouts.app')

@section('title', 'المستخدمين')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="fas fa-users me-2 text-primary"></i>المستخدمين</h2>
        <p class="text-muted mb-0">إدارة مستخدمي النظام</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary btn-modern">
        <i class="fas fa-plus me-2"></i>إضافة مستخدم جديد
    </a>
</div>

<div class="card-modern">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern mb-0">
                <thead>
                    <tr>
                        <th class="border-0">#</th>
                        <th class="border-0">الاسم</th>
                        <th class="border-0">اسم المستخدم</th>
                        <th class="border-0">الصلاحية</th>
                        <th class="border-0 text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="fw-bold">{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->username }}</td>
                        <td>
                            @if($user->role === 'super-admin')
                                <span class="badge bg-danger px-3 py-2">مدير عام</span>
                            @elseif($user->role === 'admin')
                                <span class="badge bg-warning px-3 py-2">مدير</span>
                            @else
                                <span class="badge bg-info px-3 py-2">بائع</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%;">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                <button type="button" class="btn btn-sm btn-danger d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%;" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Modals -->
@foreach($users as $user)
@if($user->id !== auth()->id())
<div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
            <div class="modal-header d-flex justify-content-between align-items-center" style="border-bottom: none; padding: 30px 30px 20px;">
                <div class="d-flex align-items-center" style="flex: 1;">
                    <div class="delete-icon me-3" style="width: 60px; height: 60px; border: 3px solid #ff6b6b; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-1">تأكيد الحذف</h5>
                        <p class="text-muted mb-0">هذا الإجراء لا يمكن التراجع عنه</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <div class="alert alert-warning d-flex align-items-center" style="border-radius: 15px; border: none; background: linear-gradient(135deg, #fff3cd, #ffeaa7);">
                    <i class="fas fa-info-circle me-3" style="color: #856404; font-size: 20px;"></i>
                    <div>
                        <strong>تحذير:</strong> سيتم حذف المستخدم "<span class="fw-bold text-dark">{{ $user->username }}</span>" نهائياً من النظام.
                    </div>
                </div>
                <div class="user-details p-3" style="background: #f8f9fa; border-radius: 15px; margin-top: 15px;">
                    <h6 class="fw-bold mb-3 text-dark">تفاصيل المستخدم:</h6>
                    <div class="row">
                        <div class="col-6">
                            <div class="detail-item mb-2">
                                <span class="text-muted">اسم المستخدم:</span>
                                <span class="fw-semibold text-dark ms-2">{{ $user->username }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="detail-item mb-2">
                                <span class="text-muted">الصلاحية:</span>
                                <span class="fw-semibold text-dark ms-2">
                                    @if($user->role === 'super-admin')
                                        مدير عام
                                    @elseif($user->role === 'admin')
                                        مدير
                                    @else
                                        بائع
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: none; padding: 20px 30px 30px;">
                <div class="d-flex gap-3 w-100">
                    <button type="button" class="btn btn-secondary btn-modern flex-fill" data-bs-dismiss="modal" style="border-radius: 12px; padding: 12px;">
                        <i class="fas fa-times me-2"></i>إلغاء
                    </button>
                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="flex-fill">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-modern w-100" style="border-radius: 12px; padding: 12px; background: linear-gradient(135deg, #ff6b6b, #ee5a52); border: none;">
                            <i class="fas fa-trash me-2"></i>حذف نهائياً
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach
@endsection