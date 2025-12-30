<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'selling_price',
        'purchase_date',
        'status',
    ];

    protected $casts = [
        'purchase_date' => 'datetime',
        'price' => 'decimal:2',
        'selling_price' => 'decimal:2',
    ];

    /**
     * Quan hệ: Chi tiết đơn hàng thuộc về 1 đơn hàng
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Quan hệ: Chi tiết đơn hàng thuộc về 1 sản phẩm
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
