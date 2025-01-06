<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $guarded = ['id'];

    public static function boot(): void
    {
        parent::boot();

        self::creating(function (Plan $plan) {
            $plan->uuid =\Illuminate\Support\Str::uuid();
        });
    }

    protected function casts(): array
    {
        return [
            'features' => 'json',
            'price' => 'decimal:2',
            'trial_days' => 'integer',
        ];
    }
}
