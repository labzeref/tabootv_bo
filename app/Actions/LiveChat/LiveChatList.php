<?php

namespace App\Actions\LiveChat;

use App\Http\Resources\MessageResource;
use App\Models\Chatroom;
use App\Models\User;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Console\Command;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class LiveChatList
{
    use AsAction, ResponseMethodsTrait;

    public function handle(User $authUser): CursorPaginator
    {
        $chatRoom = Chatroom::getGlobalRoom();

        $blockedUsersIds = $authUser->blockUsers->pluck('id')->toArray();

        return $chatRoom->messages()->with('user')->whereNotIn('user_id', $blockedUsersIds)->latest('id')->cursorPaginate(10);
    }

    public function asController(): JsonResponse
    {
        $messages = $this->handle(auth()->user());
        $messages = MessageResource::collection($messages)->resource;

        return $this->sendResponse(compact('messages'));
    }
}
