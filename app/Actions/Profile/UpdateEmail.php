<?php

namespace App\Actions\Profile;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Response;
use Inertia\ResponseFactory;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateEmail
{
    use AsAction, ResponseMethodsTrait;

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore(auth()->user()->id),
            ],
        ];
    }

    public function handle(User $user, array $validated): void
    {
        $user->update($validated);
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
