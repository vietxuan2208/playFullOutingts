<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Location;

class LocationController extends Controller
{
    public function detail($id)
    {
        $location = Location::with('categoryLocations')->findOrFail($id);

        return view('user.locationDetail', compact('location'));
    }
}
