<?php

namespace App\Actions\Community;

use App\Http\Resources\PostResource;
use App\Http\Resources\SeriesResource;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\Series;
use App\Models\User;
use App\Models\Video;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class PostLikeAction
{
    use AsAction, ResponseMethodsTrait;


    public function handle(User $user, Post $post): string
    {
        $like = $user->postLikes()->where('post_id', $post->id)->first();

        if ($like) {
            if ($like->value === true) {
                $like->delete();
                return 'Video unliked successfully';
            } else {
                $like->update(['value' => true]);
                return 'Video liked successfully';
            }
        } else {
            $user->postLikes()->create([
                'post_id' => $post->id,
                'value' => true
            ]);
            return 'Post liked successfully';
        }
    }

    public function asController(Post $post): JsonResponse
    {
        $user = auth()->user();
        $message = $this->handle($user, $post);
        $likes_count = PostLike::where('post_id', $post->id)->where('value',true)->count();
        $dislikes_count = PostLike::where('post_id', $post->id)->where('value',false)->count();

        return $this->sendResponse(['likes_count' => $likes_count,'dislikes_count'=>$dislikes_count], $message);
    }
}
