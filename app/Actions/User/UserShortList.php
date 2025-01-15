<?php

namespace App\Actions\User;

use App\Http\Resources\VideoResource;
use App\Models\Channel;
use App\Models\User;
use App\Models\Video;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class UserShortList
{
    use AsAction, ResponseMethodsTrait;
    public function asController(Request $request,Channel $channel): JsonResponse
    {

        $videos = VideoResource::collection(
            Video::query()
                ->when($request->query('sort_by') == 'newest', fn($series) => $series->orderBy('published_at', 'desc'))
                ->when($request->query('sort_by') == 'trending', fn($series) => $series->orderBy('updated_at', 'desc'))
                ->when($request->query('sort_by') == 'old', fn($series) => $series->orderBy('published_at'))
                ->where('short', true)
                ->where('user_id', $channel->user_id)
                ->paginate(20)
        )->resource;
        return $this->sendResponse(compact('videos'));
    }
}
