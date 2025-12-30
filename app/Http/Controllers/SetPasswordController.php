<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SetPasswordController extends Controller
{
    /**
     * Show Set Password form
     */
    public function show()
    {
        $user = Auth::user();
        
        // Only show if user hasn't set password yet
        if ($user && !$user->password_set) {
            return view('auth.set-password');
        }
        
        return redirect()->route('user.dashboard');
    }

    /**
     * Store the password
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Check if user has already set password
        if ($user->password_set) {
            return redirect()->route('user.dashboard');
        }

        // Validate
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ], [
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Password confirmation does not match',
        ]);

        // Update user password
        $user->update([
            'password' => Hash::make($request->password),
            'password_set' => true,
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Password has been set successfully!');
    }
}
