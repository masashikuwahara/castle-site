<?php

namespace App\Console\Commands;

use App\Models\Place;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapBuild extends Command
{
    protected $signature = 'sitemap:build {--force : Include non-published places}';
    protected $description = 'Build sitemap.xml into public directory';

    public function handle(): int
    {
        $baseUrl = config('app.url');
        if (!$baseUrl) {
            $this->error('APP_URL is not set. Please set APP_URL in .env');
            return self::FAILURE;
        }

        $sitemap = Sitemap::create();

        // 固定ページ（必要に応じて追加）
        $sitemap->add(
            Url::create(route('public.home'))
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(1.0)
        );

        $sitemap->add(
            Url::create(route('public.about'))
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
                ->setPriority(0.2)
        );

        $sitemap->add(
            Url::create(route('public.search'))
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.2)
        );

        $sitemap->add(
            Url::create(route('public.near'))
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.2)
        );

        // カテゴリ一覧（カテゴリページがある前提）
        foreach (\App\Models\Category::query()->orderBy('sort_order')->get() as $category) {
            $sitemap->add(
                Url::create(route('public.categories.show', $category))
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.7)
            );
        }

        // タグページ（重くなるなら後でOFFでもOK）
        foreach (\App\Models\Tag::query()->orderBy('id')->get() as $tag) {
            $sitemap->add(
                Url::create(route('public.tags.show', $tag))
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.3)
            );
        }

        // Place詳細（公開のみ。--force なら全件）
        $query = \App\Models\Place::query()->orderByDesc('updated_at');
        if (!$this->option('force')) {
            $query->published();
        }

        $query->select(['id', 'slug', 'updated_at'])->chunkById(500, function ($places) use ($sitemap) {
            foreach ($places as $place) {
                $sitemap->add(
                    Url::create(route('public.places.show', $place))
                        ->setLastModificationDate($place->updated_at ? Carbon::parse($place->updated_at) : now())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                        ->setPriority(0.8)
                );
            }
        });

        $path = public_path('sitemap.xml');
        $sitemap->writeToFile($path);

        $this->info("Sitemap generated: {$path}");

        return self::SUCCESS;
    }
}
