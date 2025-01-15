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

class PostCommentRepliesList
{
    use AsAction, ResponseMethodsTrait;

    public function handle(PostComment $postComment): CursorPaginator
    {
        return PostComment::query()
            ->with([
                'user',
                'likes' => fn($likes) => $likes->where('user_id', auth()->id()),
                'dislikes' => fn($likes) => $likes->where('user_id', auth()->id()),
            ])
            ->withCount(['replies'])
            ->where('parent_id', $postComment->id)
            ->orderBy('id')
            ->cursorPaginate(10000);
    }

    public function asController(PostComment $postComment): JsonResponse
    {
        $comments = $this->handle($postComment);

        $postComment = PostCommentResource::collection($comments)->resource;

        return $this->sendResponse(compact('postComment'));
    }
}
