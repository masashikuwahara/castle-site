<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'type',       // castle / cultural_property / both
        'name_ja',
        'name_en',
        'slug',
        'sort_order',
    ];

    public function places(): HasMany
    {
        return $this->hasMany(Place::class);
    }
}
