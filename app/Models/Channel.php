<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Channel extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $with = ['media'];

    public static function boot(): void
    {
        parent::boot();

        self::creating(function (Channel $channel) {
            $channel->uuid =\Illuminate\Support\Str::uuid();
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('dp')->singleFile();
        $this->addMediaCollection('banner')->singleFile();
    }

    /**
     * -----------------------------------------------------------------
     * Relations
     * -----------------------------------------------------------------
     */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function series(): HasManyThrough
    {
        return $this->hasManyThrough(Series::class, User::class);
    }
}
