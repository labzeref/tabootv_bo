<?php

namespace App\Http\Controllers;

use App\Enums\GenderEnum;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileCompleteController extends Controller
{
    public function create()
    {
        return inertia('Profile/ProfileComplete');
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'display_name' => 'required|max:20',
            'gender' => ['required', Rule::enum(GenderEnum::class)],
            'country_id' => 'required|exists:countries,id',
            'phone_number' => 'nullable|phone:INTERNATIONAL',
        ]);



        $request->user()->update($validatedData + ['profile_completed' => true]);

        return to_route('home');
    }


}
