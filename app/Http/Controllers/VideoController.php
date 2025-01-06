<?php

namespace App\Http\Controllers;

use App\Http\Resources\VideoResource;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function play(Video $video)
    {
        $video = new VideoResource($video
            ->load([
                'channel',
                'parentComments' => fn($parentComments) => $parentComments->with([
                    'replies' => fn($replies) => $replies->with([
                        'user',
                        'likes' => fn($likes) => $likes->where('user_id', auth()->id()),
                        'dislikes' => fn($likes) => $likes->where('user_id', auth()->id()),
                    ]),
                    'user',
                    'likes' => fn($likes) => $likes->where('user_id', auth()->id()),
                    'dislikes' => fn($likes) => $likes->where('user_id', auth()->id()),
                ])->latest('id'),
                'likes' => fn($likes) => $likes->where('user_id', auth()->id()),
                'dislikes' => fn($likes) => $likes->where('user_id', auth()->id()),
            ])->loadCount(['likes', 'dislikes', 'comments'])
        );

        $videos = VideoResource::collection(Video::where('short', false)->inRandomOrder()->limit(10)->get());

        return inertia('Videos/Play', compact(['video', 'videos']));
    }
}
