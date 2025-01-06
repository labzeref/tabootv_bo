<?php

namespace App\Actions\Home;

use App\Http\Resources\SeriesResource;
use App\Http\Resources\VideoResource;
use App\Models\Series;
use App\Models\Video;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class BannerList
{
    use AsAction, ResponseMethodsTrait;

    public function handle()
    {

    }

    public function jsonResponse(): JsonResponse
    {
        $blockedSeriesIds = auth()->user()->blockedSeries->pluck('id')->toArray();
        $blockedVideosIds = auth()->user()->blockedVideos->pluck('id')->toArray();

        $bannerSeries = SeriesResource::collection(Series::whereNotIn('id', $blockedSeriesIds)->with('videos')->where('banner', true)->orderBy('id','desc')->get());
        $bannerVideos = VideoResource::collection(Video::whereNotIn('id', $blockedVideosIds)->where('banner', true)->orderBy('id','desc')->get());

        $banners = [...$bannerSeries, ...$bannerVideos];

        return $this->sendResponse(compact('banners'));
    }
}
