<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function location()
    {
        $data = [
            'locations' => Location::where('is_delete', 0)->where('status',1)->orderBy('id', 'desc')->get(),
            'itineraries' => Itinerary::where('is_delete', 0)->orderBy('id', 'desc')->get(),
            'categories' => \App\Models\CategoryLocation::where('is_delete', 0)->orderBy('id', 'desc')->get(),
        ];
        return view('admin.locations')->with($data);
    }

public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:locations,name',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'address' => 'nullable|string|max:255',
            'itinerary_ids' => 'nullable|array',
            'itinerary_ids.*' => 'integer|exists:itineraries,id',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('storage/locations'), $imageName);
        }

        $location = Location::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imageName,
            'address' => $request -> address,
            'status' => $request->status ?? 1,
            'is_delete' => 0,
        ]);

        if ($request->has('itinerary_ids')) {
            $location->itineraries()->sync($request->itinerary_ids);
        }
        if ($request->has('category_ids')) {
            $location->categoryLocations()->sync($request->category_ids);
        }

        
        return back()->with('success', 'Location added successfully!');
    }

    // Cập nhật location
    public function update(Request $request, $id)
    {
        $location = Location::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:locations,name,'.$id,
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'itinerary_ids' => 'nullable|array',
            'itinerary_ids.*' => 'integer|exists:itineraries,id',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('storage/locations'), $imageName);
            $location->image = $imageName;
        }

        $location->update([
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'status' => $request->status ?? 1,
        ]);

        if ($request->exists('itinerary_ids')) {
            $location->itineraries()->sync($request->itinerary_ids ?? []);
        }

        if ($request->exists('category_ids')) {
            $location->categoryLocations()->sync($request->category_ids ?? []);
        }



        return redirect()->back()->with('success', 'Location updated successfully!');
    }

    // SOFT DELETE
    public function delete($id)
    {
        $location = Location::findOrFail($id);
        $location->update([
            'is_delete' => 1,
            'status'    => 0
        ]);

        return back()->with('success', 'Location deleted successfully!');
    }
}
