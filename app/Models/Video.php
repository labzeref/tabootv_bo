<?php

namespace App\Models;

use App\Services\CustomConversionHandler;
use FFMpeg\Format\Video\X264;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Image\Enums\Fit;

class Video extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $with = ['media', 'channel'];

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'short' => 'boolean',
            'featured' => 'boolean',
            'banner' => 'boolean',
            'published_at' => 'datetime',
            'is_blocked' => 'boolean',
        ];
    }

    public static function boot(): void
    {
        parent::boot();

        static::addGlobalScope('allowed', function (Builder $builder) {
            if (request()->is('api/*')) {
                $builder->where('is_app_blocked', false)->whereNotNull('published_at')
                    ->whereHas('media', function ($query) {
                        $query->where('collection_name', 'video');
                    });
            } else {
                $builder->where('is_blocked', false)->whereNotNull('published_at')
                    ->whereHas('media', function ($query) {
                        $query->where('collection_name', 'video');
                    });
            }
        });

        self::created(function (Video $video) {
            $series = $video->series;

            if ($series) {
                $video->update([
                    'display_order' => $series->videos->count(),
                ]);

                $series->update([
                    'duration' => $series->videos->sum('duration'),
                ]);
            }
        });

        self::updated(function (Video $video) {
            $series = $video->series;
            $series?->update([
                'duration' => $series->videos->sum('duration'),
            ]);
        });

        self::creating(function (Video $video) {
            $video->uuid = \Illuminate\Support\Str::uuid();
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('thumbnail')->singleFile();
        $this->addMediaCollection('video')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('card')
            ->format('png')
            ->performOnCollections('thumbnail')
            ->nonQueued()
            ->width(388);

        $this->addMediaConversion('featured')
            ->format('png')
            ->performOnCollections('thumbnail')
            ->nonQueued()
            ->width(170);

        $this->addMediaConversion('480')
            ->format('mp4')
            ->nonQueued()
            ->performOnCollections('video');

        $this->addMediaConversion('720')
            ->format('mp4')
            ->nonQueued()
            ->performOnCollections('video');

        $this->addMediaConversion('1080')
            ->format('mp4')
            ->nonQueued()
            ->performOnCollections('video');

        $this->addMediaConversion('1440')
            ->format('mp4')
            ->nonQueued()
            ->performOnCollections('video');

        $this->addMediaConversion('video_thumbnail')
            ->width(1080)
            ->height(1920)
            ->extractVideoFrameAtSecond(2)
            ->performOnCollections('video');
    }


    /**
     * --------------------------------------------------------------------
     * Relations
     * --------------------------------------------------------------------
     */

    public function channel(): HasOneThrough
    {
        return $this->hasOneThrough(
            Channel::class,
            User::class,
            'id',         // Foreign key on users table
            'user_id',    // Foreign key on channels table
            'user_id',    // Local key on videos table
            'id'          // Local key on users table
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'video_tags');
    }

    public function pivotCategories(): HasMany
    {
        return $this->hasMany(VideoCategory::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'video_categories');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function parentComments(): HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(VideoLike::class)->where('value', true);
    }

    public function dislikes(): HasMany
    {
        return $this->hasMany(VideoLike::class)->where('value', false);
    }

    public function likedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'video_likes', 'video_id', 'user_id');
    }
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
