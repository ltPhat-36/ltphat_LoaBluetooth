<?php

namespace App\Http\Controllers;

use App\Models\Product;

class WelcomeController extends Controller
{
   public function index() {
        $products = Product::latest()->take(12)->get();
        $featuredProducts = Product::where('is_featured', 1)->take(3)->get();
        $newProducts = Product::where('is_new', 1)->take(3)->get(); // sản phẩm mới
        return view('frontend.home', compact('products', 'featuredProducts', 'newProducts'));
    }
}
