<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'photo',
        'is_delete',
    ];

    /**
     * Quan hệ: Một sản phẩm có nhiều chi tiết đơn hàng
     */
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * Quan hệ: Một sản phẩm có thể xuất hiện trong nhiều giỏ hàng
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
