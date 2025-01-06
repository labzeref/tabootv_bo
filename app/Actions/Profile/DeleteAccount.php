<?php

namespace App\Actions\Profile;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteAccount
{
    use AsAction, ResponseMethodsTrait;

    public function handle(User $user):void
    {

        $email = $user->email . '//' . (int) ( microtime(true) * 1000000);
        $user->email = $email;  // changed email before soft-delete
        $user->save();

        $user->delete();
    }

    public function asController(Request $request):\Symfony\Component\HttpFoundation\Response
    {
        $user = $request->user();
        $this->handle($user);

        if ($request->wantsJson()) {
            return $this->jsonResponse();
        }

        return Inertia::location(route('choose-plan'));

    }

    public function jsonResponse(): JsonResponse
    {
        return $this->sendResponse();
    }
}
