@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng')

@section('styles')
<style>
.order-container { margin-top: 80px; }
.summary-box { display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 25px; }
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
.table-wrapper { overflow-x: auto; }
.table-striped tbody tr:hover { background-color: #e2f0ff; }
.btn-status { margin-left: 5px; }
.status-pending { background-color: #ffc107; color: #fff; }
.status-processing { background-color: #0d6efd; color: #fff; }
.status-completed { background-color: #198754; color: #fff; }
.status-cancelled { background-color: #dc3545; color: #fff; }
@media (max-width: 768px) { .summary-box { flex-direction: column; } }
</style>
@endsection

@section('content')
<div class="container-fluid order-container">
    <h1 class="mb-4">Đơn hàng #{{ $order->id }}</h1>

    <div class="summary-box mb-4">
        <div>Người mua: {{ $order->user->name ?? 'Khách' }}</div>
        <div>Email: {{ $order->email ?? 'Không có' }}</div>
        <div class="status-{{ $order->status }}">Trạng thái: {{ ucfirst($order->status) }}</div>
    </div>

    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="mb-4">
        @csrf @method('PATCH')
        <label for="status" class="form-label me-2">Cập nhật trạng thái:</label>
        <select name="status" id="status" class="form-select w-auto d-inline-block">
            <option value="pending" @selected($order->status=='pending')>Chờ xử lý</option>
            <option value="processing" @selected($order->status=='processing')>Đang xử lý</option>
            <option value="completed" @selected($order->status=='completed')>Đã hoàn thành</option>
            <option value="cancelled" @selected($order->status=='cancelled')>Hủy</option>
        </select>
        <button type="submit" class="btn btn-sm btn-success btn-status">Cập nhật</button>
    </form>

    <h4>Sản phẩm trong đơn</h4>
    <div class="table-wrapper">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? $item->product_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price,0,',','.') }}₫</td>
                    <td>{{ number_format($item->price * $item->quantity,0,',','.') }}₫</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @php $total = $order->items->sum(fn($item)=> $item->price * $item->quantity); @endphp
    <h5 class="text-end fw-bold mt-3">Tổng: {{ number_format($total,0,',','.') }}₫</h5>
</div>
@endsection
