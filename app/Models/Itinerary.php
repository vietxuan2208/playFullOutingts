<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    protected $table = 'itineraries';
    protected $primaryKey = 'id';


    protected $fillable = [
        'name',
        'description',
        'image',
        'photo',
        'days',
        'status',
        'is_delete',
    ];
public function locations()
{
    return $this->belongsToMany(
        Location::class,
        'location_itinerary',
        'itinerary_id',
        'location_id'
    );
}
    public function games()
    {
        return $this->belongsToMany(Game::class, 'itinerary_game');
    }

}
