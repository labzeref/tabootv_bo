<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Auth/ResetPassword', [
            'email' => $request->email,
            'token' => $request->route('token'),
        ]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the request
        $request->validate([
            'token' => ['required'], // Validate that a token is provided
            'password' => ['required', 'string', 'min:8', 'confirmed'], // Validate the password
        ]);


        // Normalize the email input to lowercase
        $normalizedEmail = strtolower($request->email);

        // Retrieve the user using a case-insensitive email search
        $passwordReset = DB::table('password_reset_tokens')->whereRaw('LOWER(email) = ?', [$normalizedEmail])
            ->orderBy('created_at','desc')->first();

        if (!$passwordReset) {
            throw ValidationException::withMessages([
                'password' => ['The password reset token is invalid.'],
            ]);
        }

        if (!$passwordReset || !Hash::check($request->token, $passwordReset->token)) {
            throw ValidationException::withMessages([
                'password' => ['The password reset token is invalid or expired'],
            ]);
        }

        // Optionally, check token expiration (e.g., 60 minutes)
        if (now()->subMinutes(60)->greaterThan($passwordReset->created_at)) {
            throw ValidationException::withMessages([
                'token' => ['The password reset token has expired.'],
            ]);
        }

        // Retrieve the user using a case-insensitive email search
        $user = User::whereRaw('LOWER(email) = ?', [$normalizedEmail])
            ->orderBy('id','desc')->first();


        if (! $user) {
            throw ValidationException::withMessages([
                'email' => ['We couldn’t find a user with that email address.'],
            ]);
        }

        // Reset the user's password
        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ])->save();

        // Delete the password reset token after successful reset
        DB::table('password_reset_tokens')->where('token', $request->token)->delete();

        return redirect()->route('login')->with('status', __('Your password has been reset successfully.'));

    }

}
