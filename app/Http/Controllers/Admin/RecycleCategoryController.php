<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Game;




class RecycleCategoryController extends Controller
{
    public function trash(){
        $categories = Category::with('games')
        ->where('is_delete',1)
        ->orderBy('id','desc')
        ->get();
        $games = Game::get();
        return view('admin.trashCategory', compact('categories','games'));
    }
    public function restore($id)
    {
        $category = Category::findOrFail($id);

        $category->is_delete = 0;
        $category->status = 1;
        $category->save();

        return redirect()->route('admin.trashCategory')
            ->with('success', 'Category restored successfully!');
    }

    public function delete($id)
    {
        $category = Category::with('games')->findOrFail($id);

        if ($category->games->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete category because it has associated games.');
        }

        $category->forceDelete();
        return redirect()->back()->with('success', 'Category deleted successfully.');
    }
}
