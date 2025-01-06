<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Category extends Model
{
    protected $guarded = ['id'];

    public function videos()
    {
        return $this->morphedByMany(Video::class, 'model', 'category_media', 'category_id', 'model_id');
    }

    public function series()
    {
        return $this->morphedByMany(Series::class, 'model', 'category_media', 'category_id', 'model_id');
    }


}
