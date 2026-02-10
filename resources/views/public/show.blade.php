@php
    $isJa = app()->getLocale() === 'ja';
    $name = $isJa ? $place->name_ja : ($place->name_en ?? $place->name_ja);

    $title = $isJa
        ? "{$place->name_ja}｜見どころ・アクセス・遺構・料金 | Daytripper"
        : "{$name} | Highlights, Access, Fees | Daytripper";

    $description = $isJa
        ? trim(($place->short_desc_ja ?: '').' '.$place->prefecture->name_ja.'の城・城跡。開城時間・休城日・料金、遺構や見どころを写真付きで紹介。')
        : trim(($place->short_desc_en ?: '').' A castle site in '.($place->prefecture->name_en ?? $place->prefecture->name_ja).'. Photos, opening hours, closed days, fees, and highlights.');

    // og imageは絶対URL化
    $ogImage = $place->thumbnailPhoto
        ? url(Storage::url($place->thumbnailPhoto->path))
        : url(asset('images/ogp-default.png'));

    $canonical = route('public.places.show', $place);

    // JSON-LD（最低限）
    $jsonLd = json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'TouristAttraction',
        'name' => $name,
        'description' => $description,
        'url' => $canonical,
        'image' => $ogImage,
        'address' => [
            '@type' => 'PostalAddress',
            'addressCountry' => 'JP',
            'addressRegion' => $isJa ? $place->prefecture->name_ja : ($place->prefecture->name_en ?? $place->prefecture->name_ja),
            'streetAddress' => $isJa ? $place->address_ja : ($place->address_en ?? $place->address_ja),
        ],
        'geo' => ($place->lat && $place->lng) ? [
            '@type' => 'GeoCoordinates',
            'latitude' => (float)$place->lat,
            'longitude' => (float)$place->lng,
        ] : null,
    ], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

    $breadcrumbLd = json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type'=>'ListItem','position'=>1,'name'=> $isJa ? 'トップ' : 'Home','item'=> route('public.home')],
            ['@type'=>'ListItem','position'=>2,'name'=> $isJa ? $place->category->name_ja : ($place->category->name_en ?? $place->category->name_ja),
             'item'=> route('public.categories.show', $place->category)],
            ['@type'=>'ListItem','position'=>3,'name'=> $name,'item'=> $canonical],
        ],
    ], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

    $jsonLdBlock = '<script type="application/ld+json">'.$jsonLd.'</script>'
                 . '<script type="application/ld+json">'.$breadcrumbLd.'</script>';
@endphp

