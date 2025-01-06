<?php

namespace App\Actions\Series;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\SeriesResource;
use App\Models\Channel;
use App\Models\Series;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class SeriesList
{
    use AsAction, ResponseMethodsTrait;

    public function asController(Request $request): JsonResponse
    {
        $categoryIds = $request->query('category_ids');

        $blockedSeriesIds = auth()->user()->blockedSeries->pluck('id')->toArray();

        $series = SeriesResource::collection(
            Series::query()
                ->whereNotIn('id', $blockedSeriesIds)
                ->with(['channel'])
                ->withCount('videos')
                ->when($request->query('sort_by') == 'newest', fn($series) => $series->orderBy('published_at', 'desc'))
                ->when($request->query('sort_by') == 'trending', fn($series) => $series->orderBy('updated_at', 'desc'))
                ->when($request->query('sort_by') == 'old', fn($series) => $series->orderBy('published_at'))
                ->when($categoryIds, fn ($series) => $series->whereHas('pivotCategories', fn ($categories) => $categories->whereIn('category_id', $categoryIds)))
                ->limit(30)
                ->get()
        );

        return $this->sendResponse(compact(['series']));
    }
}
