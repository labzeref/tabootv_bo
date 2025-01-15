<?php

namespace App\Models;

use App\SpatieMedia\InteractsWithHashedMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;

class TempMedia extends Model implements HasMedia
{
    use InteractsWithHashedMedia;

    protected $guarded = ['id'];

    public static function boot(): void
    {
        parent::boot();

        self::creating(function (TempMedia $tempMedia) {
            $tempMedia->uuid = Str::uuid();
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('file')->singleFile()->useDisk('local');
    }

    /**
     * Relations
     */
    public function chunks(): HasMany
    {
        return $this->hasMany(TempMediaChunk::class);
    }
}
