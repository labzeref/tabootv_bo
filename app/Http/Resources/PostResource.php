<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $authUser = auth()->user();

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'caption' => $this->caption,
            'created_at' => $this->created_at?->diffForHumans(),

            'likes_count' => $this->when(isset($this->likes_count), fn() => $this->likes_count),
            'dislikes_count' => $this->when(isset($this->dislikes_count), fn() => $this->dislikes_count),
            'comments_count' => $this->when(isset($this->post_comments_count), fn() => $this->post_comments_count),

            'has_liked' => $this->whenLoaded('likes', fn() => $this->likes->contains('user_id', $authUser->id)),
            'recent_like' => $this->whenLoaded('recent_like', fn() => $this->recent_like->first()?->user?->display_name),
            'has_disliked' => $this->whenLoaded('dislikes', fn() => $this->dislikes->contains('user_id', $authUser->id)),

            'comments' => CommentResource::collection($this->whenLoaded('parentComments')),
            'user' => new UserResource($this->whenLoaded('user')),

            $this->mergeWhen(
                $this->relationLoaded('media'),
                fn() => [
                    'post_image' => $this->getFirstMedia('post-image')
                        ? _getTemUrl($this->getFirstMediaPath('post-image'), now()->addDay())
                        : false
                ]
            ),
        ];
    }
}
