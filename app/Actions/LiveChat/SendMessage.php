<?php

namespace App\Actions\LiveChat;

use App\Http\Resources\MessageResource;
use App\Jobs\SendMessageNotificationToAllUsersJob;
use App\Models\Chatroom;
use App\Models\Message;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class SendMessage
{
    use AsAction, ResponseMethodsTrait;

    public function rules(): array
    {
        return [
            'content' => 'required',
        ];
    }

    public function handle(Request $request): Message
    {
        $chatRoom = Chatroom::getGlobalRoom();

        return $chatRoom->messages()->create([
            'user_id' => auth()->id(),
            'content' => $request->input('content'),
        ])->load(['user']);
    }

    public function asController(Request $request): JsonResponse
    {
        $message = $this->handle($request);

        SendMessageNotificationToAllUsersJob::dispatch($message);

        $message = new MessageResource($message);

        return $this->sendResponse(compact('message'));
    }
}
