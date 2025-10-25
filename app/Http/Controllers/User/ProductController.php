<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function show($id)
    {
        // Load sản phẩm + review + user
        $product = Product::with(['reviews.user', 'category'])->findOrFail($id);

        // Lấy sản phẩm liên quan cùng category (trừ chính nó)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4) // giới hạn 4 sp
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
    public function welcome()
{
    $products = Product::latest()->paginate(12);
    $featuredProducts = Product::where('is_featured', 1)->take(8)->get();

    return view('welcome', compact('products', 'featuredProducts'));
}
public function index()
{
    $query = Product::query();

    // Tìm kiếm theo tên sản phẩm
    if ($search = request('q')) {
        $query->where('name', 'like', "%{$search}%");
    }

    // Lọc theo danh mục
    if ($category = request('category')) {
        $query->where('category_id', $category);
    }

    // Sắp xếp
    switch(request('sort')) {
        case 'price_asc': $query->orderBy('price', 'asc'); break;
        case 'price_desc': $query->orderBy('price', 'desc'); break;
        case 'newest': $query->orderBy('created_at', 'desc'); break;
        case 'oldest': $query->orderBy('created_at', 'asc'); break;
        default: $query->latest(); break;
    }

    // Phân trang 12 sản phẩm/trang
    $products = $query->paginate(6)->appends(request()->all());

    // Lấy danh mục để dropdown filter
    $categories = \App\Models\Category::all();

    // Trả về view index với products đã lọc
    return view('products.index', compact('products', 'categories'));
}

}
