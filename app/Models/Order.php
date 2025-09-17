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
}