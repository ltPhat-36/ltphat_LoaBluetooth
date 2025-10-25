<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'start_at',
        'expires_at',
        'customer_group',
    ];

    // Cast sang datetime để dùng các hàm Carbon
    protected $dates = [
        'start_at',
        'expires_at',
    ];

    // Kiểm tra voucher còn hiệu lực
    public function isValid(): bool
    {
        $now = Carbon::now();

        if ($this->start_at && $now->lt($this->start_at)) {
            return false; // chưa bắt đầu
        }

        if ($this->expires_at && $now->gt($this->expires_at)) {
            return false; // đã hết hạn
        }

        return true;
    }
}
