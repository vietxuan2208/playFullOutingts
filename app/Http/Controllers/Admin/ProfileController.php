<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    // Hiển thị trang profile
    public function profile($id)
    {
        $user = User::findOrFail($id);
        return view('admin.profile', compact('user'));
    }

    /* ------------------------------------------------------
        UPDATE PROFILE (FORM SUBMIT NORMAL)
    ------------------------------------------------------ */
    public function updateProfile(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|max:255|unique:users,username,' . $id,
            'fullname' => 'nullable|max:255',
             'birthday' => 'nullable|date|before:' . now()->subYears(16)->format('Y-m-d'),
            'address'  => 'nullable|max:500',
            'phone'    => ['nullable', 'regex:/^\d{10}$/'],
            'email'    => 'required|email|max:255',
            'gender'   => 'nullable|in:male,female,other',
            ], [
                'birthday.before' => 'You must be at least 16 years old.',
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


    /* ------------------------------------------------------
        UPDATE AVATAR (FORM SUBMIT NORMAL)
    ------------------------------------------------------ */
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

            // Xóa ảnh cũ
            if ($user->photo && file_exists(public_path('storage/avatars/' . $user->photo))) {
                unlink(public_path('storage/avatars/' . $user->photo));
            }

            $user->photo = $filename;
            $user->save();
        }

        return redirect()->back()->with('success', 'Avatar updated successfully!');
    }


    /* ------------------------------------------------------
        CHANGE PASSWORD (FORM SUBMIT NORMAL)
    ------------------------------------------------------ */
    public function changePassword(Request $request, $id)
    {
        $request->validate([
            'currentPassword' => 'required',
            'newPassword'     => 'required|min:6|confirmed',
        ]);

        $user = User::findOrFail($id);

        // Check current password
        if (!Hash::check($request->currentPassword, $user->password)) {
            return redirect()->back()->with('error', 'Current password is incorrect!');
        }

        // Update password
        $user->password = Hash::make($request->newPassword);
        $user->save();

        return redirect()->back()->with('success', 'Password updated successfully!');
    }
}
