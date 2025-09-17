<!-- views/admin/orders/index.blade.php --> 
@extends('layouts.admin')

@section('title', 'Đơn hàng')

@section('styles')
<style>
/* Container */
.orders-container {
    margin-top: 20px; /* tránh topbar che */
}

/* Summary card */
.summary-box {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 25px;
}
.summary-box div {
    background: #0d6efd;
    color: #fff;
    padding: 20px;
    border-radius: 12px;
    flex: 1;
    min-width: 180px;
    text-align: center;
    font-weight: bold;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

/* Table wrapper để scroll ngang */
.table-wrapper {
    overflow-x: auto;
}

/* Table cải tiến */
.table-striped tbody tr:hover {
    background-color: #e2f0ff;
}

.table th {
    background-color: #0d6efd;
    color: #fff;
    position: sticky;
    top: 0;
    z-index: 2;
}

/* Nút hành động */
.btn-action {
    margin-right: 5px;
}

/* Responsive nhỏ hơn 768px */
@media (max-width: 768px) {
    .summary-box {
        flex-direction: column;
    }
}
</style>
@endsection

@section('content')
<div class="container-fluid orders-container">
    <h1 class="mb-4">Danh sách đơn hàng</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Tổng quan -->
    <div class="summary-box mb-4">
        <div>Tổng đơn hàng: {{ $orders->count() }}</div>
    </div>

    <div class="table-wrapper">
        @if($orders->count() > 0)
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>SĐT</th>
                    <th>Địa chỉ</th>
                    <th>Tổng tiền</th>
                    <th>Thanh toán</th>
                    <th>Giao hàng</th>
                    <th>Ngày đặt</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $index => $order)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->user->name ?? $order->name ?? 'Khách' }}</td>
                    <td>{{ $order->phone }}</td>
                    <td>{{ $order->address }}</td>
                    @php
                        $orderTotal = $order->items->sum(fn($item)=> $item->price * $item->quantity);
                    @endphp
                    <td>{{ number_format($orderTotal, 0, ',', '.') }}₫</td>
                    <td>{{ ucfirst($order->status) }}</td>
                    <td>
                        <form action="{{ route('admin.orders.updateShippingStatus', $order->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="shipping_status" class="form-select form-select-sm" onchange="this.form.submit()">
                                @foreach([
                                    'not_shipped' => 'Chưa gửi',
                                    'packaged' => 'Đã đóng gói',
                                    'shipping' => 'Đang vận chuyển',
                                    'completed' => 'Thành công',
                                    'cancelled' => 'Hủy'
                                ] as $key => $label)
                                    <option value="{{ $key }}" {{ $order->shipping_status === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary btn-action">
                            <i class="bi bi-eye"></i> Xem
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <p>Không có đơn hàng nào.</p>
        @endif
    </div>
</div>
@endsection
