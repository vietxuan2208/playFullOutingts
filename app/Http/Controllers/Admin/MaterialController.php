<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    // Hiển thị danh sách materials
    public function material()
    {
        $materials = Material::with('games')
        ->where('is_delete', 0)
        ->where('status', 1)
        ->orderBy('id', 'desc')
        ->get();
         $games = Game::all(); 

        return view('admin.material', compact('materials','games'));
    }

    // Thêm material
public function add(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:materials,name',
        'description' => 'nullable|string',
        'status' => 'required|in:0,1',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048'
    ]);

    $imageName = null;
    if ($request->hasFile('image')) {
       $file = $request->file('image');
            $imageName = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('storage/materials'), $imageName);
    }

    Material::create([
        'name' => $request->name,
        'description' => $request->description,
        'status' => $request->status,
        'is_delete' => 0,
        'image' => $imageName,
    ]);

    return back()->with('success', 'Added material successfully!');
}

// Cập nhật material
public function update(Request $request, $id)
{
    $material = Material::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255|unique:materials,name,' . $id,
        'description' => 'nullable|string',
        'status' => 'required|in:0,1',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    ]);

    if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('storage/materials'), $imageName);
            $material->image = $imageName;
        }

        $material->name = $request->name;
        $material->status = $request->status;
        $material->games()->sync($request->games ?? []);
        $material->save();

    return back()->with('success', 'Updated material successfully!');
}

    // Xóa material (soft delete)
    public function delete($id)
    {
        $material = Material::findOrFail($id);
        $material->is_delete = 1;
        $material->status = 0;
        $material->save();

        return back()->with('success', 'Deleted material successfully!');
    }
}
