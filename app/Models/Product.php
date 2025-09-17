<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'quantity',
        'price',
        'features',
        'image',
    ];

    // Relationship: product belongs to category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function reviews()
{
    return $this->hasMany(Review::class)->with('user'); // thêm with('user')
}


// Tính trung bình sao
public function averageRating()
{
    return $this->reviews()->avg('rating');
}

}
