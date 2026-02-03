<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Place;
use App\Models\Tag;
use App\Models\Prefecture;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class PlacePublicController extends Controller
{
    public function home(): View
    {
        $categories = Category::query()
            ->orderBy('sort_order')
            ->get();

        $latestPlaces = Place::query()
            ->published()
            ->with(['category', 'prefecture', 'thumbnailPhoto', 'tags'])
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(12)
            ->get();

        $changelogs = collect(config('changelog', []))
            ->sortByDesc(fn($x) => $x['date'])
            ->values();

        return view('public.home', compact('categories', 'latestPlaces', 'changelogs'));
    }

    public function category(Category $category, Request $request): View
    {
        $places = Place::query()
            ->published()
            ->where('category_id', $category->id)
            ->with(['prefecture', 'thumbnailPhoto', 'tags'])
            ->orderByDesc('rating')
            ->orderBy('id')
            ->paginate(24)
            ->withQueryString();

        return view('public.category', compact('category', 'places'));
    }

    public function show(Place $place): View
    {
        abort_unless($place->is_published, 404);

        $place->load(['category', 'prefecture', 'galleryPhotos', 'thumbnailPhoto', 'tags']);

        return view('public.show', compact('place'));
    }

    public function tag(Tag $tag, Request $request): View
    {
        $places = Place::query()
            ->published()
            ->whereHas('tags', fn($q) => $q->where('tags.id', $tag->id))
            ->with(['category', 'prefecture', 'thumbnailPhoto', 'tags'])
            ->orderByDesc('rating')
            ->orderBy('id')
            ->paginate(24)
            ->withQueryString();

        return view('public.tag', compact('tag', 'places'));
    }

    public function search(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        if ($q === '') {
            return view('public.search', [
                'q' => '',
                'places' => null,
                'tags' => [],
                'alert' => '何かキーワードを入力してください。',
            ]);
        }

        $places = Place::query()
            ->published()
            ->with(['category', 'prefecture', 'thumbnailPhoto', 'tags'])
            ->keyword($q)
            ->orderByDesc('rating')
            ->orderByDesc('published_at')
            ->paginate(24)
            ->withQueryString();

        // タグ候補（検索語を含むタグ）
        $tags = Tag::query()
            ->where('name_ja', 'like', "%{$q}%")
            ->orWhere('name_en', 'like', "%{$q}%")
            ->limit(20)
            ->get();

        return view('public.search', [
            'q' => $q,
            'places' => $places,
            'tags' => $tags,
            'alert' => null,
        ]);
    }

    public function near(Request $request): View
    {
        $lat = $request->query('lat');
        $lng = $request->query('lng');

        $prefectures = Prefecture::query()
            ->orderBy('id')
            ->get(['id','name_ja','lat','lng']);

        $prefectureId = $request->query('prefecture_id');

        if (($lat === null || $lng === null) && $prefectureId && is_numeric($prefectureId)) {
            $pref = $prefectures->firstWhere('id', (int)$prefectureId);
            if ($pref && $pref->lat !== null && $pref->lng !== null) {
                $lat = (float)$pref->lat;
                $lng = (float)$pref->lng;
            }
        }

        // 半径km（任意）: 10/20/30/50 など。デフォは30km
        $radiusKm = (float) $request->query('r', 30);
        if ($radiusKm <= 0) $radiusKm = 30;

        // 緯度経度が無ければ、取得用ページとして表示
        if ($lat === null || $lng === null || !is_numeric($lat) || !is_numeric($lng)) {
            return view('public.near', [
                'lat' => null,
                'lng' => null,
                'radiusKm' => $radiusKm,
                'places' => null,
                'prefectures' => $prefectures,
                'prefectureId' => $prefectureId,
            ]);
        }

        $lat = (float) $lat;
        $lng = (float) $lng;

        // ---- 絞り込み（bounding box）で高速化 ----
        // 緯度1度 ≒ 111km
        $latDelta = $radiusKm / 111.0;
        // 経度は緯度で変化
        $lngDelta = $radiusKm / (111.0 * max(cos(deg2rad($lat)), 0.01));

        $minLat = $lat - $latDelta;
        $maxLat = $lat + $latDelta;
        $minLng = $lng - $lngDelta;
        $maxLng = $lng + $lngDelta;

        // ---- Haversine（km） ----
        // 6371 = 地球半径（km）
        $distanceSql = "
            (6371 * acos(
                cos(radians(?)) * cos(radians(lat)) * cos(radians(lng) - radians(?)) +
                sin(radians(?)) * sin(radians(lat))
            ))
        ";

        $places = \App\Models\Place::query()
            ->published()
            ->whereNotNull('lat')->whereNotNull('lng')
            ->whereBetween('lat', [$minLat, $maxLat])
            ->whereBetween('lng', [$minLng, $maxLng])
            ->with(['category', 'prefecture', 'thumbnailPhoto', 'tags'])
            ->select('places.*')
            ->selectRaw("$distanceSql as distance_km", [$lat, $lng, $lat])
            ->orderBy('distance_km')
            ->paginate(24)
            ->withQueryString();

        return view('public.near', [
            'lat' => $lat,
            'lng' => $lng,
            'radiusKm' => $radiusKm,
            'places' => $places,
            'prefectures' => $prefectures,
            'prefectureId' => $prefectureId,
        ]);
    }
}
