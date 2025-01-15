<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\DeviceToken;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ApiRegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->merge([
            'email' => strtolower($request->string('email')),
        ]);

        $request->validate([
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'referral_code' => 'nullable|exists:'.User::class.',referral_code',
            'remember' => 'nullable|boolean',
            'privacy_policy' => 'nullable|boolean',
            'terms_and_condition' => 'nullable|boolean',
        ]);

        $user = User::whereRaw('LOWER(email) = ?', [$request->email])
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

        if ($request->device_token) {
            // Reassign or create the device token
            DeviceToken::updateOrCreate(
                ['value' => $request->device_token], // Match on device token
                ['user_id' => $user->id] // Assign or reassign to the current user
            );
        }
        $response = [
            'user' => new UserResource($user),
            'subscribed' => $user->subscribed(),
            'token' => $user->createToken('General Token')->plainTextToken,
        ];

        return $this->sendResponse($response);
    }
}
