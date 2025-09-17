<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        $category->load('products'); // load luôn các sản phẩm liên quan
        return view('user.categories.show', compact('category'));
    }
}
