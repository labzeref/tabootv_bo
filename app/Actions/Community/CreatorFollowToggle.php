<?php

namespace App\Actions\Community;

use App\Http\Resources\ChannelResource;
use App\Models\Channel;
use App\Traits\ResponseMethodsTrait;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Concerns\AsAction;

class CreatorFollowToggle
{
    use AsAction, ResponseMethodsTrait;

    public function asController(Channel $channel): JsonResponse
    {
        $user = auth()->user();
        $isFollowing = $user->followedChannels()->where('channel_id', $channel->id)->exists();
        if ($isFollowing){
            $user->followedChannels()->detach($channel->id);

        }else{
            $user->followedChannels()->attach($channel->id);
        }
        $channel->loadCount([
            'series',
            'videos as short_videos_count' => fn($query) => $query->where('short',true),
            'videos' => fn($query) => $query->where('short',false),
            'followers as following' => fn($query) => $query->where('user_id',$user->id),
        ])
            ->load([
                'user',
            ]);


        $creator = new ChannelResource($channel);
        return $this->sendResponse(['creator' => $creator]);
    }
}
