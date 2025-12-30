<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use App\Models\Location;

class ItineraryController extends Controller
{
    public function itinerary()
{
    $data = [
        'itineraries' => Itinerary::with([
                'locations' => function ($q) {
                    $q->where('status', 1)
                      ->where('is_delete', 0);
                }
            ])
            ->where('status', 1)
            ->where('is_delete', 0)
            ->orderByDesc('id')
            ->get(),

        'categories' => \App\Models\CategoryLocation::where('is_delete', 0)
            ->orderByDesc('id')
            ->get(),
    ];

    return view('user.itinerary', $data);
}



public function itineraryDetail($id)
{
    $itinerary = Itinerary::with([
        'games',   
        'locations'    
    ])->findOrFail($id);

    return view('user.detailItinerary', compact('itinerary'));
}


}
