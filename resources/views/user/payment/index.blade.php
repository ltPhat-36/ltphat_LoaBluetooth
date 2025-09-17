@extends('layouts.user')

@section('title', 'Thanh toán')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">💳 Thanh toán</h2>

    @if($cartItems->isEmpty())
        <p>Giỏ hàng của bạn đang trống.</p>
    @else
        <form action="{{ route('payment.process') }}" method="POST" class="checkout-form">
            @csrf
            <div class="mb-3">
                <label class="form-label">Tên người nhận</label>
                <input type="text" name="name" class="form-control"
                       value="{{ old('name', Auth::user()->name ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                       value="{{ old('email', Auth::user()->email ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Địa chỉ</label>
                <input type="text" name="address" class="form-control"
                       value="{{ old('address', Auth::user()->address ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Số điện thoại</label>
                <input type="text" name="phone" class="form-control"
                       value="{{ old('phone', Auth::user()->phone ?? '') }}" required>
            </div>

            <h4 class="mt-4">🛒 Giỏ hàng</h4>
            <table class="table table-bordered align-middle text-center shadow-sm">
                <thead class="table-primary">
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Giá</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($cartItems as $item)
                        @php
                            $price = isset($item->product) ? $item->product->price : $item->price;
                            $name  = isset($item->product) ? $item->product->name  : $item->name;
                            $quantity = $item->quantity ?? $item['quantity'];
                            $subtotal = $price * $quantity;
                            $total += $subtotal;
                        @endphp
                        <tr>
                            <td>{{ $name }}</td>
                            <td>{{ $quantity }}</td>
                            <td>{{ number_format($price,0,',','.') }} đ</td>
                            <td>{{ number_format($subtotal,0,',','.') }} đ</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <h5 class="text-end mt-3">
                Tổng cộng: <span class="text-danger fw-bold">{{ number_format($total,0,',','.') }} đ</span>
            </h5>

            <div class="mb-3">
                <label class="form-label">Chọn phương thức thanh toán</label>
                <select name="payment_method" class="form-select" required>
                    <option value="cod">COD</option>
                    <option value="momo">MoMo</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">Đặt hàng</button>
        </form>
    @endif
</div>

{{-- CSS cho checkout --}}
<style>
.checkout-form {
    background: #ffffff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
.checkout-form .form-control,
.checkout-form .form-select {
    border-radius: 8px;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.checkout-form .form-control:focus,
.checkout-form .form-select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 0.2rem rgba(37,99,235,0.25);
}
.checkout-form label {
    font-weight: 500;
    color: #1e3a8a;
}
.checkout-form table {
    border-radius: 8px;
    overflow: hidden;
}
.checkout-form thead {
    background-color: #e0f2fe;
    color: #1e3a8a;
    font-weight: 600;
}
.checkout-form button {
    font-size: 16px;
    padding: 12px;
    border-radius: 8px;
    transition: background 0.2s;
}
.checkout-form button:hover {
    background: #1d4ed8;
}
</style>
@endsection
