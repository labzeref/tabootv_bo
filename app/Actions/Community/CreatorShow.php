<?php

namespace App\Actions\Community;

use App\Http\Resources\ChannelResource;
use App\Models\Channel;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Concerns\AsAction;

class CreatorShow
{
    use AsAction, ResponseMethodsTrait;

    public function asController(Channel $channel): JsonResponse
    {
        $user = auth()->user();
        $channel =$channel->
        loadCount(['series',
            'videos as short_videos_count' => fn($query) => $query->where('short',true),
            'videos' => fn($query) => $query->where('short',false),
            'followers as following' => fn($query) => $query->where('user_id',$user->id),


            'posts'])
            ->load(['user']);
        $creator = new ChannelResource($channel);
        return $this->sendResponse(['creator' => $creator]);
    }
}
