<?php

namespace App\Actions\Community;

use App\Http\Resources\PostResource;
use App\Http\Resources\SeriesResource;
use App\Models\Post;
use App\Models\Series;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class PostList
{
    use AsAction, ResponseMethodsTrait;

    public function asController(Request $request,  $post_id = null): JsonResponse
    {

        // Retrieve the list of posts
        $posts = Post::withCount(['postComments', 'likes', 'dislikes'])
            ->with([
                'user',
                'likes' => fn($likes) => $likes->where('user_id', auth()->id()),
                'dislikes' => fn($likes) => $likes->where('user_id', auth()->id()),
                'recent_like' => fn($likes) => $likes->orderByDesc('created_at')->take(1),
            ])
            ->latest('created_at')
            ->paginate(10);

        // Transform the posts into resources
        $posts = PostResource::collection($posts)->resource;

        // Add the specified post to the top of the collection

        if ($post_id) {
            $post = Post::find($post_id);
            $postResource = new PostResource($post->load([
                'user',
                'likes' => fn($likes) => $likes->where('user_id', auth()->id()),
                'dislikes' => fn($likes) => $likes->where('user_id', auth()->id()),
                'recent_like' => fn($likes) => $likes->orderByDesc('created_at')->take(1),
            ]));
            $posts->prepend($postResource);
        }

        return $this->sendResponse(compact(['posts']));
    }
}
