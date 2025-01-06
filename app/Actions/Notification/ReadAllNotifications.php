<?php

namespace App\Actions\Notification;

use App\Models\User;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;
class ReadAllNotifications
{
    use AsAction, ResponseMethodsTrait;

    public function handle(User $user): void
    {
        $user->unreadNotifications->markAsRead();
    }

    public function asController(Request $request): JsonResponse
    {
        $this->handle($request->user());

        return $this->sendResponse();
    }
}
