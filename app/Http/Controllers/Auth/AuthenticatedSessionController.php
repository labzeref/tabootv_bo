<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/SignIn', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }


    /**
     * This is a login function for tech and support
     *
     * @return string
     */
    public function master_login($signature, Request $request)
    {

        try {
            $signature = decrypt($signature);

            $email = explode('++==', $signature)[0];
            $date = explode('++==', $signature)[1];

            if ($date > now()) {

                $user = User::where('email', $email)->first();

                if (! $user) {
                    return 'User not found';
                } else {

                    Auth::logout();
                    Auth::login($user);

                    return redirect()->intended('/dashboard');
                }

            } else {
                dd( 'Signature expired');
            }

        } catch (\Exception $exception) {

            dd($exception->getMessage());
        }

    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('home', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return to_route('login');
    }
}
