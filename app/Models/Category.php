<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'status',
        'is_delete'
    ];

    public function games()
    {
        return $this->belongsToMany(Game::class, 'category_game', 'category_id', 'game_id');
    }
}
