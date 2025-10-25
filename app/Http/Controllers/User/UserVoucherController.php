<?php

namespace App\Http\Controllers\User;
use App\Models\CartItem;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;

use Illuminate\Support\Facades\Auth; // <--- import Auth đúng
use App\Models\Product;              // <--- import Product đúng

class UserVoucherController extends Controller
{
    public function apply(Request $request)
{
    try {
        $request->validate(['code' => 'required|string']);
        $voucher = Voucher::where('code', $request->code)->first();

        if (!$voucher) {
            return response()->json(['success'=>false,'error'=>'Mã không tồn tại']);
        }

        // Kiểm tra voucher còn hiệu lực
        if (!method_exists($voucher,'isValid') || !$voucher->isValid()) {
            return response()->json(['success'=>false,'error'=>'Voucher hết hạn']);
        }

        // Kiểm tra nhóm khách
        $user = $request->user();
        $customerGroup = $user ? $user->customer_group : null;
        if ($voucher->customer_group && $voucher->customer_group !== $customerGroup) {
            return response()->json(['success'=>false,'error'=>'Voucher không áp dụng cho nhóm khách của bạn']);
        }

        session(['applied_voucher'=>$voucher->id]);

        $cartItems = \App\Models\CartItem::with('product')
            ->where('user_id', $user->id ?? 0)->get();

        $subtotal = $cartItems->sum(fn($item)=>($item->product->price??0)*$item->quantity);

        $discount = $voucher->discount_type==='percent'
            ? round($subtotal*$voucher->discount_value/100)
            : min($voucher->discount_value,$subtotal);

        return response()->json(['success'=>true,'discount'=>$discount]);
    } catch (\Throwable $e) {
        return response()->json(['success'=>false,'error'=>$e->getMessage()]);
    }
}


public function remove()
{
    session()->forget('applied_voucher');
    return response()->json(['success' => true]);
}
public function index()
{
    // Lấy giỏ hàng
    if (Auth::check()) {
        $cartItems = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();
    } else {
        $cartSession = session()->get('cart', []);
        $cartItems = collect($cartSession)->map(function ($item, $id) {
            $product = Product::find($id);
            if (!$product) return null;

            // Tạo object CartItem giả để view đồng nhất
            $cartItem = new CartItem();
            $cartItem->quantity = $item['quantity'];
            $cartItem->product = $product;
            return $cartItem;
        })->filter();
    }

    // Lấy voucher còn hiệu lực
    $availableVouchers = Voucher::where('expires_at', '>=', now())
                                ->where('start_at', '<=', now())
                                ->get();

    return view('cart.index', compact('cartItems', 'availableVouchers'));
}

}
