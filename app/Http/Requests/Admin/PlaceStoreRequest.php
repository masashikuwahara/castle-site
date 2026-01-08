<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlaceStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Breezeのauthで保護している前提
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(['castle', 'cultural_property'])],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'prefecture_id' => ['required', 'integer', 'exists:prefectures,id'],

            'slug' => ['required', 'string', 'max:120', 'alpha_dash', 'unique:places,slug'],
            'name_ja' => ['required', 'string', 'max:120'],
            'name_en' => ['nullable', 'string', 'max:160'],
            'kana' => ['nullable', 'string', 'max:160'],

            'short_desc_ja' => ['nullable', 'string', 'max:200'],
            'short_desc_en' => ['nullable', 'string', 'max:240'],
            'description_ja' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],

            'address_ja' => ['nullable', 'string', 'max:255'],
            'address_en' => ['nullable', 'string', 'max:255'],

            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],

            'built_year' => ['nullable', 'string', 'max:20'],
            'builder_ja' => ['nullable', 'string', 'max:120'],
            'builder_en' => ['nullable', 'string', 'max:160'],
            'abolished_year' => ['nullable', 'string', 'max:20'],

            'main_lords_ja' => ['nullable', 'string'],
            'main_lords_en' => ['nullable', 'string'],

            'renovator_ja' => ['nullable', 'string', 'max:120'],
            'renovator_en' => ['nullable', 'string', 'max:160'],

            'castle_style_ja' => ['nullable', 'string', 'max:80'],
            'castle_style_en' => ['nullable', 'string', 'max:120'],
            'tenshu_style_ja' => ['nullable', 'string', 'max:80'],
            'tenshu_style_en' => ['nullable', 'string', 'max:120'],

            'heritage_designation_ja' => ['nullable', 'string', 'max:120'],
            'heritage_designation_en' => ['nullable', 'string', 'max:160'],

            'remains_ja' => ['nullable', 'string'],
            'remains_en' => ['nullable', 'string'],

            'rating' => ['nullable', 'integer', 'between:1,5'],

            'is_published' => ['sometimes', 'boolean'],
            'published_at' => ['nullable', 'date'],

            // タグ（複数）
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['integer', 'exists:tags,id'],

            // サムネ1枚
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], // 5MB
            'thumbnail_caption_ja' => ['nullable', 'string', 'max:200'],
            'thumbnail_caption_en' => ['nullable', 'string', 'max:240'],
            'thumbnail_taken_at' => ['nullable', 'date'],
        ];
    }

    public function prepareForValidation(): void
    {
        // checkbox未送信対策：編集時も扱いやすいよう boolean 化
        $this->merge([
            'is_published' => (bool) $this->input('is_published', false),
        ]);
    }
}
