<?php

namespace App\Actions\Notification;

use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Notifications\DatabaseNotification;

class DestroyNotification
{
    use AsAction, ResponseMethodsTrait;

    public function handle(DatabaseNotification $notification): void
    {
        $notification->delete();
    }

    public function asController(DatabaseNotification $notification): JsonResponse
    {
        $this->handle($notification);

        return $this->sendResponse();
    }
}
