<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Category;
use Illuminate\Http\Request;

class GameUserController extends Controller
{
    public function game()
    {
        $categories = Category::where('is_delete', 0)->get();

        $data = [];

        foreach ($categories as $cat) {
            $data[] = [
                'category' => $cat,
                'games'    => Game::whereHas('categories', function ($q) use ($cat) {
                                    $q->where('id', $cat->id);
                                })
                                ->where('is_delete', 0)
                                ->orderBy('id', 'asc')
                                ->take(3)
                                ->get()
            ];
        }

        return view('user.game', compact('data'));
    }


    public function category($id)
{
    // Lấy danh sách tất cả category (để hiển thị filter list)
    $categoriesList = Category::where('is_delete', 0)->get();

    // Lấy category hiện tại
    $category = Category::where('is_delete', 0)->findOrFail($id);

    // Lấy danh sách games của category hiện tại
    $games = $category->games()->where('is_delete', 0)->get();

    // Trả về view với đủ biến
    return view('user.categoryPage', compact('categoriesList', 'category', 'games'));
}



    public function detailGame($id)
    {
        $game = Game::with(['materials', 'categories'])
                    ->where('id', $id)
                    ->where('is_delete', 0)
                    ->firstOrFail();

        return view('user.detailGame', compact('game'));
    }
}
