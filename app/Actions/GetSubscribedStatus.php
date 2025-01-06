<?php

namespace App\Actions;

use App\Models\User;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use LaravelIdea\Helper\App\Models\_IH_Subscription_QB;
use Lorisleiva\Actions\Concerns\AsAction;

class GetSubscribedStatus
{
    use AsAction, ResponseMethodsTrait;

    public function handle(User $user): HasMany|_IH_Subscription_QB|bool
    {
        return $user->subscribed();
    }

    public function asController(Request $request): JsonResponse
    {
        $authUser = auth()->user();

        $subscribed = $this->handle($authUser);

        return $this->sendResponse(['subscribed' => $subscribed]);
    }
}
