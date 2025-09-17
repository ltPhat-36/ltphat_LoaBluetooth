<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // View composer cho layout thanh toán user
        View::composer('user.payment.*', function ($view) {
            if (Auth::check()) {
                $cartItems = CartItem::with('product')->where('user_id', Auth::id())->get();
            } else {
                $cartArray = session('cart', []);
                $cartItems = collect($cartArray)->map(function($item){
                    return (object)[
                        'id' => $item['id'] ?? null,
                        'name' => $item['name'] ?? '',
                        'price' => $item['price'] ?? 0,
                        'quantity' => $item['quantity'] ?? 0,
                    ];
                });
            }
            $view->with('cartItems', $cartItems);
        });

        // View composer cho layout admin - số lượng đánh giá chưa đọc
        View::composer('layouts.admin', function ($view) {
            $unreadReviewsCount = Review::where('is_read', false)->count();
            $view->with('unreadReviewsCount', $unreadReviewsCount);
        });
    }
}
