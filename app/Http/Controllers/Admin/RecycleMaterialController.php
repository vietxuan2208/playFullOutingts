<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Material;

class RecycleMaterialController extends Controller

{
    public function trash()
    {
        $materials = Material::where('is_delete', 1)->get();
        
        return view('admin.trashMaterial', compact('materials'));
    }

    public function restore($id)
    {
        Material::where('id', $id)->update(['is_delete' => 0]);
        return back()->with('success', 'Restored successfully!');
    }

    public function forceDelete($id)
    {
        $material = Material::findOrFail($id);
        if ($material->games()->exists()) {
            return back()->with('error', 'Cannot delete! This material is being used in a game.');
        }

        $material->delete();

        return back()->with('success', 'Permanently deleted successfully!');
    }
        
}