<x-public-layout
    {{-- :title="$place->name_ja . '｜城・文化財'"
    :description="$description"
    :ogImage="$ogImage"
    :ogUrl="route('public.places.show', $place)" --}}
    :title="$title"
    :description="$description"
    :canonical="$canonical"
    :ogImage="$ogImage"
    :ogUrl="$canonical"
    :jsonLd="$jsonLdBlock"
    >

    <div class="space-y-6">
        <div class="space-y-8">
        <section class="space-y-3">
            @include('public._breadcrumb', [
                'items' => [
                    ['label' => 'トップ', 'url' => route('public.home')],
                    ['label' => $place->category?->name_ja ?? 'カテゴリ', 'url' => route('public.categories.show', $place->category)],
                    ['label' => $place->prefecture?->name_ja ?? '都道府県', 'url' => null],
                ]
            ])
            <h1 class="text-2xl md:text-3xl tracking-wide leading-tight"
                style="font-family:'Noto Serif JP', serif;">
                {{ $place->name_ja }}
            </h1>
            @if($place->name_en)
                <div class="text-sm text-slate-600">{{ $place->name_en }}</div>
            @endif

            @if($place->tags?->isNotEmpty())
                <div class="flex flex-wrap gap-2 pt-1">
                    @foreach($place->tags as $tag)
                        <a href="{{ route('public.tags.show', $tag) }}"
                        class="px-3 py-1 rounded-full text-sm
                                border border-slate-900/10 bg-white/60 hover:bg-white">
                            #{{ $tag->name_ja }}
                        </a>
                    @endforeach
                </div>
            @endif
        </section>

        {{-- メイン写真 --}}
        @if($place->thumbnailPhoto)
            <section class="rounded-2xl border border-slate-900/10 bg-white/60 shadow-sm overflow-hidden">
                <img src="{{ Storage::url($place->thumbnailPhoto->path) }}"
                    class="w-full max-h-[520px] object-cover"
                    alt="{{ $place->name_ja }} の写真">

                @if($place->thumbnailPhoto->caption_ja)
                    <div class="px-5 py-4 text-sm text-slate-600 bg-[#fbfaf7]/60 border-t border-slate-900/10">
                        {{ $place->thumbnailPhoto->caption_ja }}
                    </div>
                @endif
            </section>
        @endif

        {{-- 基本情報（“札”レイアウト） --}}
        <section class="rounded-2xl border border-slate-900/10 bg-white/60 shadow-sm p-5 md:p-6">
            <div class="flex items-center gap-3 mb-4">
                <h2 class="text-lg tracking-wide" style="font-family:'Noto Serif JP', serif;">基本情報</h2>
                <div class="h-px flex-1 bg-slate-900/10"></div>

                @if(!empty($place->rating))
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md border border-[#c2412d]/25 bg-[#fbfaf7] text-[#a83626] text-xs shadow-sm">
                        おすすめ {{ str_repeat('★', (int)$place->rating) }}
                    </span>
                @endif
            </div>

            <dl class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                {{-- 1項目＝札 --}}
                <div class="rounded-xl border border-slate-900/10 bg-white/60 p-3">
                    <dt class="text-xs text-slate-600">城郭構造</dt>
                    <dd class="mt-1 text-slate-900">{{ $place->castle_style_ja }}</dd>
                </div>

                <div class="rounded-xl border border-slate-900/10 bg-white/60 p-3">
                    <dt class="text-xs text-slate-600">天守構造</dt>
                    <dd class="mt-1 text-slate-900">{{ $place->tenshu_style_ja }}</dd>
                </div>

                <div class="rounded-xl border border-slate-900/10 bg-white/60 p-3">
                    <dt class="text-xs text-slate-600">築城主</dt>
                    <dd class="mt-1 text-slate-900">{{ $place->builder_ja }}</dd>
                </div>

                <div class="rounded-xl border border-slate-900/10 bg-white/60 p-3">
                    <dt class="text-xs text-slate-600">築城年</dt>
                    <dd class="mt-1 text-slate-900">{{ $place->built_year }}</dd>
                </div>

                <div class="rounded-xl border border-slate-900/10 bg-white/60 p-3">
                    <dt class="text-xs text-slate-600">廃城年</dt>
                    <dd class="mt-1 text-slate-900">{{ $place->abolished_year }}</dd>
                </div>

                <div class="rounded-xl border border-slate-900/10 bg-white/60 p-3">
                    <dt class="text-xs text-slate-600">主な改修者</dt>
                    <dd class="mt-1 text-slate-900">{{ $place->renovator_ja }}</dd>
                </div>

                <div class="md:col-span-2 rounded-xl border border-slate-900/10 bg-white/60 p-3">
                    <dt class="text-xs text-slate-600">主な城主</dt>
                    <dd class="mt-1 text-slate-900 leading-relaxed">{!! nl2br(e($place->main_lords_ja)) !!}</dd>
                </div>

                <div class="md:col-span-2 rounded-xl border border-slate-900/10 bg-white/60 p-3">
                    <dt class="text-xs text-slate-600">指定文化財</dt>
                    <dd class="mt-1 text-slate-900">{{ $place->heritage_designation_ja }}</dd>
                </div>

                <div class="md:col-span-2 rounded-xl border border-slate-900/10 bg-white/60 p-3">
                    <dt class="text-xs text-slate-600">遺構</dt>
                    <dd class="mt-1 text-slate-900 leading-relaxed">{!! nl2br(e($place->remains_ja)) !!}</dd>
                </div>

                <div class="md:col-span-2 rounded-xl border border-slate-900/10 bg-white/60 p-3">
                    <dt class="text-xs text-slate-600">住所</dt>
                    <dd class="mt-1 text-slate-900">{{ $place->address_ja }}</dd>
                </div>

                @if($place->opening_hours_ja)
                    <div class="md:col-span-2 rounded-xl border border-slate-900/10 bg-white/60 p-3">
                        <dt class="text-xs text-slate-600">開城時間</dt>
                        <dd class="mt-1 text-slate-900 leading-relaxed">{!! nl2br(e($place->opening_hours_ja)) !!}</dd>
                    </div>
                @endif

                @if($place->closed_days_ja)
                    <div class="md:col-span-2 rounded-xl border border-slate-900/10 bg-white/60 p-3">
                        <dt class="text-xs text-slate-600">休城日</dt>
                        <dd class="mt-1 text-slate-900">{{ $place->closed_days_ja }}</dd>
                    </div>
                @endif

                @if($place->admission_fee_ja)
                    <div class="md:col-span-2 rounded-xl border border-slate-900/10 bg-white/60 p-3">
                        <dt class="text-xs text-slate-600">入城料金</dt>
                        <dd class="mt-1 text-slate-900">{{ $place->admission_fee_ja }}</dd>
                    </div>
                @endif
            </dl>
        </section>

        {{-- 概要 --}}
        @if($place->description_ja)
            <section class="rounded-2xl border border-slate-900/10 bg-white/60 shadow-sm p-5 md:p-6">
                <div class="flex items-center gap-3 mb-4">
                    <h2 class="text-lg tracking-wide" style="font-family:'Noto Serif JP', serif;">概要</h2>
                    <div class="h-px flex-1 bg-slate-900/10"></div>
                </div>

                <div class="prose max-w-none prose-slate">
                    {!! nl2br(e($place->description_ja)) !!}
                </div>
            </section>
        @endif

        {{-- Map --}}
        <section class="rounded-2xl border border-slate-900/10 bg-white/60 shadow-sm p-5 md:p-6 space-y-3">
            <div class="flex items-center gap-3">
                <h2 class="text-lg tracking-wide" style="font-family:'Noto Serif JP', serif;">地図</h2>
                <div class="h-px flex-1 bg-slate-900/10"></div>
            </div>

            @php
                $mapQuery = null;
                if (!is_null($place->lat) && !is_null($place->lng)) {
                    $mapQuery = $place->lat.','.$place->lng;
                } elseif ($place->address_ja) {
                    $mapQuery = $place->address_ja;
                }
            @endphp

            @if($mapQuery)
                <iframe
                    class="w-full h-[360px] rounded-xl border border-slate-900/10"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    src="https://www.google.com/maps?q={{ urlencode($mapQuery) }}&output=embed">
                </iframe>
                <div class="text-xs text-slate-600">
                    ※ 地図がずれる場合は住所表記（町名/番地）を調整すると改善することがあります。
                </div>
            @else
                <div class="text-sm text-slate-600">地図を表示するには住所または緯度経度を登録してください。</div>
            @endif
        </section>

        {{-- 写真ギャラリー --}}
        @if($place->galleryPhotos->count() > 0)
            <section class="space-y-4">
                <div class="flex items-center gap-3">
                    <h2 class="text-lg tracking-wide" style="font-family:'Noto Serif JP', serif;">写真</h2>
                    <div class="h-px flex-1 bg-slate-900/10"></div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach($place->galleryPhotos as $photo)
                        @php $cap = $photo->caption_ja ?: $photo->caption_en; @endphp

                        <button
                            type="button"
                            class="group rounded-2xl border border-slate-900/10 bg-white/60 shadow-sm overflow-hidden text-left
                                hover:shadow-md transition"
                            data-gallery="place-gallery"
                            data-src="{{ url(Storage::url($photo->path)) }}"
                            data-caption="{{ e($cap ?? '') }}"
                        >
                            <img src="{{ Storage::url($photo->path) }}"
                                class="w-full aspect-square object-cover group-hover:scale-[1.02] transition"
                                alt="{{ $place->name_ja }} の写真">

                            @if($cap)
                                <div class="p-3 text-xs text-slate-600 bg-[#fbfaf7]/60 border-t border-slate-900/10 line-clamp-2">
                                    {{ $cap }}
                                </div>
                            @endif
                        </button>
                    @endforeach
                </div>
            </section>
        @endif
    </div>

    {{-- Gallery Modal --}}
    <div id="galleryModal" class="fixed inset-0 z-50 hidden" data-close="1">
        {{-- overlay --}}
        <div id="galleryOverlay" class="absolute inset-0 bg-black/70" data-close="1"></div>

        {{-- modal content --}}
        <div class="relative w-full h-full flex items-center justify-center p-4" data-close="1">
            <div class="relative max-w-5xl w-full" data-close="0">
                {{-- close --}}
                <button id="galleryClose"
                        class="absolute -top-10 right-0 text-white text-sm px-3 py-2 rounded-xl border border-white/20 bg-black/40 hover:bg-black/60 backdrop-blur">
                    閉じる（Esc）
                </button>

                {{-- image --}}
                <div class="relative bg-black rounded overflow-hidden">
                    <img id="galleryImage" src="" alt="" class="w-full max-h-[80vh] object-contain bg-black">

                    {{-- arrows --}}
                    <button id="galleryPrev"
                            class="absolute left-2 top-1/2 -translate-y-1/2 text-white bg-black/40 hover:bg-black/60 rounded-full w-10 h-10 flex items-center justify-center"
                            aria-label="前の写真">
                        ←
                    </button>
                    <button id="galleryNext"
                            class="absolute right-2 top-1/2 -translate-y-1/2 text-white bg-black/40 hover:bg-black/60 rounded-full w-10 h-10 flex items-center justify-center"
                            aria-label="次の写真">
                        →
                    </button>
                </div>

                {{-- caption --}}
                <div id="galleryCaption" class="mt-3 text-white text-sm bg-black/40 rounded p-3"></div>
            </div>
        </div>
    </div>
</x-public-layout>