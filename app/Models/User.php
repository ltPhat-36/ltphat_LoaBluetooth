<?php

namespace App\Models;
use App\Models\CartItem;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'email_verified_at',
        'address',
        'phone',
        'points',
        'level', // thêm level
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Wishlist relation
    public function wishlist()
    {
        return $this->belongsToMany(Product::class, 'wishlists')->withTimestamps();
    }
    
    // Thêm điểm cho user
    public function addPoints(int $points)
    {
        $this->points += $points;
        $this->updateLevel();
        $this->save();
    }

    // Cập nhật level theo points
    public function updateLevel()
    {
        if ($this->points >= 1000) {
            $this->level = 'Platinum';
        } elseif ($this->points >= 500) {
            $this->level = 'Gold';
        } elseif ($this->points >= 200) {
            $this->level = 'Silver';
        } else {
            $this->level = 'Bronze';
        }
    }

    // Thuộc tính customer_group dựa vào level
    public function getCustomerGroupAttribute(): string
    {
        // Chỉ lấy Bronze/Silver/Gold, bỏ Platinum nếu bạn muốn
        return match($this->level) {
            'Platinum' => 'Gold', // hoặc 'Platinum' nếu muốn tạo voucher riêng
            'Gold' => 'Gold',
            'Silver' => 'Silver',
            default => 'Bronze',
        };
    }
    public function cartItems()
{
    return $this->hasMany(CartItem::class);
}
}
