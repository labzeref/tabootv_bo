<?php

namespace App\Http\Resources;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Video */
class VideoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $authUser = auth()->user();

        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'type' => 'video',
            'user' => new UserResource($this->whenLoaded('user')),
            'comments' => CommentResource::collection($this->whenLoaded('parentComments')),
            'channel' => new ChannelResource($this->whenLoaded('channel')),
            'user_id' => $this->user_id,
            'series_id' => $this->series_id,
            'display_order' => $this->display_order,
            'title' => $this->title,
            'description' => $this->description,
            'link' => $this->link,
            'short' => $this->short,
            'featured' => $this->featured,
            'banner' => $this->banner,
            'processing' => $this->processing,
            'duration' => $this->duration,
            'published_at' => $this->published_at,
            'published' => (bool) $this->published_at,
            'humans_publish_at' => $this->published_at?->diffForHumans(),
            'latest' => $this->published_at?->addDays(7)->isFuture(),
            'created_at' => $this->created_at?->diffForHumans(),

            'likes_count' => $this->when(isset($this->likes_count), fn() => $this->likes_count),
            'dislikes_count' => $this->when(isset($this->dislikes_count), fn() => $this->dislikes_count),
            'comments_count' => $this->when(isset($this->comments_count), fn() => $this->comments_count),

            'has_liked' => $this->whenLoaded('likes', fn() => $this->likes->contains('user_id', $authUser->id)),
            'has_disliked' => $this->whenLoaded('dislikes', fn() => $this->dislikes->contains('user_id', $authUser->id)),

            $this->mergeWhen(
                $this->relationLoaded('media'),
                fn() => [
                    'url' => $this->getFirstMedia('video')
                        ? _getMediaUrl($this->getFirstMedia('video'), '720', now()->addDay())
                        : '#no-url',

                    'url_1440' => $this->getFirstMedia('video')
                        ? _getMediaUrl($this->getFirstMedia('video'), '1440', now()->addDay())
                        : '#no-url',
                    'url_1080' => $this->getFirstMedia('video')
                        ? _getMediaUrl($this->getFirstMedia('video'), '1080', now()->addDay())
                        : '#no-url',
                    'url_720' => $this->getFirstMedia('video')
                        ? _getMediaUrl($this->getFirstMedia('video'), '720', now()->addDay())
                        : '#no-url',
                    'url_480' => $this->getFirstMedia('video')
                        ? _getMediaUrl($this->getFirstMedia('video'), '480', now()->addDay())
                        : '#no-url',

                    'thumbnail' => $this->getThumbnail(),
                    'card_thumbnail' => $this->getFirstMedia('thumbnail')
                        ? _getTemUrl($this->getFirstMediaPath('thumbnail', 'card'), now()->addDay())
                        : asset('/images/placeholder-dp.jpg'),
                    'featured_thumbnail' => $this->getFirstMedia('thumbnail')
                        ? _getTemUrl($this->getFirstMediaPath('thumbnail', 'featyred'), now()->addDay())
                        : asset('/images/placeholder-dp.jpg'),
                ]
            )
        ];
    }

    private function getThumbnail(): string
    {
        if ($this->short) {
            return $this->getFirstMedia('video')
                ? _getTemUrl($this->getFirstMediaPath('video', 'video_thumbnail'), now()->addDay())
                : asset('/images/placeholder-dp.jpg');
        } else {
            return $this->getFirstMedia('thumbnail')
                ? _getTemUrl($this->getFirstMediaPath('thumbnail'), now()->addDay())
                : asset('/images/placeholder-dp.jpg');
        }
    }
}
