<?php

namespace App\Http\Resources;

use App\Models\Series;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Series */
class SeriesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'type' => 'series',
            'channel' => new ChannelResource($this->whenLoaded('channel')),
            'user_id' => $this->user_id,
            'title' => $this->title,
            'description' => $this->description,
            'duration' => $this->duration,
            'banner' => $this->banner,
            'published_at' => $this->created_at,
            'humans_publish_at' => $this->published_at?->diffForHumans(),
            'latest' => $this->published_at?->addDays(7)->isFuture(),
            'videos' => VideoResource::collection($this->whenLoaded('videos')),
            'user' => new UserResource($this->whenLoaded('user')),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'videos_count' => $this->when(isset($this->videos_count), fn() => $this->videos_count),
            $this->mergeWhen(
                $this->relationLoaded('media'),
                fn() => [
                    'thumbnail' => $this->getFirstMedia('trailer_thumbnail')
                        ? _getTemUrl($this->getFirstMediaPath('trailer_thumbnail'), now()->addDay())
                        : asset('/images/placeholder-dp.jpg'),
                    'card_thumbnail' => $this->getFirstMedia('trailer_thumbnail')
                        ? _getTemUrl($this->getFirstMediaPath('trailer_thumbnail', 'trailer_thumbnail'), now()->addDay())
                        : asset('/images/placeholder-dp.jpg'),
                    'trailer_thumbnail' => $this->getFirstMedia('trailer_thumbnail')
                        ? _getTemUrl($this->getFirstMediaPath('trailer_thumbnail'), now()->addDay())
                        : asset('/images/placeholder-dp.jpg'),
                    'desktop_banner' => $this->getFirstMedia('desktop_banner')
                        ? _getTemUrl($this->getFirstMediaPath('desktop_banner'), now()->addDay())
                        : asset('/images/placeholder-dp.jpg'),
                    'mobile_banner' => $this->getFirstMedia('mobile_banner')
                        ? _getTemUrl($this->getFirstMediaPath('mobile_banner'), now()->addDay())
                        : asset('/images/placeholder-dp.jpg'),
                    'trailer_url' => _getTemUrl($this->getFirstMediaPath('trailer')),
                ]
            )
        ];
    }

}
