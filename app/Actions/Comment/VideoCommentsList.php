<?php

namespace App\Actions\Comment;

use App\Http\Resources\CommentResource;
use App\Http\Resources\SeriesResource;
use App\Http\Resources\VideoResource;
use App\Models\Comment;
use App\Models\Series;
use App\Models\User;
use App\Models\Video;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\Concerns\AsAction;

class VideoCommentsList
{
    use AsAction, ResponseMethodsTrait;

    public function handle(Video $video): CursorPaginator
    {
        return Comment::query()
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
            ->where('video_id', $video->id)
            ->where('parent_id', null)
            ->latest('id')
            ->cursorPaginate(10000);
    }

    public function asController(Video $video): JsonResponse
    {
        $comments = $this->handle($video);

        $comments = CommentResource::collection($comments)->resource;

        return $this->sendResponse(compact('comments'));
    }
}
