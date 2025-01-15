<?php

namespace App\Actions\Community;

use App\Http\Resources\PostResource;
use App\Http\Resources\SeriesResource;
use App\Models\Post;
use App\Models\Series;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class PostStore
{
    use AsAction, ResponseMethodsTrait;

    public function asController(Request $request): JsonResponse
    {
        $request->validate([
            'caption' => 'required|string',
            'post_image' => 'nullable|image',
        ]);

        if ($request->user()->channel()->doesntExist()) {
            return $this->sendError('You are not creator', [], 400);
        }

        $post =  Post::create([
            'caption' => $request->caption,
            'user_id' => auth()->id(),
        ]);

        if ($request->hasFile('post_image')) {
            $post->addMediaFromRequest('post_image')->toMediaCollection('post-image');
        }

        $post = new PostResource($post->loadCount(['postComments','likes','dislikes'])
            ->load([
                'media',
                'user',
                'likes' => fn($likes) => $likes->where('user_id', auth()->id()),
                'dislikes' => fn($likes) => $likes->where('user_id', auth()->id()),
            ])
        );
        return $this->sendResponse(compact(['post']));
    }
}
