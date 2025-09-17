<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userCount    = User::count();
        $orderCount   = Order::count();
        $productCount = Product::count();

        // Tổng doanh thu từ đơn hàng hoàn thành
        $revenue = Order::where('status', 'completed')->sum('total_price');

        // Doanh thu theo tháng (6 tháng gần nhất)
        $monthlyRevenue = Order::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_price) as total')
            )
            ->where('status', 'completed')
            ->whereYear('created_at', date('Y'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'month')
            ->toArray();

        // Đảm bảo có đủ 12 tháng
        $labels = [];
        $data   = [];
        for ($m = 1; $m <= 12; $m++) {
            $labels[] = "Tháng $m";
            $data[]   = $monthlyRevenue[$m] ?? 0;
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
