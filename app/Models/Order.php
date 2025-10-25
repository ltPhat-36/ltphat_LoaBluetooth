<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'name',
        'address',
        'phone',
        'total_price',
        'status',
        'shipping_status',
        'email', 
        'momo_request_id',
        'momo_order_id',
        'payment_method',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function items() {
        return $this->hasMany(OrderItem::class);
    }

    // Boot method để bắt sự kiện khi update status
    protected static function booted()
{
    static::updated(function ($order) {
        // 1. Nếu status vừa thay đổi và là cancelled => trả hàng
        if ($order->isDirty('status') && $order->status === 'cancelled') {
            foreach ($order->items as $item) {
                $product = $item->product;
                if ($product) {
                    $product->quantity += $item->quantity;
                    $product->save();
                }
            }
        }

        // 2. Nếu status vừa thay đổi và là completed => cộng points & update level
        if ($order->isDirty('status') && $order->status === 'completed') {
            $user = $order->user;
            if ($user) {
                // Chỉ cộng điểm khi chưa cộng trước đó
                $pointsToAdd = (int) round($order->total_price / 1000);

                // Cập nhật điểm & level
                $user->addPoints($pointsToAdd);
            }
        }
    });
}
}
