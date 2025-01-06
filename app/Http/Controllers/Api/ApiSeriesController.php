<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SeriesResource;
use App\Http\Resources\VideoResource;
use App\Models\Series;
use App\Models\Video;
use Illuminate\Http\Request;

class ApiSeriesController extends Controller
{
    public function trailer(Series $series)
    {
        $series = new SeriesResource($series->load([
            'videos' => fn($videos) => $videos->orderBy('display_order'),
            'channel'
        ]));

        return $this->sendResponse(compact('series'));
    }

    public function play(Video $video)
    {
        if (!$video->series_id) {
            return $this->sendError('Video is not part of any series', 422);
        }

        $authUser = auth()->user();

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

        return $this->sendResponse(compact(['video']));
    }
}
