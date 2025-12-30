<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /** Redirect to Google OAuth provider */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /** Handle Google callback, find or create user, then login */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $email = $googleUser->getEmail();
            if (!$email) {
                return redirect()->route('register')->withErrors(['email' => 'Google account did not provide an email.']);
            }

            $user = User::where('email', $email)->first();
            $isNewUser = false;
            
            if (!$user) {
                $username = $googleUser->getNickname() ?? explode('@', $email)[0];
                $username = preg_replace('/[^A-Za-z0-9_\.\-]/', '', $username);

                // ensure unique username
                $base = $username;
                $i = 1;
                while (User::where('username', $username)->exists()) {
                    $username = $base . $i;
                    $i++;
                }

                // Get name from Google or use email username as fallback
                $name = $googleUser->getName();
                if (!$name) {
                    $name = explode('@', $email)[0];
                }

                $user = User::create([
                    'name' => $name,
                    'username' => $username,
                    'email' => $email,
                    'password' => Hash::make(Str::random(24)),
                    'role_id' => 1,
                    'status' => 1,
                    'is_delete' => 0,
                    'google_id' => $googleUser->getId(),
                    'password_set' => false,
                    'photo' => $googleUser->getAvatar(),
                ]);
                
                $isNewUser = true;
            }

            Auth::login($user, true);

            // If new user, redirect to set password
            if ($isNewUser) {
                return redirect()->route('set-password.show');
            }

            if ($user->role_id == 2) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('user.dashboard');
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['error' => 'Google authentication failed: ' . $e->getMessage()]);
        }
    }
}
