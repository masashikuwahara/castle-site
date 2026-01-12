<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Place;
use Illuminate\Http\Response;
use Illuminate\View\View;

class SitemapController extends Controller
{
    public function index(): Response
    {
        // 静的ページ（必要最低限）
        $staticUrls = [
            [
                'loc' => route('public.home'),
                'lastmod' => now(),
                'changefreq' => 'weekly',
                'priority' => '1.0',
            ],
            [
                'loc' => route('public.about'),
                'lastmod' => now(),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ],
        ];

        // カテゴリページ（数が少ないので入れる）
        $categoryUrls = Category::query()
            ->orderBy('sort_order')
            ->get()
            ->map(function ($category) {
                return [
                    'loc' => route('public.categories.show', $category),
                    'lastmod' => $category->updated_at ?? now(),
                    'changefreq' => 'weekly',
                    'priority' => '0.7',
                ];
            })
            ->all();

        // 公開Place（メイン）
        $placeUrls = Place::query()
            ->published()
            ->select(['slug', 'updated_at', 'published_at'])
            ->orderByDesc('published_at')
            ->orderByDesc('updated_at')
            ->get()
            ->map(function ($place) {
                return [
                    'loc' => route('public.places.show', ['place' => $place->slug]),
                    'lastmod' => ($place->updated_at ?? $place->published_at ?? now()),
                    'changefreq' => 'monthly',
                    'priority' => '0.8',
                ];
            })
            ->all();

        $urls = array_merge($staticUrls, $categoryUrls, $placeUrls);

        return response()
            ->view('public.sitemap', ['urls' => $urls], 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
