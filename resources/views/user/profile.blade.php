@extends('layouts.user')

@section('title', 'Hồ sơ cá nhân')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <h2 class="mb-4 text-center">🧑 Hồ sơ cá nhân</h2>

            {{-- Hiển thị level khách hàng --}}
            <div class="mb-3 text-center">
                <span class="badge bg-info fs-6">
                    Level khách hàng: {{ $user->level ?? 'Chưa xác định' }}
                </span>
            </div>

            {{-- Thông báo thành công --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Thông báo lỗi chung --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Đã có lỗi xảy ra!</strong>
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body">
                    <form action="{{ route('user.profile.update') }}" method="POST">
                        @csrf

                        {{-- Họ tên --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">Họ tên</label>
                            <input type="text" name="name" id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Email (không thể đổi) --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                        </div>

                        {{-- Địa chỉ --}}
                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <input type="text" name="address" id="address"
                                   class="form-control @error('address') is-invalid @enderror"
                                   value="{{ old('address', $user->address) }}">
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Số điện thoại --}}
                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="text" name="phone" id="phone"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $user->phone) }}">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <hr>
                        <h5 class="mb-3">🔑 Đổi mật khẩu (tùy chọn)</h5>

                        {{-- Mật khẩu mới --}}
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu mới</label>
                            <input type="password" name="password" id="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   autocomplete="new-password">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Xác nhận mật khẩu --}}
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="form-control" autocomplete="new-password">
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">⬅ Quay lại</a>
                            <button type="submit" class="btn btn-primary"
                                    onclick="return confirm('Bạn có chắc chắn muốn lưu thay đổi?')">
                                💾 Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
