<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Itinerary;


class RecycleItineraryController extends Controller

{
    public function trash()
    {
        $itineraries = Itinerary::where('is_delete', 1)->get();
        return view('admin.trashItineraries', compact('itineraries'));
    }

    public function restore($id)
    {
        $itinerary = Itinerary::findOrFail($id);
        $itinerary->is_delete = 0;
        $itinerary->status = 1;
        $itinerary->save();

        return back()->with('success', 'Restore Itinerary Success!');
    }



    public function delete($id)
    {
        Itinerary::where('id', $id)->delete();
        return back()->with('success', 'Delete Itinerary Sussess!');
    }
        
}
