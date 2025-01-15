<?php

namespace App\Actions\User;

use App\Http\Resources\PostResource;
use App\Http\Resources\VideoResource;
use App\Models\Channel;
use App\Models\Post;
use App\Models\User;
use App\Models\Video;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class UserPostsList
{
    use AsAction, ResponseMethodsTrait;
    public function asController(Request $request,Channel $channel): JsonResponse
    {

        $posts = Post::withCount(['postComments', 'likes', 'dislikes'])
            ->when($request->query('sort_by') == 'newest', fn($series) => $series->orderBy('updated_at', 'desc'))
            ->when($request->query('sort_by') == 'trending', fn($series) => $series->orderBy('updated_at', 'desc'))
            ->when($request->query('sort_by') == 'old', fn($series) => $series->orderBy('updated_at'))
            ->with([
                'user',
                'likes' => fn($likes) => $likes->where('user_id', auth()->id()),
                'dislikes' => fn($likes) => $likes->where('user_id', auth()->id()),
                'recent_like' => fn($likes) => $likes->orderByDesc('created_at')->take(1),
            ])
            ->where('user_id', $channel->user_id)
            ->latest('created_at')
            ->paginate(10);

        $posts = PostResource::collection($posts)->resource;

        return $this->sendResponse(compact(['posts']));
    }
}
