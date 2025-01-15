<?php

namespace App\Actions\Community;

use App\Http\Resources\ChannelResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\SeriesResource;
use App\Models\Channel;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\Series;
use App\Models\User;
use App\Models\Video;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class CreatorList
{
    use AsAction, ResponseMethodsTrait;

    public function asController(): JsonResponse
    {

        $user = auth()->user();
        $channels = Channel::with('user')->
            withCount([
            'followers as following' => fn($query) => $query->where('user_id',$user->id),
        ])->
        orderBy('id')->
        paginate(50);

        $creators = ChannelResource::collection($channels)->resource;

        return $this->sendResponse(['creators' => $creators]);
    }
}
