<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('is_delete', 0)
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.product', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:products,name',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'stock'       => 'required|integer',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);

        $photoName = 'no-image.jpg';

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $photoName = time() . '_' . uniqid() . '.' . $request->photo->extension();
            $request->photo->move(public_path('storage/images'), $photoName);
        }

        Product::create([
            'name'        => $request->name,
            'photo'       => $photoName,
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'is_delete'   => 0,
        ]);

        return redirect()->route('product_admin')->with('success', 'Product added successfully!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id'          => 'required|exists:products,id',
            'name'        => 'required|string|max:100|unique:products,name,'.$request->id,
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'stock'       => 'required|integer',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);

        $product = Product::findOrFail($request->id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $imageName = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('storage/images'), $imageName);
            $product->photo = $imageName;
        }

        $product->save();

        return redirect()->back()->with('success', 'Product update successful!');
    }

    public function delete(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->is_delete = 1;
        $product->save();

        return redirect()->back()->with('success', 'Product deleted successfully.');
    }
}
