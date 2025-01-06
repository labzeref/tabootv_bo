<?php

namespace App\Actions\Video;

use App\Models\User;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class ToggleVideoAutoplay
{
    use AsAction, ResponseMethodsTrait;

    public function handle(User $user): void
    {
        $user->update(['video_autoplay' => !$user->video_autoplay]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function asController(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->handle($request->user());

        return $this->sendResponse();
    }
}
