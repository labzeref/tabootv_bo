<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostCommentResource extends JsonResource
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
            'uuid' => $this->uuid,
            'replies' => PostCommentResource::collection($this->whenLoaded('replies')),
            'user' => new UserResource($this->whenLoaded('user')),
            'parent_id' => $this->parent_id,
            'user_id' => $this->user_id,
            'post_id' => $this->post_id,
            'content' => $this->content,
            'replies_count' => $this->when(isset($this->replies_count), fn() => $this->replies_count),

            'has_liked' => $this->whenLoaded('likes', fn() => $this->likes->contains('user_id', $authUser->id)),
            'has_disliked' => $this->whenLoaded('dislikes', fn() => $this->dislikes->contains('user_id', $authUser->id)),

            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
