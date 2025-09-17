@extends('layouts.admin')

@section('title', 'Chỉnh sửa người dùng')

@section('styles')
<style>
    .edit-card {
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    .edit-card label {
        font-weight: 500;
    }
    .badge-role {
        padding: 4px 10px;
        border-radius: 12px;
        color: #fff;
        font-size: 0.9rem;
        font-weight: 500;
    }
    .badge-admin { background-color: #dc3545; }
    .badge-customer { background-color: #0d6efd; }
</style>
@endsection

@section('content')
<div class="container">
    <h2 class="mb-4">Chỉnh sửa người dùng</h2>

    <div class="edit-card">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="name">Tên</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
            </div> 

            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                <small>
                    Trạng thái email:
                    @if($user->hasVerifiedEmail())
                        <span class="text-success">Đã xác thực</span>
                    @else
                        <span class="text-danger">Chưa xác thực</span>
                    @endif
                </small>
            </div>

            <div class="form-group mb-3">
                <label for="role">Vai trò</label>
                <select id="role" name="role" class="form-control" required>
                    <option value="customer" @selected($user->role === 'customer')>Người dùng</option>
                    <option value="admin" @selected($user->role === 'admin')>Quản trị</option>
                </select>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">← Quay lại</a>
            </div>
        </form>
    </div>
</div>
@endsection
