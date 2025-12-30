<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Itinerary;
use App\Models\Location;
use Illuminate\Http\Request;

class ItineraryAdminController extends Controller
{
    public function itinerary()
    {
        $itineraries = Itinerary::with(['locations','games'])
            ->orderByDesc('id')
            ->where('is_delete', 0)
            ->get();

        $locations = Location::where('status',1)->get();
        $games     = Game::where('status',1)->get();

        return view('admin.itineraries', compact(
            'itineraries',
            'locations',
            'games'
        ));
    }

    public function add(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:itineraries,name',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'days' => 'nullable|integer',
            'status' => 'required|in:0,1',
        ]);
        $imageName = null;
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $imageName = time().'_'.$file->getClientOriginalName();
                    $file->move(public_path('storage/itineraries'), $imageName);
                }

        $itinerary = Itinerary::create([
        'name' => $request->name,
        'description' => $request->description,
        'days' => $request->days,
        'status' => $request->status,
        'image' => $imageName
    ]);

    $itinerary->locations()->sync($request->location_ids);


        return back()->with('success', 'Added itinerary successfully!');
    }

public function update(Request $request, $id)
{
    $itinerary = Itinerary::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255|unique:itineraries,name,' . $id,
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        'days' => 'nullable|integer',
        'status' => 'required|in:0,1',
    ]);

    $itinerary->update([
        'name' => $request->name,
        'description' => $request->description,
        'days' => $request->days,
        'status' => $request->status,
    ]);

    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $imageName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('storage/itineraries'), $imageName);
        $itinerary->update(['image' => $imageName]);
    }

    if ($request->has('location_ids')) {
        $itinerary->locations()->sync($request->location_ids);
    }

    if ($request->has('game_ids')) {
        $itinerary->games()->sync($request->game_ids);
    }

    return back()->with('success', 'Updated itinerary successfully!');
}


public function delete($id)
{
    $itinerary = Itinerary::findOrFail($id);

    // detach pivot trước
    $itinerary->games()->detach();
    $itinerary->locations()->detach();

    // soft delete
    $itinerary->update([
        'is_delete' => 1,
        'status' => 0
    ]);

    return back()->with('success', 'Deleted itinerary successfully!');
}

    
}
