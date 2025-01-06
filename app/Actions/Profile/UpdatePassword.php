<?php

namespace App\Actions\Profile;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Response;
use Inertia\ResponseFactory;
use Illuminate\Validation\Rules;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdatePassword
{
    use AsAction, ResponseMethodsTrait;

    public function rules(): array
    {
        return [
            'password' => ['required','confirmed', Rules\Password::defaults()],

        ];
    }

    public function handle(User $user, array $validated): void
    {
        $user->update(['password' => Hash::make($validated['password'])]);
    }

    public function asController(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate($this->rules());
        $user = $request->user();

        $this->handle($user, $validated);

        if ($request->wantsJson()) {
            return $this->jsonResponse();
        }

        return to_route('profile.show');
    }


    public function jsonResponse(): JsonResponse
    {
        $user = new UserResource(auth()->user());
        return $this->sendResponse(compact('user'));
    }
}
