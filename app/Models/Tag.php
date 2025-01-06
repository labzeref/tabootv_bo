<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    public function videos(): BelongsToMany
    {
        return $this->belongsToMany(Video::class, 'video_tags', 'tag_id', 'video_id');
    }
}
