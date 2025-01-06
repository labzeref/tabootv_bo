<?php

namespace App\Actions\Comment;

use App\Http\Resources\SeriesResource;
use App\Http\Resources\VideoResource;
use App\Models\Comment;
use App\Models\Series;
use App\Models\User;
use App\Models\Video;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Concerns\AsAction;

class CommentLikeToggle
{
    use AsAction, ResponseMethodsTrait;

    public function handle(User $user, Comment $comment): string
    {
        $like = $user->commentLikes()->where('comment_id', $comment->id)->first();

        if ($like) {
            if ($like->value === true) {
                $like->delete();
                return 'Comment unliked successfully';
            } else {
                $like->update(['value' => true]);
                return 'Comment liked successfully';
            }
        } else {
            $user->commentLikes()->create([
                'comment_id' => $comment->id,
                'value' => true
            ]);
            return 'Comment liked successfully';
        }
    }

    public function asController(Comment $comment): JsonResponse
    {
        $user = auth()->user();
        $message = $this->handle($user, $comment);

        return $this->sendResponse([], $message);
    }
}
