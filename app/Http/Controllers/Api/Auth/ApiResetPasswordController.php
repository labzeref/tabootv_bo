<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class ApiResetPasswordController extends Controller
{
    public function forget(Request $request)
    {
        $request->merge([
            'email' => strtolower($request->string('email')),
        ]);

        $user = User::whereRaw('LOWER(email) = ?', [$request->email])
            ->orderBy('id','desc')->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'Email does not exist',
            ]);
        }

        $otp = rand(100000, 999999);

        $user->update(['remember_token' => $otp]);

        Mail::to($request->email)->send(new ResetPasswordMail($otp));

        return $this->sendResponse();
    }

    public function reset(Request $request)
    {
        $request->merge([
            'email' => strtolower($request->string('email')),
        ]);

        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::where('email', $request->email)
            ->where('remember_token', $request->otp)
            ->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'otp' => 'Invalid OTP',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'remember_token' => null,
        ]);

        return $this->sendResponse();
    }
}
