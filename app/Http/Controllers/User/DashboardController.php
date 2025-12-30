<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Itinerary;

class DashboardController extends Controller
{
    
    public function dashboard()
    {
        // Lấy danh mục + 3 game theo danh mục
        $categories = Category::all();
        $categories->each(function ($category) {
            $category->limited_games = $category->games()
                ->where('status', 1)
                ->orderBy('id', 'asc')
                ->take(1)
                ->get();
        });

        // Lấy itinerary
        $itineraries = Itinerary::where('status', 1)
            ->where('is_delete', 0)
            ->with(['locations' => function ($query) {
                $query->where('is_delete', 0)->take(1);
            }])
            ->limit(4)
            ->orderBy('id','desc')
            ->get();

        // Truyền cả 2 xuống view
        return view('user.dashboard', compact('categories', 'itineraries'));
    }

    
}
