<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // Hiển thị trang đánh giá
    public function create($productId)
    {
        $product = Product::findOrFail($productId);
        return view('user.review.create', compact('product'));
    }

    // Xử lý submit đánh giá
    public function store(Request $request, $productId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $userId = auth()->id();

        // Kiểm tra user đã mua sản phẩm chưa
        $hasPurchased = \App\Models\OrderItem::whereHas('order', function($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->whereIn('status', ['đã thanh toán (MoMo)', 'đã đặt (COD)']); // chỉ tính các đơn hoàn tất
            })
            ->where('product_id', $productId)
            ->exists();

        if (!$hasPurchased) {
            return redirect()->route('frontend.products.show', $productId)
                             ->with('error', 'Bạn phải mua sản phẩm này trước khi đánh giá.');
        }

        // Tạo đánh giá
        \App\Models\Review::create([
            'product_id' => $productId,
            'user_id' => $userId,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('frontend.products.show', $productId)
                         ->with('success', 'Đánh giá của bạn đã được gửi!');
    }
}
