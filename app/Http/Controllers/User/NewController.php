<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;

class NewController extends Controller
{
    // Hiển thị danh sách tin tức
    public function index()
    {
        $news = News::orderByDesc('created_at')->paginate(6); // phân trang 6 tin
        return view('user.news.index', compact('news'));
    }

    // Hiển thị chi tiết tin tức
    public function show($slug)
    {
        $newsItem = News::where('slug', $slug)->firstOrFail();
        return view('user.news.show', compact('newsItem'));
    }
}
