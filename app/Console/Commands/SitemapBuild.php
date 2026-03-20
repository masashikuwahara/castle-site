<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Place;
use App\Models\Tag;
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

        // 固定ページ
        $sitemap->add(
            Url::create(route('public.home'))
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(1.0)
        );

        $sitemap->add(
            Url::create(route('public.about'))
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
                ->setPriority(0.2)
        );

        $sitemap->add(
            Url::create(route('public.search'))
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.2)
        );

        $sitemap->add(
            Url::create(route('public.near'))
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.2)
        );

        // カテゴリ
        foreach (Category::query()->orderBy('sort_order')->get() as $category) {
            $sitemap->add(
                Url::create(route('public.categories.show', $category))
                    ->setLastModificationDate($category->updated_at ?? now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.7)
            );
        }

        // タグ
        foreach (Tag::query()->orderBy('id')->get() as $tag) {
            $sitemap->add(
                Url::create(route('public.tags.show', $tag))
                    ->setLastModificationDate($tag->updated_at ?? now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.3)
            );
        }

        // Place詳細
        $query = Place::query();

        if (!$this->option('force')) {
            $query->published();
        }

        $query->select(['id', 'slug', 'updated_at'])
            ->chunkById(500, function ($places) use ($sitemap) {
                foreach ($places as $place) {
                    $sitemap->add(
                        Url::create(route('public.places.show', $place))
                            ->setLastModificationDate($place->updated_at ?? now())
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