<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlacePhoto extends Model
{
    protected $fillable = [
        'place_id',
        'path',
        'caption_ja',
        'caption_en',
        'taken_at',
        'sort_order',
        'is_thumbnail',
    ];

    protected $casts = [
        'taken_at' => 'date',
        'is_thumbnail' => 'boolean',
    ];

    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }
}
