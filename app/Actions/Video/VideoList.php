<?php

namespace App\Actions\Video;

use App\Http\Resources\VideoResource;
use App\Models\Video;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class VideoList
{
    use AsAction, ResponseMethodsTrait;

    public function asController(Request $request): JsonResponse
    {
        $categoryIds = $request->query('category_ids');
        $countryId = $request->query('country_id');

        $blockedVideosIds = auth()->user()->blockedVideos->pluck('id')->toArray();

        $videos = VideoResource::collection(
            Video::query()
                ->whereNotIn('id', $blockedVideosIds)
                ->where('short', false)
                ->when(!empty($request->query('search')), fn($videos) => $videos->where('title', 'iLike', '%' . $request->query('search') . '%'))
                ->when($request->query('sort_by') == 'newest', fn($videos) => $videos->orderBy('published_at', 'desc'))
                ->when($request->query('sort_by') == 'trending', fn($videos) => $videos->orderBy('updated_at', 'desc'))
                ->when($request->query('sort_by') == 'old', fn($videos) => $videos->orderBy('published_at'))
                ->when($categoryIds, fn ($videos) => $videos->whereHas('pivotCategories', fn ($categories) => $categories->whereIn('category_id', $categoryIds)))
                ->when($countryId, fn ($videos) => $videos->where('country_id', $countryId))
                ->get()
        );
        return $this->sendResponse(compact('videos'));
    }
}
