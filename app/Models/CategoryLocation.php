<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryLocation extends Model
{
    use HasFactory;
    protected $table = 'location_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'status',
        'is_delete',
    ];

public function locations()
    {
        return $this->belongsToMany(
            Location::class,
            'category_location_location',
            'location_category_id',       
            'location_id'              
        );
    }

}
