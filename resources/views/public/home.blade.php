@php
    $title = 'Daytripper｜日本の城・城跡を探せる日帰り旅行ガイド';
    $description = '日本各地の城・城跡を写真付きで紹介。見どころ、アクセス、料金、周辺散策、カテゴリ別・都道府県別に探せる Daytripper。';
    $canonical = route('public.home');
    $ogImage = url(asset('images/ogp-default.png'));

    $jsonLdBlock = '<script type="application/ld+json">' . json_encode([
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'WebSite',
                '@id' => $canonical . '#website',
                'url' => $canonical,
                'name' => 'Daytripper',
                'inLanguage' => 'ja',
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => route('public.search') . '?q={search_term_string}',
                    'query-input' => 'required name=search_term_string',
                ],
            ],
            [
                '@type' => 'Organization',
                '@id' => $canonical . '#organization',
                'name' => 'Daytripper',
                'url' => $canonical,
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => $ogImage,
                ],
            ],
            [
                '@type' => 'WebPage',
                '@id' => $canonical . '#webpage',
                'url' => $canonical,
                'name' => $title,
                'description' => $description,
                'inLanguage' => 'ja',
                'isPartOf' => [
                    '@id' => $canonical . '#website',
                ],
                'about' => [
                    '日本の城',
                    '城跡',
                    '文化財',
                    '日帰り旅行',
                ],
            ],
        ],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
@endphp

<x-public-layout
    :title="$title"
    :description="$description"
    :canonical="$canonical"
    :ogImage="$ogImage"
    :ogUrl="$canonical"
    :ogType="'website'"
    :jsonLd="$jsonLdBlock"
>
    <div class="space-y-10">
        <section class="rounded-2xl border border-slate-900/10 bg-white/60 shadow-sm overflow-hidden">
            <div class="p-6 md:p-8">
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl md:text-3xl tracking-wide leading-tight"
                        style="font-family:'Noto Serif JP', serif;">
                        日本の城・城跡を探せる日帰り旅行ガイド
                    </h1>
                </div>

                <p class="mt-3 text-slate-700 leading-relaxed">
                    Daytripper は、日本各地の城・城跡を写真付きで紹介する観光ガイドです。
                    見どころ、アクセス、料金、周辺散策の情報をまとめ、カテゴリや地域から探せます。
                </p>

                <div class="mt-4 text-sm text-slate-600 leading-relaxed">
                    検索では「熊本城」「五稜郭」「現存天守」など、城名・地域・タグで探せます。
                </div>

                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <a href="{{ route('public.about') }}"
                       class="p-4 rounded-xl border border-slate-900/10 bg-white/70 hover:bg-white shadow-sm transition">
                        <div class="text-sm font-medium">初めて訪れる人へ</div>
                        <div class="text-xs text-slate-600 mt-1">サイトの使い方・見方</div>
                    </a>

                    @if(\Illuminate\Support\Facades\Route::has('public.prefectures'))
                        <a href="{{ route('public.prefectures') }}"
                           class="p-4 rounded-xl border border-slate-900/10 bg-white/70 hover:bg-white shadow-sm transition">
                            <div class="text-sm font-medium">都道府県から探す</div>
                            <div class="text-xs text-slate-600 mt-1">地域別に城・城跡を一覧表示</div>
                        </a>
                    @endif

                    <a href="{{ route('public.near') }}"
                       class="p-4 rounded-xl border border-slate-900/10 bg-white/70 hover:bg-white shadow-sm transition">
                        <div class="text-sm font-medium">お城レーダー</div>
                        <div class="text-xs text-slate-600 mt-1">現在地の近くにある城を検索</div>
                    </a>

                    <a href="{{ route('public.search', ['q' => '日本100名城']) }}"
                       class="p-4 rounded-xl border border-slate-900/10 bg-white/70 hover:bg-white shadow-sm transition">
                        <div class="text-sm font-medium">日本100名城を探す</div>
                        <div class="text-xs text-slate-600 mt-1">定番の名城から探す</div>
                    </a>
                </div>
            </div>

            <div class="h-px bg-slate-900/10"></div>

            <div class="px-6 md:px-8 py-3 text-xs text-slate-600 bg-[#fbfaf7]/60">
                城めぐりの行き先探しに使いやすいよう、情報を少しずつ追加しています。
            </div>
        </section>

        @if($categories->isNotEmpty())
            <section class="space-y-4">
                <div class="flex items-center gap-3">
                    <h2 class="text-lg tracking-wide" style="font-family:'Noto Serif JP', serif;">カテゴリから探す</h2>
                    <div class="h-px flex-1 bg-slate-900/10"></div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                    @foreach($categories as $category)
                        <a href="{{ route('public.categories.show', $category) }}"
                           class="p-4 rounded-xl border border-slate-900/10 bg-white/70 hover:bg-white shadow-sm transition">
                            <div class="text-sm font-medium">{{ $category->name_ja }}</div>
                            <div class="text-xs text-slate-600 mt-1">
                                {{ $category->name_ja }}の城・城跡を見る
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        @if($latestPlaces->isNotEmpty())
            <section class="space-y-4">
                <div class="flex items-center gap-3">
                    <h2 class="text-lg tracking-wide" style="font-family:'Noto Serif JP', serif;">最近追加した城・城跡</h2>
                    <div class="h-px flex-1 bg-slate-900/10"></div>
                </div>

                <p class="text-sm text-slate-600">
                    新しく公開した城・城跡の詳細ページです。写真や見どころ、基本情報を掲載しています。
                </p>

                @include('public._place_grid', ['places' => $latestPlaces])
            </section>
        @endif

        @php
            $logs = $changelogs ?? collect();
            $newBorder = now()->subDays(7);
        @endphp

        @if($logs->isNotEmpty())
            <section class="rounded-2xl border border-slate-900/10 bg-white/60 shadow-sm p-5 md:p-6">
                <div class="flex items-center gap-3 mb-4">
                    <h2 class="text-lg tracking-wide" style="font-family:'Noto Serif JP', serif;">更新履歴</h2>
                    <div class="h-px flex-1 bg-slate-900/10"></div>
                </div>

                <ul class="space-y-3 text-sm">
                    @foreach($logs->take(10) as $log)
                        @php
                            $date = \Illuminate\Support\Carbon::parse($log['date']);
                            $isNew = $date->greaterThanOrEqualTo($newBorder);
                        @endphp

                        <li class="flex items-start gap-3">
                            <span class="text-slate-500 tabular-nums w-24 shrink-0">
                                {{ $date->format('Y-m-d') }}
                            </span>

                            <div class="flex-1">
                                @if(!empty($log['url']))
                                    <a href="{{ $log['url'] }}" class="hover:underline">
                                        {{ $log['title_ja'] ?? '' }}
                                    </a>
                                @else
                                    <span>{{ $log['title_ja'] ?? '' }}</span>
                                @endif

                                @if($isNew)
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-md border border-[#c2412d]/25 bg-[#fbfaf7] text-[#a83626] text-xs shadow-sm">
                                        NEW
                                    </span>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif
    </div>
</x-public-layout>