<?php

namespace App\Actions\Community;

use App\Http\Resources\PostResource;
use App\Http\Resources\SeriesResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\Series;
use App\Models\User;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class PostCommentDislikeToggle
{
    use AsAction, ResponseMethodsTrait;

    public function handle(User $user, PostComment $postComment): string
    {
        $like = $user->postCommentLikes()->where('post_comment_id', $postComment->id)->first();

        if ($like) {
            if ($like->value === true) {
                $like->update(['value' => false]);
                return 'Comment disliked successfully';
            } else {
                $like->delete();
                return 'Comment un disliked successfully';
            }
        } else {
            $user->postCommentLikes()->create([
                'post_comment_id' => $postComment->id,
                'value' => false
            ]);
            return 'Comment disliked successfully';
        }
    }

    public function asController(PostComment $postComment): JsonResponse
    {
        $user = auth()->user();
        $message = $this->handle($user, $postComment);

        return $this->sendResponse([], $message);
    }
}
