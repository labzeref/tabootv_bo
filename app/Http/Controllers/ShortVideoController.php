<?php

namespace App\Http\Controllers;

use App\Http\Resources\VideoResource;
use App\Models\Video;
use Illuminate\Http\Request;

class ShortVideoController extends Controller
{
   public function __invoke($uuid = null)
    {
        $video = null;

        if ($uuid) {
            $video = Video::query()
            ->where('uuid', $uuid)
            ->where('short', true)
            ->with([
                    'channel',
                    'parentComments' => fn($parentComments) => $parentComments->with([
                        'replies.user',
                        'user',
                        'likes' => fn($likes) => $likes->where('user_id', auth()->id()),
                        'dislikes' => fn($likes) => $likes->where('user_id', auth()->id()),
                    ])->latest('id'),
                    'likes' => fn($likes) => $likes->where('user_id', auth()->id()),
                    'dislikes' => fn($likes) => $likes->where('user_id', auth()->id()),
                ])
            ->withCount(['comments'])
            ->first();
        }

        if ($video) {
            $video = new VideoResource($video);
        }

        return inertia('Shorts/Index', compact('video'));
    }
}
