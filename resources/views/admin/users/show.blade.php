@extends('layouts.admin')

@section('title', 'Chi tiết người dùng')

@section('styles')
<style>
    .user-card {
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        background-color: #fff;
        padding: 20px;
    }
    .user-card p {
        font-size: 16px;
        margin-bottom: 8px;
    }
    .badge-role {
        padding: 4px 10px;
        border-radius: 12px;
        color: #fff;
        font-weight: 500;
        font-size: 0.9rem;
    }
    .badge-admin { background-color: #dc3545; } /* đỏ */
    .badge-customer { background-color: #0d6efd; } /* xanh */
    .action-btns a, .action-btns form {
        margin-right: 5px;
    }
</style>
@endsection

@section('content')
<div class="container">
    <h2 class="mb-4">Thông tin người dùng</h2>

    <div class="user-card mb-3">
        <p><strong>ID:</strong> {{ $user->id }}</p>
        <p><strong>Tên:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p>
            <strong>Vai trò:</strong>
            <span class="badge-role {{ $user->role === 'admin' ? 'badge-admin' : 'badge-customer' }}">
                {{ ucfirst($user->role) }}
            </span>
        </p>
        <p><strong>Ngày tạo:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
        <p>
            <strong>Email xác thực:</strong>
            @if($user->hasVerifiedEmail())
                <span class="text-success">Đã xác thực</span>
            @else
                <span class="text-danger">Chưa xác thực</span>
            @endif
        </p>
    </div>

    <div class="action-btns mb-3">
        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">✏️ Sửa</a>
        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button onclick="return confirm('Bạn chắc chắn xóa?')" class="btn btn-danger">🗑️ Xóa</button>
        </form>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">← Quay lại</a>
    </div>
</div>
@endsection
