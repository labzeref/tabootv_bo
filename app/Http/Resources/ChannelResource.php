<?php

namespace App\Http\Resources;

use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Channel */
class ChannelResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'user_id' => $this->user_id,
            'description' => $this->description,
            'following' => when(isset($this->following), fn() => $this->following?true:false),
            'user' => new UserResource($this->whenLoaded('user')),
            "series_count" => $this->when(isset($this->series_count), fn() => $this->series_count),
            "posts_count" => $this->when(isset($this->posts_count), fn() => $this->posts_count),
            "short_videos_count" => $this->when(isset($this->short_videos_count), fn() => $this->short_videos_count),
            "videos_count" => $this->when(isset($this->videos_count), fn() => $this->videos_count),
             $this->mergeWhen(
                $this->relationLoaded('media'),
                fn() => [
                    'dp' => $this->getFirstMedia('dp')
                        ? _getTemUrl($this->getFirstMediaPath('dp'), now()->addDay())
                        : asset('/images/placeholder-dp.jpg'),
                    'banner' => $this->getFirstMedia('banner')
                        ? _getTemUrl($this->getFirstMediaPath('banner'), now()->addDay())
                        : asset('/images/placeholder-dp.jpg'),
                ]
            )
        ];
    }
}
