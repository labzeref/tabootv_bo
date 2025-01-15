<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\DeviceToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ApiLoginController extends Controller
{
    public function store(Request $request)
    {
        $request->merge([
            'email' => strtolower($request->string('email')),
        ]);

        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'device_token' => ['nullable', 'string'],

        ]);
        $request->merge([
            'email' => strtolower($request->email),
        ]);

        $user = User::whereRaw('LOWER(email) = ?', [$request->email])
            ->orderBy('id','desc')->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'email does not exist.',
            ]);
        }

        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'Invalid password.',
            ]);
        }

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

//    public function loginById($id)
//    {
//        $user = User::find($id);
//
//        if (!$user) {
//            throw ValidationException::withMessages([
//                'email' => 'Credentials do not match.',
//            ]);
//        }
//
//        $response = [
//            'user' => new UserResource($user),
//            'token' => $user->createToken('General Token')->plainTextToken,
//        ];
//
//        return $this->sendResponse($response);
//    }

//    public function google(Request $request)
//    {
//        $request->validate([
//            'token' => ['required', 'string'],
//        ]);
//
//        $url = "https://oauth2.googleapis.com/tokeninfo?id_token={$request->token}";
//
//        $response = json_decode(file_get_contents($url), true);
//
//        if (isset($response['error'])) {
//            return $this->sendError('Invalid Google ID Token', 401);
//        }
//
//        $google_id = $response['sub'];
//        $name = $response['name'];
//        $email = $response['email'];
//
//        $user = User::updateOrCreate([
//            'email' => strtolower($email),
//        ], [
//            'google_id' => $google_id,
//            'first_name' => $name,
//            'password' => Hash::make(Str::random()),
//            'email_verified_at' => now(),
//        ]);
//
//        $response = [
//            'user' => new UserResource($user),
//            'token' => $user->createToken('General Token')->plainTextToken,
//        ];
//
//        return $this->sendResponse($response);
//    }

    public function destroy(Request $request)
    {
        $user = auth()->user();

        // Remove the device token
        if ($request->device_token) {
            $user->deviceTokens()->where('value', $request->device_token)->delete();
        }
        $request->user()->currentAccessToken()->delete();

        return $this->sendResponse([], 'User Log Out Successfully');
    }
}
