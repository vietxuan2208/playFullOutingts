<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Game;
use App\Models\Material;

class RecycleGameController extends Controller
{
    public function trash(){
        $games = Game::where('is_delete', 1)
            ->orderBy('id','desc')
            ->get();
            
        $categories = Category::all();
        $materials = Material::all(); 
        return view('admin.trashGame', compact('games','categories', 'materials'));
    }

    public function restore($id){
        $game = Game::findOrFail($id);
        $game->is_delete = 0;
        $game->status = 1;
        $game->save();

        return redirect()->back()->with('success', 'Game restored successfully!');
    }

    public function delete($id){
        $game = Game::findOrFail($id);
        $game->delete();

        return redirect()->back()->with('success', 'Game deleted successfully!');
    }
}

