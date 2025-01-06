<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SeriesCategory extends Model
{
    protected $guarded = ['id'];

    public function series(): HasMany
    {
        return $this->hasMany(Series::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }
}
