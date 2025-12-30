<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
    ];

    /**
     * Quan hệ: Giỏ hàng thuộc về 1 người dùng
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quan hệ: Giỏ hàng chứa 1 sản phẩm
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
