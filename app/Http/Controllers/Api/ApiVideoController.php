<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SeriesResource;
use App\Http\Resources\VideoResource;
use App\Models\Video;

class ApiVideoController extends Controller
{
    public function play(Video $video)
    {
        $video = new VideoResource(
            $video->load([
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
            ])
                ->loadCount(['likes', 'dislikes', 'comments'])
        );

        $videos = VideoResource::collection(Video::with('channel')->where('short', false)->inRandomOrder()->get());

        return $this->sendResponse(compact(['video', 'videos']));
    }
}
