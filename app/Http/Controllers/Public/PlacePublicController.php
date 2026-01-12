<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Place;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlacePublicController extends Controller
{
    public function home(): View
    {
        // メニューに出すカテゴリ（sort_order順）
        $categories = Category::query()
            ->orderBy('sort_order')
            ->get();

        // 最新の公開Placeを少しだけ（トップに出す用）
        $latestPlaces = Place::query()
            ->published()
            ->with(['category', 'prefecture', 'thumbnailPhoto', 'tags'])
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(12)
            ->get();

        return view('public.home', compact('categories', 'latestPlaces'));
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

        $places = Place::query()
            ->published()
            ->with(['category', 'prefecture', 'thumbnailPhoto', 'tags'])
            ->keyword($q)
            ->orderByDesc('rating')
            ->orderByDesc('published_at')
            ->paginate(24)
            ->withQueryString();

        // タグ候補（超簡易：検索語を含むタグ）
        $tags = [];
        if ($q !== '') {
            $tags = Tag::query()
                ->where('name_ja', 'like', "%{$q}%")
                ->orWhere('name_en', 'like', "%{$q}%")
                ->limit(20)
                ->get();
        }

        return view('public.search', compact('q', 'places', 'tags'));
    }
}
