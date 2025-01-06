<?php

namespace App\Actions\Home;

use App\Http\Resources\VideoResource;
use App\Models\Video;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class FeaturedVideoList
{
    use AsAction, ResponseMethodsTrait;

    public function handle()
    {

    }

    public function jsonResponse(): JsonResponse
    {
        $blockedVideosIds = auth()->user()->blockedVideos->pluck('id')->toArray();
        $videos = VideoResource::collection(Video::whereNotIn('id', $blockedVideosIds)->with('user')->where('featured', true)->orderBy('id','desc')->get());

        return $this->sendResponse(compact('videos'));
    }
}
