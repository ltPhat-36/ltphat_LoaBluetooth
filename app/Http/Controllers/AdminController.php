<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $userCount    = User::count();
        $orderCount   = Order::count();
        $productCount = Product::count();

        // Sử dụng total_price thay vì total_amount
        $revenue = Order::where('status', 'completed')->sum('total_price');

        // Lấy dữ liệu doanh thu 7 ngày gần nhất
        $labels = [];
        $data   = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('d/m');
            $data[]   = Order::whereDate('created_at', $date)
                             ->where('status', 'completed')
                             ->sum('total_price');
        }

        return view('admin.dashboard', compact(
            'userCount',
            'orderCount',
            'productCount',
            'revenue',
            'labels',
            'data'
        ));
    }
}
