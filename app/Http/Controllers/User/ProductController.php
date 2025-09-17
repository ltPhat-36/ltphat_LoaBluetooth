<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function show($id)
    {
        // Load reviews kèm user, giữ cả admin_reply
        $product = Product::with(['reviews.user'])->findOrFail($id);

        return view('products.show', compact('product'));
    }
}

