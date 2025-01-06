<?php

namespace App\Actions\Profile;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowProfile
{
    use AsAction, ResponseMethodsTrait;

    public function handle(): User
    {
        return auth()->user();
    }

    public function jsonResponse(): JsonResponse
    {
        $user = new UserResource($this->handle());

        return $this->sendResponse(compact('user'));
    }

    public function htmlResponse(): Response|ResponseFactory
    {
        return inertia('Profile/Index');
    }
}
