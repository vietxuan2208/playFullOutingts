<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryLocation;
use App\Models\LocationCategory;
use App\Models\Location;
use Illuminate\Http\Request;

class CategoryLocationController extends Controller
{
    public function categoryLocation()
    {
        $data = [
            'categories' => CategoryLocation::where('is_delete', 0)->orderBy('id', 'desc')->get(),
            'allLocations' => Location::where('is_delete', 0)->orderBy('id', 'desc')->get(),
        ];

        return view('admin.categoryLocation')->with($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:location_categories,name',
            'slug' => 'required|unique:location_categories,slug',
        ]);

        CategoryLocation::create([
            'name'        => $request->name,
            'slug'        => $request->slug,
            'description' => $request->description,
            'status'      => 1,
            'is_delete'   => 0,
        ]);

        return back()->with('success', 'Location Category added successfully!');
    }

    public function update(Request $request, $id)
    {
        $category = CategoryLocation::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:location_categories,name,' . $id,
            'slug' => 'required|unique:location_categories,slug,' . $id,
        ]);

        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->description = $request->description;
        $category->status = $request->status;
        $category->save();

        if ($request->has('locations')) {
            $category->locations()->sync($request->locations);
        } else {
            $category->locations()->sync([]);
        }

        return redirect()->back()->with('success', 'Location Category updated.');
    }

    public function delete($id)
    {
        $category = CategoryLocation::findOrFail($id);

        $category->is_delete = 1;
        $category->status = 0;
        $category->save();

        return back()->with('success', 'Location Category deleted successfully!');
    }
}
