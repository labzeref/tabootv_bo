<?php

namespace App\Actions\Community;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostCommentResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\SeriesResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\Series;
use App\Models\Video;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class PostCommentList
{
    use AsAction, ResponseMethodsTrait;

    public function handle(Post $post): CursorPaginator
    {
        return PostComment::query()
            ->with([
                'user',
                'replies' => fn ($replies) => $replies->with([
                    'user',
                    'likes' => fn($likes) => $likes->where('user_id', auth()->id()),
                    'dislikes' => fn($likes) => $likes->where('user_id', auth()->id()),
                ]),
                'likes' => fn($likes) => $likes->where('user_id', auth()->id()),
                'dislikes' => fn($likes) => $likes->where('user_id', auth()->id()),
            ])
            ->withCount(['replies'])
            ->where('post_id', $post->id)
            ->where('parent_id', null)
            ->latest('id')
            ->cursorPaginate(10000);
    }

    public function asController(Post $post): JsonResponse
    {
        $comments = $this->handle($post);

        $postComment = PostCommentResource::collection($comments)->resource;

        return $this->sendResponse(compact('postComment'));
    }
}
