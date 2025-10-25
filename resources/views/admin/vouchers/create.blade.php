@extends('layouts.admin')

@section('title', 'Tạo Voucher Mới')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">➕ Tạo Voucher Mới</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.vouchers.store') }}" method="POST" class="voucher-form">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-semibold">Mã Voucher</label>
            <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="VD: SALE10" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Loại giảm giá</label>
            <select name="discount_type" class="form-select" required>
                <option value="percent" {{ old('discount_type')=='percent' ? 'selected' : '' }}>Phần trăm (%)</option>
                <option value="fixed" {{ old('discount_type')=='fixed' ? 'selected' : '' }}>Số tiền cố định (đ)</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Giá trị giảm</label>
            <input type="number" name="discount_value" class="form-control" value="{{ old('discount_value') }}" min="0" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Số lượng sử dụng tối đa</label>
            <input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit', 1) }}" min="1" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Ngày hết hạn (tùy chọn)</label>
            <input type="date" name="expires_at" class="form-control" value="{{ old('expires_at') }}">
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Nhóm khách hàng</label>
            <select name="customer_group" class="form-select">
                <option value="">Tất cả</option>
                <option value="Bronze" {{ old('customer_group')=='Bronze' ? 'selected' : '' }}>Bronze</option>
                <option value="Silver" {{ old('customer_group')=='Silver' ? 'selected' : '' }}>Silver</option>
                <option value="Gold" {{ old('customer_group')=='Gold' ? 'selected' : '' }}>Gold</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary w-100">Tạo Voucher</button>
    </form>
</div>

{{-- CSS riêng cho form voucher --}}
<style>
.voucher-form {
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
.voucher-form .form-control,
.voucher-form .form-select {
    border-radius: 8px;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.voucher-form .form-control:focus,
.voucher-form .form-select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 0.2rem rgba(37,99,235,0.25);
}
.voucher-form label {
    color: #1e3a8a;
}
.voucher-form button {
    font-size: 16px;
    padding: 12px;
    border-radius: 8px;
    transition: background 0.2s;
}
.voucher-form button:hover {
    background: #1d4ed8;
}
</style>
@endsection
