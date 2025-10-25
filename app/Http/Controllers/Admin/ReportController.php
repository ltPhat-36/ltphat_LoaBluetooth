<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // Trang báo cáo tổng hợp
    public function index()
    {
        $paidStatuses = ['completed'];

        // Doanh thu theo danh mục
        $categoryRevenue = DB::table('order_items')
            ->join('products','order_items.product_id','=','products.id')
            ->join('orders','order_items.order_id','=','orders.id')
            ->whereIn('orders.status', $paidStatuses)
            ->select(
                'products.category_id',
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue'),
                DB::raw('SUM(order_items.quantity) as total_qty')
            )
            ->groupBy('products.category_id')
            ->orderByDesc('total_revenue')
            ->get();

        $totalOrders = Order::count();
        $totalCustomers = DB::table('users')->where('role', 'customer')->count();

        // Doanh thu theo ngày, tháng, năm
        $revenueByDate = Order::whereIn('status', $paidStatuses)
            ->selectRaw('DATE(created_at) as date, SUM(total_price) as total_revenue, COUNT(*) as order_count')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        $revenueByMonth = Order::whereIn('status', $paidStatuses)
            ->selectRaw('DATE_FORMAT(created_at,"%Y-%m") as month, SUM(total_price) as total_revenue, COUNT(*) as order_count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $revenueByYear = Order::whereIn('status', $paidStatuses)
            ->selectRaw('YEAR(created_at) as year, SUM(total_price) as total_revenue, COUNT(*) as order_count')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        // Top sản phẩm bán chạy
        $topProducts = DB::table('order_items')
            ->join('products','order_items.product_id','=','products.id')
            ->join('orders','order_items.order_id','=','orders.id')
            ->whereIn('orders.status', $paidStatuses)
            ->select(
                'products.id',
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_qty'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue')
            )
            ->groupBy('products.id','products.name')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        // Dữ liệu chart
        $catLabels = $categoryRevenue->pluck('category_id');
        $catRevenue = $categoryRevenue->pluck('total_revenue');

        $revDateLabels = $revenueByDate->pluck('date');
        $revDateData = $revenueByDate->pluck('total_revenue');

        $revMonthLabels = $revenueByMonth->pluck('month');
        $revMonthData = $revenueByMonth->pluck('total_revenue');

        $revYearLabels = $revenueByYear->pluck('year');
        $revYearData = $revenueByYear->pluck('total_revenue');

        // Lấy tất cả trạng thái thực tế trong DB
$paymentStatusLabels = Order::distinct()->pluck('status')->toArray();

// Tính doanh thu từng trạng thái
$paymentStatusRevenue = [];
foreach($paymentStatusLabels as $status) {
    $paymentStatusRevenue[] = (float) Order::where('status', $status)->sum('total_price');
}




        return view('admin.reports.index', compact(
            'categoryRevenue','totalOrders','totalCustomers',
            'revenueByDate','revenueByMonth','revenueByYear',
            'topProducts',
            'catLabels','catRevenue',
            'revDateLabels','revDateData',
            'revMonthLabels','revMonthData',
            'revYearLabels','revYearData',
            'paymentStatusLabels','paymentStatusRevenue'
        ));
    }

    // Trang biểu đồ (chart view)
    public function chartView()
    {
        $paidStatuses = ['completed']; // trạng thái đơn hàng được tính doanh thu

        // --- Doanh thu theo danh mục ---
        $categories = DB::table('order_items')
            ->join('products','order_items.product_id','=','products.id')
            ->join('orders','order_items.order_id','=','orders.id')
            ->whereIn('orders.status', $paidStatuses)
            ->select('products.category_id', DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue'))
            ->groupBy('products.category_id')
            ->get();

        $catLabels = $categories->pluck('category_id');
        $catRevenue = $categories->pluck('total_revenue');

        // --- Doanh thu theo ngày (30 ngày gần nhất) ---
        $revByDate = Order::whereIn('status', $paidStatuses)
            ->where('created_at','>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, SUM(total_price) as total_revenue')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        $revDateLabels = $revByDate->pluck('date');
        $revDateData = $revByDate->pluck('total_revenue');

        // --- Doanh thu theo tháng (12 tháng) ---
        $revByMonth = Order::whereIn('status', $paidStatuses)
            ->selectRaw('DATE_FORMAT(created_at,"%Y-%m") as month, SUM(total_price) as total_revenue')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $revMonthLabels = $revByMonth->pluck('month');
        $revMonthData = $revByMonth->pluck('total_revenue');

        // --- Doanh thu theo năm ---
        $revByYear = Order::whereIn('status', $paidStatuses)
            ->selectRaw('YEAR(created_at) as year, SUM(total_price) as total_revenue')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        $revYearLabels = $revByYear->pluck('year');
        $revYearData = $revByYear->pluck('total_revenue');

        // --- Doanh thu theo trạng thái thanh toán ---
        // Lấy tất cả trạng thái thực tế trong DB
$paymentStatusLabels = Order::distinct()->pluck('status')->toArray();

// Tính doanh thu từng trạng thái
$paymentStatusRevenue = [];
foreach($paymentStatusLabels as $status) {
    $paymentStatusRevenue[] = (float) Order::where('status', $status)->sum('total_price');
}



        // --- Trả dữ liệu sang view charts ---
        return view('admin.reports.charts', compact(
            'catLabels','catRevenue',
            'revDateLabels','revDateData',
            'revMonthLabels','revMonthData',
            'revYearLabels','revYearData',
            'paymentStatusLabels','paymentStatusRevenue'
        ));
    }
}
