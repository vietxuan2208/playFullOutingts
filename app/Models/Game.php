<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $table = 'games';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'slug',
        'image',
        'duration',
        'instructions',
        'video_url',
        'download_file',
        'status',
        'players',
        'difficulty',
        'game_setup',
        'game_rules',
        'is_delete',

    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_game', 'game_id', 'category_id');
    }
    public function materials()
    {
        return $this->belongsToMany(Material::class, 'game_material', 'game_id', 'material_id');
    }
    
    public function itineraries()
    {
        return $this->belongsToMany(Itinerary::class, 'itinerary_game');
    }
}
