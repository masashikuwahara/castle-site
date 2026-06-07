<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\Prefecture;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicSearchSuggestController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $q = trim((string) $request->query('q', ''));

        if ($q === '') {
            return response()->json([]);
        }

        $places = Place::query()
            ->where('is_published', 1)
            ->where(function ($query) use ($q) {
                $query->where('name_ja', 'like', "%{$q}%")
                    ->orWhere('name_en', 'like', "%{$q}%")
                    ->orWhere('kana', 'like', "%{$q}%")
                    ->orWhere('address_ja', 'like', "%{$q}%");
            })
            ->limit(5)
            ->get()
            ->map(fn ($place) => [
                'type' => 'place',
                'label' => '城・城跡',
                'name' => $place->name_ja,
            ]);

        $prefectures = Prefecture::query()
            ->where(function ($query) use ($q) {
                $query->where('name_ja', 'like', "%{$q}%")
                    ->orWhere('name_en', 'like', "%{$q}%");
            })
            ->limit(3)
            ->get()
            ->map(fn ($prefecture) => [
                'type' => 'prefecture',
                'label' => '地域',
                'name' => $prefecture->name_ja,
            ]);

        $tags = Tag::query()
            ->where(function ($query) use ($q) {
                $query->where('name_ja', 'like', "%{$q}%")
                    ->orWhere('name_en', 'like', "%{$q}%");
            })
            ->limit(5)
            ->get()
            ->map(fn ($tag) => [
                'type' => 'tag',
                'label' => 'タグ',
                'name' => $tag->name_ja,
            ]);

        return response()->json(
            $places
                ->concat($prefectures)
                ->concat($tags)
                ->take(10)
                ->values()
        );
    }
}