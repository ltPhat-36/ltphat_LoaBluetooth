@extends('layouts.admin')

@section('title', 'Quản lý Đánh giá')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Quản lý Đánh giá</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Người dùng</th>
                <th>Sản phẩm</th>
                <th>Rating</th>
                <th>Bình luận</th>
                <th>Thời gian</th>
                <th>Hành động</th>
                <th>Trả lời</th>
            </tr>
        </thead>
        <tbody>
    @foreach($reviews as $review)
        <tr>
            <td>{{ $review->id }}</td>
            <td>{{ $review->user->name ?? 'Người dùng bị xóa' }}</td>
            <td>{{ $review->product->name ?? 'Sản phẩm bị xóa' }}</td>
            <td>{{ $review->rating }}/5</td>
            <td>{{ $review->comment }}</td>
            <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
            <td>
                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa đánh giá này?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">Xóa</button>
                </form>
            </td>
            <!-- Cột Trả lời -->
            <td>
                @if($review->admin_reply)
                    <span class="badge bg-success">Đã trả lời</span>
                @else
                    <a href="{{ route('admin.reviews.edit', $review) }}" class="btn btn-sm btn-primary">Trả lời</a>
                @endif
            </td>
        </tr>
    @endforeach
</tbody>

    </table>

    {{ $reviews->links() }}
</div>
@endsection
