<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/ForgotPassword', [
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Normalize the email input to lowercase
        $normalizedEmail = strtolower($request->email);

        // Retrieve the user using a case-insensitive email search
        $user = User::whereRaw('LOWER(email) = ?', [$normalizedEmail])
            ->orderBy('id','desc')->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => ['We couldn’t find a user with that email address.'],
            ]);
        }

        // Send the password reset link
        $status = Password::sendResetLink([
            'email' => $user->email, // Use the matched user's email
        ]);

        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }

}
