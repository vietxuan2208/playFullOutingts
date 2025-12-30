<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function user(){
        $data = [
            'users' => User::where('is_delete', 0)->orderBy('id', 'desc')->get()
        ];
        return view('admin/user')->with($data);
    }
    public function block(User $user)
    {
        $user->status = $user->status == 1 ? 0 : 1;
        $user->save();

        return redirect()->back()->with('success', 'User status updated successfully.');
    }
    public function unBlock(User $user)
    {
        $user->status = $user->status == 1 ? 0 : 1;
        $user->save();
        $message = $user->status == 1 
            ? 'User has been unblocked successfully.' 
            : 'User has been blocked successfully.';

        return redirect()->back()->with('success', $message);
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->fullname = $request->fullname;
        $user->birthday = $request->birthday;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->gender = $request->gender;
        $user->status = $request->status;
        $user->role_id = $request->role_id;

        // Upload ảnh nếu có
    if ($request->hasFile('photo')) {
        $file = $request->file('photo');
        $filename = time().'_'.$file->getClientOriginalName();

        // Lưu trực tiếp vào public/storage/avatars
        $file->move(public_path('storage/avatars'), $filename);

        $user->photo = $filename;
    }

        $user->save();

        return redirect()->back()->with('success', 'User updated successfully!');
    }

    public function trash(){
        $data = [
            'users' => User::where('is_delete', 1)->get()
        ];
        return view('admin/trashUser')->with($data);
    }
    public function store(Request $request)
    {
    // Validate dữ liệu
    $request->validate([
        'username' => 'required|string|max:255|unique:users',
        'email' => 'required|email|unique:users',
        'phone' => 'required|string|max:15',
        'address' => 'required|string|max:255',
        'gender' => 'required|in:male,female,other',
        'password' => 'required|string|min:6',
        'role_id' => 'required|in:1,2',
        'status' => 'required|in:0,1',
        'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    ]);

    $photoName = null;
    if ($request->hasFile('photo')) {
        $file = $request->file('photo');
        $photoName = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('storage/avatars'), $photoName);
    }

    User::create([
        'username' => $request->username,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role_id' => $request->role_id,
        'status' => $request->status,
        'photo' => $photoName,
        'is_delete' => 0,
        'phone' => $request->phone,
        'address' => $request->address,
        'gender' => $request->gender,
    ]);

    return redirect()->back()->with('success', 'Admin added successfully!');
    }



}
