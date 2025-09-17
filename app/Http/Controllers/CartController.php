<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Hiển thị giỏ hàng
    public function index()
    {
        if (Auth::check()) {
            // Lấy giỏ hàng từ DB
            $cartItems = CartItem::with('product')
                ->where('user_id', Auth::id())
                ->get();
        } else {
            // Lấy giỏ hàng từ session
            $cartSession = session()->get('cart', []);
            $cartItems = collect($cartSession)->map(function ($item, $id) {
                $product = Product::find($id);
                if (!$product) return null;
                return (object)[
                    'quantity' => $item['quantity'],
                    'product'  => $product
                ];
            })->filter(); // lọc null nếu product bị xóa
        }

        return view('cart.index', compact('cartItems'));
    }

    // Thêm sản phẩm vào giỏ hàng
    public function add(Request $request, Product $product)
    {
        if (Auth::check()) {
            $cartItem = CartItem::firstOrCreate(
                ['user_id' => Auth::id(), 'product_id' => $product->id],
                ['quantity' => 0]
            );
            $cartItem->quantity += 1;
            $cartItem->save();
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$product->id])) {
                $cart[$product->id]['quantity']++;
            } else {
                $cart[$product->id] = [
                    'id'       => $product->id,
                    'quantity' => 1,
                ];
            }
            session(['cart' => $cart]);
        }

        return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
    }

    // Tăng số lượng
    public function increase($id)
    {
        if (Auth::check()) {
            $cartItem = CartItem::where('user_id', Auth::id())->where('product_id', $id)->first();
            if ($cartItem) {
                $cartItem->quantity += 1;
                $cartItem->save();
            }
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$id])) {
                $cart[$id]['quantity']++;
                session(['cart' => $cart]);
            }
        }

        return response()->json(['success' => true]);
    }

    // Giảm số lượng
    public function decrease($id)
    {
        if (Auth::check()) {
            $cartItem = CartItem::where('user_id', Auth::id())->where('product_id', $id)->first();
            if ($cartItem) {
                if ($cartItem->quantity > 1) {
                    $cartItem->quantity -= 1;
                    $cartItem->save();
                } else {
                    $cartItem->delete();
                }
            }
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$id])) {
                if ($cart[$id]['quantity'] > 1) {
                    $cart[$id]['quantity']--;
                } else {
                    unset($cart[$id]);
                }
                session(['cart' => $cart]);
            }
        }

        return response()->json(['success' => true]);
    }

    // Cập nhật số lượng (direct input)
    public function update(Request $request, $id)
    {
        $qty = max(1, (int)$request->quantity);

        if (Auth::check()) {
            $cartItem = CartItem::where('user_id', Auth::id())->where('product_id', $id)->first();
            if ($cartItem) {
                $cartItem->quantity = $qty;
                $cartItem->save();
            }
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$id])) {
                $cart[$id]['quantity'] = $qty;
                session(['cart' => $cart]);
            }
        }

        return response()->json(['success' => true]);
    }

    // Xóa sản phẩm
    public function remove($id)
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->where('product_id', $id)->delete();
        } else {
            $cart = session()->get('cart', []);
            unset($cart[$id]);
            session(['cart' => $cart]);
        }

        return response()->json(['success' => true]);
    }

    // Xóa toàn bộ giỏ hàng
    public function clear()
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->delete();
        } else {
            session()->forget('cart');
        }

        return response()->json(['success' => true]);
    }
}
