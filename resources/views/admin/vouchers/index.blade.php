@extends('layouts.admin')

@section('title', 'Danh sách Voucher')

@section('content')
<h2>Danh sách Voucher</h2>
<a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary mb-2">Thêm Voucher</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Code</th>
            <th>Loại</th>
            <th>Giá trị</th>
            <th>Nhóm khách hàng</th>
            <th>Hạn sử dụng</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($vouchers as $v)
        <tr>
            <td>{{ $v->code }}</td>
            <td>{{ $v->discount_type }}</td>
            <td>{{ $v->discount_value }}</td>
            <td>{{ $v->customer_group ?? 'Tất cả' }}</td>
            <td>{{ $v->expires_at ? \Carbon\Carbon::parse($v->expires_at)->format('d/m/Y') : '-' }}</td>
            <td>{{ $v->active ? 'Hoạt động' : 'Không' }}</td>
            <td>
                <a href="{{ route('admin.vouchers.edit', $v) }}" class="btn btn-sm btn-warning">Sửa</a>
                <form action="{{ route('admin.vouchers.destroy', $v) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Xóa voucher?')">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
