<?php

namespace App\Actions\Community;

use App\Models\Post;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class DeletePost
{
    use AsAction, ResponseMethodsTrait;

    public function handle(Post $post): void
    {
        $post->delete();
    }

    public function asController(Request $request, Post $post): JsonResponse
    {
        if (auth()->id() !== $post->user_id) {
            return $this->sendError('You are not authorized to delete this post', [], 403);
        }

        $this->handle($post);

        return $this->sendResponse([], 'Post deleted successfully');
    }
}
