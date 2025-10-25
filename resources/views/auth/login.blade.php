@extends('layouts.user')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="login-card p-5 shadow-lg">
        <h2 class="text-center mb-4 fw-bold text-primary">Đăng nhập</h2>

        @if(session('warning'))
            <div class="alert alert-warning rounded-pill">
                {{ session('warning') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger rounded-pill">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email</label>
                <input type="email" name="email" id="email" class="form-control input-field" required value="{{ old('email') }}">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Mật khẩu</label>
                <input type="password" name="password" id="password" class="form-control input-field" required>
            </div>

            <div class="form-check mb-4">
                <input type="checkbox" name="remember" id="remember" class="form-check-input">
                <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
            </div>

            <button type="submit" class="btn btn-gradient w-100 fw-bold">Đăng nhập</button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('register') }}" class="text-decoration-none text-muted">Chưa có tài khoản? Đăng ký ngay</a>
        </div>
    </div>
</div>

<style>
body {
    background: #f0f4f8;
    font-family: 'Inter', sans-serif;
}

.login-card {
    background: #ffffff;
    border-radius: 20px;
    max-width: 400px;
    width: 100%;
    transition: all 0.3s ease;
}

.login-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.08);
}

.input-field {
    border-radius: 12px;
    border: 1px solid #d1d5db;
    padding: 12px 14px;
    transition: border-color 0.3s, box-shadow 0.3s;
}

.input-field:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.2);
    outline: none;
}

.btn-gradient {
    background: linear-gradient(135deg, #4f46e5, #3b82f6);
    color: #fff;
    border-radius: 12px;
    padding: 12px 0;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.btn-gradient:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 20px rgba(59,130,246,0.3);
}

.alert {
    font-size: 0.9rem;
    border-radius: 12px;
    padding: 10px 15px;
}

.text-primary {
    color: #4f46e5 !important;
}
</style>
@endsection
