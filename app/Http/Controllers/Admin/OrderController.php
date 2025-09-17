<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    // Danh sách đơn hàng
    public function index()
    {
        $orders = Order::with('user')->orderByDesc('created_at')->get();
        return view('admin.orders.index', compact('orders'));
    }
    public function updateShippingStatus(Request $request, Order $order)
    {
        $request->validate([
            'shipping_status' => 'required|in:not_shipped,packaged,shipping,completed,cancelled'
        ]);

        $order->shipping_status = $request->shipping_status;
        $order->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái giao hàng thành công!');
    }
    // Chi tiết đơn hàng
    public function show(Order $order)
    {
        $order->load('user', 'items.product'); // items = sản phẩm trong đơn
        return view('admin.orders.show', compact('order'));
    }

    // Cập nhật trạng thái đơn hàng
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,completed,cancelled',
        ]);

        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }
}
