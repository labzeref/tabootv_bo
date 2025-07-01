<?php

namespace App\Actions\Comment;

use App\Http\Resources\CommentResource;
use App\Http\Resources\SeriesResource;
use App\Http\Resources\VideoResource;
use App\Models\Comment;
use App\Models\Series;
use App\Models\User;
use App\Models\Video;
use App\Notifications\CommentReplyNotification;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Notification;
use Lorisleiva\Actions\Concerns\AsAction;

class PostVideoComment
{
    use AsAction, ResponseMethodsTrait;

    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:1500', 'min:1'],
            'parent_id' => ['nullable'],
        ];
    }

    public function handle(User $user, Video $video, Request $request): Comment
    {
        $comment = $video->comments()->create([
            'user_id' => $user->id,
            'parent_id' => $request->parent_id,
            'content' => $request['content'],
        ]);

        if ($request->parent_id) {
            $parentComment = $comment->parent;

            Notification::send($parentComment->user, new CommentReplyNotification($user, $video));
        }

        return $comment;
    }

    public function asController(Video $video, Request $request): JsonResponse
    {
        $user = auth()->user();
        $comment = $this->handle($user, $video, $request);
        $comment = new CommentResource($comment->load('user'));

        return $this->sendResponse(compact('comment'), 'added comment on video successfully');
    }
}
