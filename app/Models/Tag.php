<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $fillable = [
        'name_ja',
        'name_en',
        'slug',
    ];

    public function places(): BelongsToMany
    {
        return $this->belongsToMany(Place::class, 'place_tag');
    }
}
