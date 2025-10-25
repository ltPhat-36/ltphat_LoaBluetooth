<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;   // 👈 thêm dòng này
use Illuminate\Http\Request;
use App\Models\Product;


class WishlistController extends Controller
{
    public function toggle($productId)
    {
        $user = auth()->user();
        $product = Product::findOrFail($productId);

        if ($user->wishlist->contains($productId)) {
            $user->wishlist()->detach($productId);
            return back()->with('success', 'Đã xóa sản phẩm khỏi Yêu thích.');
        } else {
            $user->wishlist()->attach($productId);
            return back()->with('success', 'Đã thêm sản phẩm vào Yêu thích.');
        }
    }

    public function index()
    {
        $wishlist = auth()->user()->wishlist()->get(); // ❌ đừng eager load 'product'
        return view('user.wishlist.index', compact('wishlist'));
    }
    

}
