<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class RecycleProductController extends Controller

{
    public function trash()
    {
        $products = Product::where('is_delete', 1)->get();
        return view('admin.trashProduct', compact('products'));
    }

    public function restore($id)
    {
        Product::where('id', $id)->update(['is_delete' => 0]);
        return back()->with('success', 'Restored successfully!');
    }

    public function delete($id)
    {
        $hasOrder = DB::table('order_details')
            ->where('product_id', $id)
            ->exists();

        if ($hasOrder) {
            return back()->with('error', 'Cannot delete! Product is used in orders.');
        }
        Product::where('id', $id)->delete();

        return back()->with('success', 'Product permanently deleted successfully!');
    }
        
}
