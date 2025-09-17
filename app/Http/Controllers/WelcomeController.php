<?php

namespace App\Http\Controllers;

use App\Models\Product;

class WelcomeController extends Controller
{
    /**
     * Hiển thị trang welcome với danh sách sản phẩm
     */
    public function index()
    {
        // Lấy sản phẩm mới nhất, phân trang 12 sản phẩm/trang
        $products = Product::latest()->paginate(12);

        // Trả về view welcome.blade.php
        return view('welcome', compact('products'));
    }
}
