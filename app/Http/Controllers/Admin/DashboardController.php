<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Game;
use App\Models\Product;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers     = User::count();
        $totalGames     = Game::count();
        $totalProducts  = Product::count();
        $totalCategories = Category::count();

        return view('admin.pages.dashboard', compact(
            'totalUsers',
            'totalGames',
            'totalProducts',
            'totalCategories'
        ));
    }
}
