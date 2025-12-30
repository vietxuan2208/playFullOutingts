<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use App\Models\Location;

class RecycleLocationController extends Controller

{
    public function trash()
    {
        $locations = Location::where('is_delete', 1)->get();
        return view('admin.trashLocation', compact('locations'));
    }

    public function restore($id)
    {
        Location::where('id', $id)->update(['is_delete' => 0]);
        return back()->with('success', 'Restored successfully!');
    }

    public function forceDelete($id)
    {
        Location::where('id', $id)->delete();
        return back()->with('success', 'Permanently deleted successfully!');
    }
        
}
