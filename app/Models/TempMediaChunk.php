<?php

namespace App\Models;

use App\SpatieMedia\InteractsWithHashedMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

class TempMediaChunk extends Model implements HasMedia
{
    use InteractsWithHashedMedia;

    protected $guarded = ['id'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('file')->singleFile()->useDisk('local');
    }
}
