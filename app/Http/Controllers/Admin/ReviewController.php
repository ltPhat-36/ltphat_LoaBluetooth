<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    // Danh sách đánh giá
    public function index()
{
    // Lấy danh sách đánh giá, mới nhất trước
    $reviews = Review::with(['user', 'product'])
                     ->orderByDesc('created_at')
                     ->paginate(15);

    // Đánh dấu tất cả đánh giá chưa đọc là đã đọc
    Review::where('is_read', false)->update(['is_read' => true]);

    return view('admin.reviews.index', compact('reviews'));
}

    // Xóa đánh giá
    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')
                         ->with('success', 'Đánh giá đã được xóa.');
    }
    // Trang trả lời đánh giá
public function edit(Review $review)
{
    return view('admin.reviews.reply', compact('review'));
}

// Xử lý lưu câu trả lời admin
public function update(Request $request, Review $review)
{
    $request->validate([
        'admin_reply' => 'required|string|max:1000',
    ]);

    $review->update([
        'admin_reply' => $request->admin_reply,
    ]);

    return redirect()->route('admin.reviews.index')
                     ->with('success', 'Bạn đã trả lời đánh giá thành công.');
}

}
