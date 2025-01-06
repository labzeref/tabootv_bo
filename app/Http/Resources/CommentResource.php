<?php

namespace App\Http\Resources;

use App\Models\Comment;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Comment */
class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $authUser = auth()->user();

        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'replies' => CommentResource::collection($this->whenLoaded('replies')),
            'user' => new UserResource($this->whenLoaded('user')),
            'parent_id' => $this->parent_id,
            'user_id' => $this->user_id,
            'video_id' => $this->video_id,
            'content' => $this->content,

            'has_liked' => $this->whenLoaded('likes', fn() => $this->likes->contains('user_id', $authUser->id)),
            'has_disliked' => $this->whenLoaded('dislikes', fn() => $this->dislikes->contains('user_id', $authUser->id)),

            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
