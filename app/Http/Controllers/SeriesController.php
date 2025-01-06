<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\SeriesResource;
use App\Http\Resources\VideoResource;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Series;
use App\Models\Video;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    public function index()
    {
        $categories = CategoryResource::collection(Category::all());

        return inertia('Series/Index', compact('categories'));
    }

    public function trailer(Series $series)
    {
        $series = new SeriesResource($series->load(['user',
            'videos' => fn($q) => $q->orderBy('display_order'), 'media'
        ])->loadCount('videos'));
        return inertia('Player/Trailer', compact('series'));
    }

    public function play(Series $series)
    {
        return to_route('series.video', $series->videos()->first()->uuid);
    }

    public function video(Video $video)
    {
        $videos = VideoResource::collection($video->series->videos()->orderBy('display_order')->get());

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
            ])->loadCount(['likes', 'dislikes', 'comments'])
        );

        return inertia('Series/Play', compact(['videos', 'video']));
    }
}
