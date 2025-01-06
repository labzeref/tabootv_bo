<?php

namespace App\Actions\ShortVideos;

use App\Http\Resources\VideoResource;
use App\Models\Video;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class ShortVideosList
{
    use AsAction, ResponseMethodsTrait;

    public function handle(): void
    {
    }

    public function asController($uuid = null): JsonResponse
    {
        $blockedVideosIds = auth()->user()->blockedVideos->pluck('id')->toArray();
        $videos = Video::query()
            ->whereNotIn('id', $blockedVideosIds)
            ->where('short', true)
            ->with([
                'channel',
                'parentComments' => fn($parentComments) => $parentComments->with([
                    'replies.user',
                    'user',
                    'likes' => fn($likes) => $likes->where('user_id', auth()->id()),
                    'dislikes' => fn($likes) => $likes->where('user_id', auth()->id()),
                ])->latest('id'),
                'likes' => fn($likes) => $likes->where('user_id', auth()->id()),
                'dislikes' => fn($likes) => $likes->where('user_id', auth()->id()),
            ])
            ->withCount(['comments'])
            ->inRandomOrder()
            ->paginate(5);

        // Prepend the video only if it's the first page
        if ($videos->currentPage() === 1 && $uuid) {
            $video = Video::where('short', true)->where('uuid', $uuid)->first();

            if ($video) {
                // Convert the paginated items to a collection
                $collection = $videos->getCollection();

                // Prepend the provided video to the collection
                $collection->prepend($video);

                // Replace the collection in the paginated result
                $videos->setCollection($collection);
            }
        }

        // Transform the collection into a resource
        $videos = VideoResource::collection($videos)->resource;

        return $this->sendResponse(compact('videos'));
    }
}
