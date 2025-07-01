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
            'name' => $this->name,
            'description' => $this->description,

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
