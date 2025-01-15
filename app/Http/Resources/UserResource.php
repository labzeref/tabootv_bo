<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'country_id' => $this->country_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'display_name' => $this->display_name ?: $this->first_name,
            'email' => $this->email,
            'gender' => $this->gender,
            'phone_number' => $this->phone_number,
            'profile_completed' => $this->profile_completed,
            'video_autoplay' => $this->video_autoplay,
            'badge' => $this->getBadge(),
            'channel' => new ChannelResource($this->whenLoaded('channel')),

            $this->mergeWhen(
                $this->relationLoaded('media'),
                fn() => [
                    'dp' => $this->getFirstMedia('dp')
                        ? _getTemUrl($this->getFirstMediaPath('dp'), now()->addDay())
                        : asset('/images/placeholder-dp.jpg'),
                    'medium_dp' => $this->getFirstMedia('dp')
                        ? _getTemUrl($this->getFirstMediaPath('dp', 'medium'), now()->addDay())
                        : asset('/images/placeholder-dp.jpg'),
                    'small_dp' => $this->getFirstMedia('dp')
                        ? _getTemUrl($this->getFirstMediaPath('dp', 'small'), now()->addDay())
                        : asset('/images/placeholder-dp.jpg'),
                ]
            )
        ];
    }
}
