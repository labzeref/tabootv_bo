<?php

namespace App\Actions\Profile;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateDp
{
    use AsAction, ResponseMethodsTrait;

public function rules(Request $request): array
{
    return [
        'base64' => 'nullable|boolean',
        'dp' => $request->input('base64') ? 'required|string' : 'required|mimetypes:image/*|max:4096',
    ];
}

    public function handle(User $user, $file, $base64 = false): void
    {
        if ($base64) {
            $user->addMediaFromBase64($file)->toMediaCollection('dp');
        } else {
            $user->addMedia($file)->toMediaCollection('dp');
        }
    }

    public function asController(Request $request): RedirectResponse|JsonResponse
    {
        $base64 = $request->input('base64') ?? false;
        $file = $base64 ? $request->input('dp') : $request->file('dp');
        $user = $request->user(); // Get the authenticated user

        $this->handle($user, $file, $base64);

        if ($request->wantsJson()) {
            $this->jsonResponse($user);
        }

        return to_route('profile.show');
    }

    public function jsonResponse(): JsonResponse
    {
        $user = new UserResource(auth()->user());
        return $this->sendResponse(compact('user'));
    }

}


