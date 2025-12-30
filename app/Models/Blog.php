<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blogs';

    protected $primaryKey = 'id';

    // Cho phép fill dữ liệu
    protected $fillable = [
        'posted_date',
        'user_id',
        'blog_name',
        'image',
        'content',
        'is_delete',
    ];

    // Laravel tự quản lý created_at / updated_at
    public $timestamps = true;

    /**
     * Blog thuộc về 1 User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
