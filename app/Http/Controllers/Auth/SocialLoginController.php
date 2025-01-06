<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::updateOrCreate([
                'email' => strtolower($googleUser->email),
            ], [
                'google_id' => $googleUser->id,
                'first_name' => $googleUser->name,
                'google_token' => $googleUser->token,
                'google_refresh_token' => $googleUser->refreshToken,
                'password' => Hash::make(Str::random()),
                'email_verified_at' => now(),
            ]);

            Auth::login($user);
        } catch (\Throwable $throwable) {
            return to_route('sign-in')->with('error', 'Failed to login with Google');
        }

        return to_route('home');
    }
}
