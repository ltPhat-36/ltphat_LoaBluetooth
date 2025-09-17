@extends('layouts.admin')

@section('title', 'Trả lời đánh giá')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Trả lời đánh giá của {{ $review->user->name ?? 'Người dùng' }}</h1>

    <div class="card p-4 mb-4">
        <p><strong>Sản phẩm:</strong> {{ $review->product->name ?? 'Sản phẩm bị xóa' }}</p>
        <p><strong>Đánh giá:</strong> {{ $review->rating }}/5</p>
        <p><strong>Bình luận:</strong> {{ $review->comment }}</p>
    </div>

    <form action="{{ route('admin.reviews.update', $review) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="admin_reply" class="form-label">Phản hồi từ Admin</label>
            <textarea name="admin_reply" id="admin_reply" rows="5" class="form-control" required>{{ $review->admin_reply }}</textarea>

        </div>

        <button type="submit" class="btn btn-success">Gửi phản hồi</button>
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
