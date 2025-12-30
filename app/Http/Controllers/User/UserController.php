<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Storage; // Used to handle files in storage (images, documents, etc.)

class UserController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        $orders = Order::with('orderDetails.product')
            ->where('user_id', $user->id)
            ->orderBy('purchase_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return view('user.profile', compact('orders'));
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Validate dữ liệu
        $request->validate([
            'name'     => 'required|string|max:100',
            'address'  => 'nullable|string|max:255',
            'birthday' => 'nullable|date|before:' . now()->subYears(16)->format('Y-m-d'),
            'gender'   => 'nullable|in:male,female,other',
            'phone'    => 'nullable|string|max:20',
            'photo'    => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ], [
            // Custom error message
            'birthday.before' => 'You must be at least 16 years old.',
        ]);


        // Gán các trường đơn giản
        $user->name     = $request->name;
        $user->address  = $request->address;
        $user->birthday = $request->birthday;
        $user->gender   = $request->gender;
        $user->phone    = $request->phone;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $imageName = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('storage/avatars'), $imageName);
            $user->photo = $imageName;
        }
        // Lưu toàn bộ
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
