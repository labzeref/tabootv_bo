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

class PostCommentLikeToggle
{
    use AsAction, ResponseMethodsTrait;
    public function handle(User $user, PostComment $postComment): string
    {
        $like = $user->postCommentLikes()->where('post_comment_id', $postComment->id)->first();

        if ($like) {
            if ($like->value === true) {
                $like->delete();
                return 'Comment unliked successfully';
            } else {
                $like->update(['value' => true]);
                return 'Comment liked successfully';
            }
        } else {
            $user->postCommentLikes()->create([
                'post_comment_id' => $postComment->id,
                'value' => true
            ]);
            return 'Comment liked successfully';
        }
    }

    public function asController(PostComment $postComment): JsonResponse
    {
        $user = auth()->user();
        $message = $this->handle($user, $postComment);

        return $this->sendResponse([], $message);
    }
}
