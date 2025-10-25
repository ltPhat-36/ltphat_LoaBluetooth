<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;
class PaymentController extends Controller
{
    public function index()
{
    $user = Auth::user();

    // Lấy giỏ hàng
    $cartItems = $user->cartItems()->with('product.category')->get();

    // Tính subtotal
    $subtotal = $cartItems->sum(function($item){
        return ($item->product->price ?? 0) * $item->quantity;
    });

    $shipping_fee = 25000;
    $vat = round($subtotal * 0.10);

    // Lấy voucher nếu có
    $discount = 0;
    if (session()->has('applied_voucher')) {
        $voucherId = session('applied_voucher');
        $voucher = \App\Models\Voucher::find($voucherId);
        if ($voucher) {
            if ($voucher->discount_type === 'percent') {
                $discount = round($subtotal * $voucher->discount_value / 100);
            } else {
                $discount = $voucher->discount_value;
            }
        }
    }

    $grand_total = $subtotal + $shipping_fee + $vat - $discount;

    return view('user.payment.index', [
        'cartItems'    => $cartItems,
        'subtotal'     => $subtotal,
        'shipping_fee' => $shipping_fee,
        'vat'          => $vat,
        'discount'     => $discount,
        'grand_total'  => $grand_total,
        'voucher'      => $voucher ?? null,
    ]);
}
}