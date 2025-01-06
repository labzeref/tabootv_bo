<?php

namespace App\Http\Resources;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Plan */
class PlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'apple_id' => $this->apple_id,
            'name' => $this->name,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price/100,
            'save_percentage' => $this->save_percentage,
            'features' => $this->features,
            'trial_days' => $this->trial_days,
            'checkout_url' => $this->checkout_url,
        ];
    }
}
