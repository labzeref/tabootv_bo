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

class PostSingle
{
    use AsAction, ResponseMethodsTrait;

    public function asController(Post $post): JsonResponse
    {

        $post = new PostResource($post->loadCount(['postComments','likes','dislikes'])
            ->load([
                'user',
                'likes' => fn($likes) => $likes->where('user_id', auth()->id()),
                'dislikes' => fn($likes) => $likes->where('user_id', auth()->id()),
            ])
            );
        return $this->sendResponse(compact(['post']));
    }
}
