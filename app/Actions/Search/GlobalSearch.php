<?php

namespace App\Actions\Search;

use App\Http\Resources\SeriesResource;
use App\Http\Resources\VideoResource;
use App\Models\Series;
use App\Models\Video;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class GlobalSearch
{
    use AsAction, ResponseMethodsTrait;

    public function asController(Request $request): JsonResponse
    {
        $categoryIds = $request->query('category_ids');
        $countryId = $request->query('country_id');

        $blockedSeriesIds = auth()->user()->blockedSeries->pluck('id')->toArray();
        $blockedVideosIds = auth()->user()->blockedVideos->pluck('id')->toArray();

        $videos = VideoResource::collection(
            Video::query()
                ->whereNotIn('id', $blockedSeriesIds)
                ->when(!empty($request->query('search')), fn($videos) => $videos->where('title', 'iLike', '%' . $request->query('search') . '%'))
                ->when($request->query('sort_by') == 'newest', fn($videos) => $videos->orderBy('published_at', 'desc'))
                ->when($request->query('sort_by') == 'trending', fn($videos) => $videos->orderBy('updated_at', 'desc'))
                ->when($request->query('sort_by') == 'old', fn($videos) => $videos->orderBy('published_at'))
                ->when($categoryIds, fn($videos) => $videos->whereHas('pivotCategories', fn($categories) => $categories->whereIn('category_id', $categoryIds)))
                ->when($countryId, fn($videos) => $videos->where('country_id', $countryId))
                ->where('short', false)
                ->limit(30)
                ->get()
        );

        $series = SeriesResource::collection(
            Series::query()
                ->whereNotIn('id', $blockedVideosIds)
                ->withCount('videos')
                ->when(!empty($request->query('search')), fn($series) => $series->where('title', 'iLike', '%' . $request->query('search') . '%'))
                ->when($request->query('sort_by') == 'newest', fn($series) => $series->orderBy('created_at'))
                ->when($request->query('sort_by') == 'trending', fn($series) => $series->orderBy('updated_at'))
                ->when($request->query('sort_by') == 'old', fn($series) => $series->orderBy('created_at', 'desc'))
                ->when($categoryIds, fn($series) => $series->whereHas('pivotCategories', fn($categories) => $categories->whereIn('category_id', $categoryIds)))
                ->limit(30)
                ->get()
        );

        return $this->sendResponse(compact('videos', 'series'));
    }
}
