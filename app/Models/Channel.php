<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    public function series()
    {
        return $this->hasManyThrough(
            Series::class, // Final model (Series)
            User::class,   // Intermediate model (User)
            'id',          // Foreign key on the intermediate model (User -> id)
            'user_id',     // Foreign key on the final model (Series -> user_id)
            'user_id',     // Local key on the parent model (Channel -> user_id)
            'id'           // Local key on the intermediate model (User -> id)
        );
    }

    public function videos()
    {
        return $this->hasManyThrough(
            Video::class, // Final model (Series)
            User::class,   // Intermediate model (User)
            'id',          // Foreign key on the intermediate model (User -> id)
            'user_id',     // Foreign key on the final model (Series -> user_id)
            'user_id',     // Local key on the parent model (Channel -> user_id)
            'id'           // Local key on the intermediate model (User -> id)
        );
    }

    public function posts()
    {
        return $this->hasManyThrough(
            Post::class, // Final model (Series)
            User::class,   // Intermediate model (User)
            'id',          // Foreign key on the intermediate model (User -> id)
            'user_id',     // Foreign key on the final model (Series -> user_id)
            'user_id',     // Local key on the parent model (Channel -> user_id)
            'id'           // Local key on the intermediate model (User -> id)
        );
    }
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'channel_user')->withTimestamps();
    }

}
