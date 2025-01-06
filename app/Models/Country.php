<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $guarded = ['id'];

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

}
