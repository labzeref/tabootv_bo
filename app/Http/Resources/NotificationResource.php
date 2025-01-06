<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Notifications\DatabaseNotification;

/** @mixin DatabaseNotification */
class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->data['type'],
            'image' => $this->data['image_path'],
            'model_uuid' => $this->data['model_uuid'] ?? '',
            'model_type' => $this->data['model_type'] ?? '',
            'route' => $this->data['route']??'',
            'message' => $this->data['message'],
            'created_at' => $this->created_at?->diffForHumans(),
            'has_read' => $this->read_at !== null,
        ];
    }
}
