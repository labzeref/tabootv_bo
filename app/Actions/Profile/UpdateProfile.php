<?php

namespace App\Actions\Profile;

use App\Enums\GenderEnum;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateProfile
{
    use AsAction,ResponseMethodsTrait;

    public function rules(): array
    {
        return [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'display_name' => 'required|max:20',
            'gender' => ['required', Rule::enum(GenderEnum::class)],
            'country_id' => 'required|exists:countries,id',
            'phone_number' => 'nullable|phone:INTERNATIONAL',
        ];
    }

    public function handle(User $user,array $validated):void
    {
        $user->update($validated);
    }

    public function asController(Request $request): void
    {
        $validated = $request->validate($this->rules());

        $user = $request->user();
        $this->handle($user, $validated);
    }

    public function htmlResponse(): RedirectResponse
    {
        return back()->with('success', 'Profile updated successfully');
    }
    public function jsonResponse(): JsonResponse
    {
        $user_data = new UserResource(auth()->user());
        return $this->sendResponse(compact('user_data'));
    }
}
