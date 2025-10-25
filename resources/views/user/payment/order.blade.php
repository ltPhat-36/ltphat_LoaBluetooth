@extends('layouts.user')

@section('title', '📜 Lịch sử đơn hàng')

@section('content')
<style>
:root {
    --radius: 14px;
    --shadow: 0 4px 20px rgba(0,0,0,0.08);
}
.order-card {
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border: none;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.order-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}
.order-header {
    background: #f8fafc;
    border-bottom: 1px solid #e5e7eb;
    border-top-left-radius: var(--radius);
    border-top-right-radius: var(--radius);
    padding: 0.8rem 1.2rem;
}
.status-badge {
    padding: 0.4rem 0.7rem;
    font-size: 0.85rem;
    font-weight: 600;
    border-radius: 8px;
}
.status-cod {
    background: #facc15;
    color: #78350f;
}
.status-momo {
    background: #4ade80;
    color: #064e3b;
}
.status-default {
    background: #e2e8f0;
    color: #334155;
}
.table th {
    background: #f1f5f9;
}
.total-price {
    font-size: 1.2rem;
    font-weight: bold;
}
.review-card {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 10px 15px;
    margin-top: 10px;
    background: #f9fafb;
}
</style>

<h2 class="mb-4">📜 Lịch sử đơn hàng</h2>

@forelse($orders as $order)
<div class="card order-card mb-4">
    <div class="order-header d-flex justify-content-between align-items-center">
        <div>
            <strong>🛒 Đơn hàng #{{ $order->id }}</strong><br>
            <small class="text-muted">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</small>
        </div>
        <div>
            <span class="status-badge 
                @if(str_contains($order->status,'COD')) status-cod
                @elseif(str_contains($order->status,'MoMo')) status-momo
                @else status-default @endif">
                {{ $order->status }}
            </span>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th class="text-center">Số lượng</th>
                        <th class="text-end">Giá</th>
                        <th class="text-end">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($order->items as $item)
    @php
        $subtotal = $item->price * $item->quantity;
        $total += $subtotal;
    @endphp
    <tr>
        <td>{{ $item->product->name ?? 'Sản phẩm' }}</td>
        <td class="text-center">{{ $item->quantity }}</td>
        <td class="text-end">{{ number_format($item->price,0,',','.') }} đ</td>
        <td class="text-end">{{ number_format($subtotal,0,',','.') }} đ</td>
    </tr>

    @if($item->product && $order->status === 'completed') {{-- thêm điều kiện completed --}}
    <tr>
        <td colspan="4">
            @php
                $existingReview = $item->product->reviews()->where('user_id', auth()->id())->first();
            @endphp

            @if($existingReview)
                <div class="review-card">
                    ⭐ Bạn đã đánh giá: <strong>{{ $existingReview->rating }}</strong> — "{{ $existingReview->comment }}"
                </div>
            @else
                <a href="{{ route('reviews.create', $item->product->id) }}" class="btn btn-sm btn-primary mt-2">Đánh giá sản phẩm</a>
            @endif
        </td>
    </tr>
    @endif
@endforeach

                </tbody>
            </table>
        </div>
        <h5 class="text-end total-price mt-3">Tổng: <span class="text-danger">{{ number_format($total,0,',','.') }} đ</span></h5>
        {{-- Nếu đơn chưa thanh toán hoặc thất bại thì cho phép thanh toán lại --}}
@if(in_array($order->status, ['chờ thanh toán','thanh toán MoMo thất bại']))
    <div class="text-end mt-2">
        <a href="{{ route('orders.momo.pay', $order) }}" class="btn btn-warning">
            🔄 Thanh toán lại MoMo
        </a>
    </div>
@endif

    </div>
</div>
@empty
<div class="alert alert-info shadow-sm p-4 text-center">
    😔 Bạn chưa có đơn hàng nào.<br>
    <a href="{{ route('home') }}" class="btn btn-primary mt-3">🛍️ Mua sắm ngay</a>
</div>
@endforelse
@endsection
