<?php

namespace App\Models;

use App\Enums\SubscriptionStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $guarded = ['id'];

    public static function boot(): void
    {
        parent::boot();

        self::creating(function (Subscription $subscription) {
            $subscription->uuid =\Illuminate\Support\Str::uuid();
        });
    }

    protected function casts(): array
    {
        return [
            'status' => SubscriptionStatusEnum::class,
            'start_at' => 'datetime',
            'next_bill_at' => 'datetime',
            'end_at' => 'datetime',
            'payload' => 'json',
        ];
    }

    public function scopeActive(Builder $query): void
    {
        $query->whereNull('end_at')->orWhereDate('end_at', '>', now());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}
