<?php

namespace App\Actions\User;

use App\Models\User;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class BlockUser
{
    use AsAction, ResponseMethodsTrait;

    public function handle(User $authUser, User $user): void
    {
        $authUser->blockedUsers()->attach($user->id);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function asController(User $user): JsonResponse
    {
        $authUser = auth()->user();

        if ($authUser->id == $user->id) {
            return $this->sendError('You cannot block yourself', [], 422);
        }

        if ($authUser->pivotBlockedUsers()->where('target_id', $user->id)->exists()) {
            return $this->sendError('User already blocked', [], 422);
        }

        $this->handle($authUser, $user);

        return $this->sendResponse();
    }
}
