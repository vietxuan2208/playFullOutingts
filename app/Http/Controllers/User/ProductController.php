<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function detail($id)
    {
        $product = Product::findOrFail($id);

        $relatedProducts = Product::where('id', '!=', $product->id)
            ->where('is_delete', 0)
            ->inRandomOrder()
            ->take(8)
            ->get();

        return view('user.detail', compact('product', 'relatedProducts'));
    }

    public function shop(Request $request)
    {
        $query = Product::where('is_delete', 0)
            ->where('stock', '>', 0);

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }


        if ($request->filled('price')) {

            if (in_array('all', $request->price) && count($request->price) == 1) {
            } else {
                $query->where(function ($q) use ($request) {

                    foreach ($request->price as $priceRange) {

                        if ($priceRange === 'all') continue;

                        [$min, $max] = explode('-', $priceRange);

                        $q->orWhereBetween('price', [(float)$min, (float)$max]);
                    }
                });
            }
        }



        if ($request->sort === 'asc') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort === 'desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(6)->withQueryString();

        return view('user.shop', compact('products'));
    }
}
