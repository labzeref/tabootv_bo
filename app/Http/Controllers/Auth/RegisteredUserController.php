<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlanResource;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    public function choosePlan()
    {
        $plans = PlanResource::collection(Plan::where('name', '!=','lifetime')->get());

        return inertia('Payment/ChoosePlan', compact(['plans']));
    }

    public function create(Request $request): Response
    {
        $referralCode = $request->query('referral');

        return Inertia::render('Auth/SignUp', compact(['referralCode']));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'email' => strtolower($request->email),
        ]);

        $request->validate([
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'referral_code' => 'nullable|exists:'.User::class.',referral_code',
            'remember' => 'nullable|boolean',
            'privacy_policy' => 'nullable|boolean',
            'terms_and_condition' => 'nullable|boolean',
        ]);

        $user = User::whereRaw('LOWER(email) = ?', [strtolower($request->email)])
            ->orderBy('id','desc')->first();

        if ($user) {
            throw ValidationException::withMessages([
                'email' => 'Email already exists',
            ]);
        }

        if (! $request->privacy_policy || ! $request->terms_and_condition) {
            throw ValidationException::withMessages([
                'terms_and_condition' => 'Please agree to the terms and conditions and privacy policy',
            ]);
        }


        $referralId = User::where('referral_code', $request->referral_code)->value('id');

        $user = User::create([
            'referral_id' => $referralId,
            'email' => strtolower($request->email),
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user, $request->remember);

        return redirect(route('home', absolute: false));
    }
}
