<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'is_delete',
        'pay',
        'purchase_date',
        'delivery_address',
        'delivery_phone',
        'receiver_name',
        'receiver_email',
        'payment_method',
    ];

    protected $casts = [
        'purchase_date' => 'datetime',
    ];

    /**
     * Quan hệ: Đơn hàng thuộc về 1 người dùng
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quan hệ: Một đơn hàng có nhiều chi tiết đơn hàng
     */
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
