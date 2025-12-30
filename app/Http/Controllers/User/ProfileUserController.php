<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileUserController extends Controller
{
    public function profile($id)
    {
        $user = User::findOrFail($id);
        return view('user.profile', compact('user'));
    }

    public function updateProfile(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|max:255|unique:users,username,' . $id,
            'fullname' => 'nullable|max:255',
            'phone'    => ['nullable', 'regex:/^\d{10}$/'],
            'birthday' => 'nullable|date',
            'address'  => 'nullable|max:500',
            'email'    => 'required|email|max:255|unique:users,email,' . $id,
            'gender'   => 'nullable|in:male,female,other',
        ]);

        $user->username = $request->username;
        $user->fullname = $request->fullname;
        $user->birthday = $request->birthday;
        $user->address  = $request->address;
        $user->phone    = $request->phone;
        $user->email    = $request->email;
        $user->gender   = $request->gender;

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }


    public function updatePhoto(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/avatars'), $filename);

            if ($user->photo && file_exists(public_path('storage/avatars/' . $user->photo))) {
                unlink(public_path('storage/avatars/' . $user->photo));
            }

            $user->photo = $filename;
            $user->save();
        }

        return redirect()->back()->with('success', 'Avatar updated successfully!');
    }


    public function changePassword(Request $request, $id)
    {
        $request->validate([
            'currentPassword' => 'required',
            'newPassword'     => 'required|min:6|confirmed',
        ]);

        $user = User::findOrFail($id);

        if (!Hash::check($request->currentPassword, $user->password)) {
            return redirect()->back()->with('error', 'Current password is incorrect!');
        }

        $user->password = Hash::make($request->newPassword);
        $user->save();

        return redirect()->back()->with('success', 'Password updated successfully!');
    }
}
