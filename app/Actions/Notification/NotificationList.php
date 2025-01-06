<?php

namespace App\Actions\Notification;

use App\Http\Resources\NotificationResource;
use App\Models\User;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class NotificationList
{
    use AsAction, ResponseMethodsTrait;

    public function handle(User $user)
    {
        return $user->notifications;
    }

    public function asController(Request $request): JsonResponse
    {
        $notifications = NotificationResource::collection($this->handle($request->user()));

        return $this->sendResponse(compact('notifications'));
    }
}
