<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Place extends Model
{
    protected $fillable = [
        'type',
        'category_id',
        'prefecture_id',
        'slug',
        'name_ja',
        'name_en',
        'kana',
        'short_desc_ja',
        'short_desc_en',
        'description_ja',
        'description_en',
        'address_ja',
        'address_en',
        'lat',
        'lng',
        'built_year',
        'builder_ja',
        'builder_en',
        'abolished_year',
        'main_lords_ja',
        'main_lords_en',
        'renovator_ja',
        'renovator_en',
        'castle_style_ja',
        'castle_style_en',
        'tenshu_style_ja',
        'tenshu_style_en',
        'heritage_designation_ja',
        'heritage_designation_en',
        'remains_ja',
        'remains_en',
        'rating',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
        'rating' => 'integer',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    // --------------------
    // Relations
    // --------------------
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function prefecture(): BelongsTo
    {
        return $this->belongsTo(Prefecture::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(PlacePhoto::class)->orderBy('sort_order');
    }

    /**
     * サムネ（place_photos.is_thumbnail = 1）を1枚だけ取る
     * ※ migrationで unique(place_id, is_thumbnail) を張っている想定
     */
    public function thumbnailPhoto(): HasOne
    {
        return $this->hasOne(PlacePhoto::class)->where('is_thumbnail', true);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'place_tag');
    }

    // --------------------
    // Scopes (管理/公開で便利)
    // --------------------
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeCategorySlug(Builder $query, string $slug): Builder
    {
        return $query->whereHas('category', fn ($q) => $q->where('slug', $slug));
    }

    public function scopePrefectureSlug(Builder $query, string $slug): Builder
    {
        return $query->whereHas('prefecture', fn ($q) => $q->where('slug', $slug));
    }

    public function scopeTagSlug(Builder $query, string $slug): Builder
    {
        return $query->whereHas('tags', fn ($q) => $q->where('slug', $slug));
    }

    /**
     * 管理画面の一覧検索（MVP用：LIKE検索）
     */
    public function scopeKeyword(Builder $query, ?string $keyword): Builder
    {
        $keyword = trim((string) $keyword);
        if ($keyword === '') {
            return $query;
        }

        return $query->where(function ($q) use ($keyword) {
            $q->where('name_ja', 'like', "%{$keyword}%")
              ->orWhere('name_en', 'like', "%{$keyword}%")
              ->orWhere('slug', 'like', "%{$keyword}%")
              ->orWhere('kana', 'like', "%{$keyword}%")
              ->orWhere('short_desc_ja', 'like', "%{$keyword}%")
              ->orWhere('short_desc_en', 'like', "%{$keyword}%");
        });
    }
}
