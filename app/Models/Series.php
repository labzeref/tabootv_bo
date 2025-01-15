<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Series extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $with = ['media', 'channel'];

    protected $guarded = ['id'];

    public static function boot(): void
    {
        parent::boot();

        static::addGlobalScope('allowed', function (Builder $builder) {
            if (request()->is('api/*')) {
                $builder->where('is_app_blocked', false)->whereNotNull('published_at');
            } else {
                $builder->where('is_blocked', false)->whereNotNull('published_at');
            }
        });

        self::creating(function (Series $series) {
            $series->uuid = \Illuminate\Support\Str::uuid();
        });
    }

    protected function casts(): array
    {
        return [
            'banner' => 'boolean',
            'published_at' => 'datetime',
            'is_blocked' => 'boolean',
        ];
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaCollection('thumbnail')->singleFile();
        $this->addMediaCollection('trailer_thumbnail')->singleFile();
        $this->addMediaCollection('desktop_banner')->singleFile();
        $this->addMediaCollection('mobile_banner')->singleFile();

        $this->addMediaCollection('trailer')->singleFile();
    }

    public function registerAllMediaConversions(): void
    {
        $this->addMediaConversion('card')
            ->performOnCollections('thumbnail')
            ->width(388);
    }

    /**
     * --------------------------------------------------------------------------------
     * Relations
     * --------------------------------------------------------------------------------
     */


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

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

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class)->orderBy('display_order');
    }

    public function pivotCategories(): HasMany
    {
        return $this->hasMany(SeriesCategory::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'series_categories');
    }
}
