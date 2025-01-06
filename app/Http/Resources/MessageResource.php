<?php

namespace App\Http\Resources;

use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Message */
class MessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Try to get the user's timezone from the request or session (you can also use a default like 'UTC')
        $userTimezone = $request->has('timezone') ? $request->get('timezone') : 'UTC';  // Default to 'UTC'

        // Parse the created_at timestamp and set the correct timezone
        $createdAt = $this->created_at->setTimezone($userTimezone);
        if($createdAt->isToday()) {
            $formattedCreatedAt = $createdAt->format('h:i A');
        }else{
            $formattedCreatedAt = $createdAt->format('d/m/Y');
        }

        // Format the time to 'h:i A' (e.g., '7:12 AM')
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'content' => $this->content,
            'time' => $formattedCreatedAt,
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
