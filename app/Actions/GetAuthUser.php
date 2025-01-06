<?php

namespace App\Actions;

use App\Http\Resources\UserResource;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAuthUser
{
    use AsAction, ResponseMethodsTrait;

    public function handle()
    {

    }

    public function asController(Request $request): JsonResponse
    {
        $user = auth()->user();

        $response = [
            'user' => new UserResource($user),
            'subscribed' => $user->subscribed(),
        ];

        return $this->sendResponse($response);
    }
}
