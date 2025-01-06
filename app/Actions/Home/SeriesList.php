<?php

namespace App\Actions\Home;

use App\Http\Resources\SeriesResource;
use App\Models\Series;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Concerns\AsAction;

class SeriesList
{
    use AsAction, ResponseMethodsTrait;

    public function handle()
    {

    }

    public function jsonResponse(): JsonResponse
    {
        $blockedSeriesIds = auth()->user()->blockedSeries->pluck('id')->toArray();
        $series = SeriesResource::collection(
            Series::query()
                ->whereNotIn('id', $blockedSeriesIds)
                ->with([
                    'user',
                    'media',
                    'videos' => fn ($videos) => $videos->orderBy('display_order', 'asc')
                ])
                ->withCount(['videos'])
                ->orderBy('published_at', 'desc')
                ->get());


        return $this->sendResponse(compact('series'));
    }
}
