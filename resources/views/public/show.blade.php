@php
    $isJa = app()->getLocale() === 'ja';

    $placeName = $isJa ? $place->name_ja : ($place->name_en ?: $place->name_ja);
    $placeNameJa = $place->name_ja;
    $placeNameEn = $place->name_en ?: null;
    $subName = $isJa
        ? ($placeNameEn && $placeNameEn !== $placeNameJa ? $placeNameEn : null)
        : ($placeNameJa && $placeNameJa !== $placeName ? $placeNameJa : null);

    $prefName = $isJa
        ? ($place->prefecture->name_ja ?? null)
        : ($place->prefecture->name_en ?? $place->prefecture->name_ja ?? null);

    $categoryName = $isJa
        ? ($place->category->name_ja ?? null)
        : ($place->category->name_en ?? $place->category->name_ja ?? null);

    $castleStyle = $isJa ? ($place->castle_style_ja ?? null) : ($place->castle_style_en ?? $place->castle_style_ja ?? null);
    $tenshuStyle = $isJa ? ($place->tenshu_style_ja ?? null) : ($place->tenshu_style_en ?? $place->tenshu_style_ja ?? null);
    $builder = $isJa ? ($place->builder_ja ?? null) : ($place->builder_en ?? $place->builder_ja ?? null);
    $renovator = $isJa ? ($place->renovator_ja ?? null) : ($place->renovator_en ?? $place->renovator_ja ?? null);
    $mainLords = $isJa ? ($place->main_lords_ja ?? null) : ($place->main_lords_en ?? $place->main_lords_ja ?? null);
    $heritage = $isJa ? ($place->heritage_designation_ja ?? null) : ($place->heritage_designation_en ?? $place->heritage_designation_ja ?? null);
    $remains = $isJa ? ($place->remains_ja ?? null) : ($place->remains_en ?? $place->remains_ja ?? null);
    $addressText = $isJa ? ($place->address_ja ?? null) : ($place->address_en ?? $place->address_ja ?? null);
    $openingHours = $isJa ? ($place->opening_hours_ja ?? null) : ($place->opening_hours_en ?? $place->opening_hours_ja ?? null);
    $closedDays = $isJa ? ($place->closed_days_ja ?? null) : ($place->closed_days_en ?? $place->closed_days_ja ?? null);
    $admissionFee = $isJa ? ($place->admission_fee_ja ?? null) : ($place->admission_fee_en ?? $place->admission_fee_ja ?? null);
    $descriptionBody = $isJa ? ($place->description_ja ?? null) : ($place->description_en ?? $place->description_ja ?? null);
    $shortDesc = $isJa ? ($place->short_desc_ja ?? null) : ($place->short_desc_en ?? $place->short_desc_ja ?? null);

    $fallbackLead = $descriptionBody
        ? \Illuminate\Support\Str::limit(
            trim(preg_replace('/\s+/u', ' ', strip_tags($descriptionBody))),
            $isJa ? 90 : 120,
            '…'
        )
        : null;

    $lead = trim($shortDesc ?: $fallbackLead ?: '');

    $title = $isJa
        ? trim($place->name_ja . ($prefName ? "（{$prefName}）" : '') . 'の見どころ・アクセス | Daytripper')
        : trim($placeName . ($prefName ? " ({$prefName})" : '') . ' | Highlights & Access | Daytripper');

    $description = $isJa
        ? trim(($lead ? $lead . ' ' : '') . '写真、遺構、アクセス、開城時間、休城日、料金情報を掲載。')
        : trim(($lead ? $lead . ' ' : '') . 'Photos, remains, access, opening hours, closed days, and admission info.');

    $description = preg_replace('/\s+/u', ' ', $description);

    $ogImage = $place->thumbnailPhoto
        ? url(\Illuminate\Support\Facades\Storage::url($place->thumbnailPhoto->path))
        : url(asset('images/ogp-default.png'));

    $canonical = route('public.places.show', $place);

    $labels = [
        'home' => $isJa ? 'トップ' : 'Home',
        'categoryFallback' => $isJa ? 'カテゴリ' : 'Category',
        'overview' => $isJa ? '概要' : 'Overview',
        'basicInfo' => $isJa ? '基本情報' : 'Basic Information',
        'map' => $isJa ? '地図' : 'Map',
        'photos' => $isJa ? '写真' : 'Photos',
        'castleStyle' => $isJa ? '城郭構造' : 'Castle style',
        'tenshuStyle' => $isJa ? '天守構造' : 'Tenshu style',
        'builder' => $isJa ? '築城主' : 'Builder',
        'builtYear' => $isJa ? '築城年' : 'Built',
        'abolishedYear' => $isJa ? '廃城年' : 'Abolished',
        'renovator' => $isJa ? '主な改修者' : 'Renovator',
        'mainLords' => $isJa ? '主な城主' : 'Main lords',
        'heritage' => $isJa ? '指定文化財' : 'Heritage designation',
        'remains' => $isJa ? '遺構' : 'Remains',
        'address' => $isJa ? '住所' : 'Address',
        'openingHours' => $isJa ? '開城時間' : 'Opening hours',
        'closedDays' => $isJa ? '休城日' : 'Closed days',
        'admissionFee' => $isJa ? '入城料金' : 'Admission fee',
        'rating' => $isJa ? 'おすすめ' : 'Recommended',
        'close' => $isJa ? '閉じる（Esc）' : 'Close (Esc)',
        'prevPhoto' => $isJa ? '前の写真' : 'Previous photo',
        'nextPhoto' => $isJa ? '次の写真' : 'Next photo',
        'mapFallback' => $isJa ? '地図を表示するには住所または緯度経度を登録してください。' : 'Register an address or coordinates to display the map.',
        'mapNote' => $isJa ? '※ 地図がずれる場合は住所表記（町名/番地）を調整すると改善することがあります。' : 'If the map position is off, refining the address may improve accuracy.',
        'mainPhotoAltFallback' => $isJa ? $place->name_ja . 'の外観写真' : $placeName . ' exterior photo',
        'galleryPhotoAltFallback' => $isJa ? $place->name_ja . 'の写真' : $placeName . ' photo',
    ];

    $thumbAlt = $place->thumbnailPhoto
        ? ($place->thumbnailPhoto->caption_ja
            ?? $place->thumbnailPhoto->caption_en
            ?? $labels['mainPhotoAltFallback'])
        : null;

    $breadcrumbItems = [
        ['label' => $labels['home'], 'url' => route('public.home')],
    ];

    if ($place->category) {
        $breadcrumbItems[] = [
            'label' => $categoryName ?: $labels['categoryFallback'],
            'url' => route('public.categories.show', $place->category),
        ];
    }

    $breadcrumbItems[] = [
        'label' => $placeName,
        'url' => null,
    ];

    $breadcrumbLdItems = [
        [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => $labels['home'],
            'item' => route('public.home'),
        ],
    ];

    $position = 2;

    if ($place->category) {
        $breadcrumbLdItems[] = [
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => $categoryName ?: $labels['categoryFallback'],
            'item' => route('public.categories.show', $place->category),
        ];
    }

    $breadcrumbLdItems[] = [
        '@type' => 'ListItem',
        'position' => $position,
        'name' => $placeName,
        'item' => $canonical,
    ];

    $touristAttraction = array_filter([
        '@type' => 'TouristAttraction',
        '@id' => $canonical . '#place',
        'name' => $placeName,
        'description' => $description,
        'url' => $canonical,
        'mainEntityOfPage' => $canonical,
        'inLanguage' => $isJa ? 'ja' : 'en',
        'image' => [$ogImage],
        'address' => array_filter([
            '@type' => 'PostalAddress',
            'addressCountry' => 'JP',
            'addressRegion' => $prefName,
            'streetAddress' => $addressText,
        ], fn ($v) => !is_null($v) && $v !== ''),
    ], fn ($v) => !is_null($v) && $v !== '' && $v !== []);

    if (!is_null($place->lat) && !is_null($place->lng)) {
        $touristAttraction['geo'] = [
            '@type' => 'GeoCoordinates',
            'latitude' => (float) $place->lat,
            'longitude' => (float) $place->lng,
        ];
    }

    $breadcrumbLd = [
        '@type' => 'BreadcrumbList',
        'itemListElement' => $breadcrumbLdItems,
    ];

    $jsonLdBlock = '<script type="application/ld+json">' . json_encode([
        '@context' => 'https://schema.org',
        '@graph' => [$touristAttraction, $breadcrumbLd],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';

    $mapQuery = null;
    if (!is_null($place->lat) && !is_null($place->lng)) {
        $mapQuery = $place->lat . ',' . $place->lng;
    } elseif ($addressText) {
        $mapQuery = $addressText;
    }

    $ratingStars = !empty($place->rating)
        ? str_repeat('★', max(1, min(5, (int) $place->rating)))
        : null;
@endphp

<x-public-layout
    :title="$title"
    :description="$description"
    :canonical="$canonical"
    :ogImage="$ogImage"
    :ogUrl="$canonical"
    :ogType="'article'"
    :jsonLd="$jsonLdBlock"
>
    <div class="space-y-8">
        <section class="space-y-3">
            @include('public._breadcrumb', [
                'items' => $breadcrumbItems
            ])

            <h1 class="text-2xl md:text-3xl tracking-wide leading-tight"
                style="font-family:'Noto Serif JP', serif;">
                {{ $placeName }}
            </h1>

            @if($subName)
                <div class="text-sm text-slate-600">{{ $subName }}</div>
            @endif

            @if($place->tags?->isNotEmpty())
                <div class="flex flex-wrap gap-2 pt-1">
                    @foreach($place->tags as $tag)
                        @php
                            $tagName = $isJa ? ($tag->name_ja ?? null) : ($tag->name_en ?? $tag->name_ja ?? null);
                        @endphp

                        @if($tagName)
                            <a href="{{ route('public.tags.show', $tag) }}"
                               class="px-3 py-1 rounded-full text-sm border border-slate-900/10 bg-white/60 hover:bg-white">
                                #{{ $tagName }}
                            </a>
                        @endif
                    @endforeach
                </div>
            @endif
        </section>

        @if($place->thumbnailPhoto)
            <section class="rounded-2xl border border-slate-900/10 bg-white/60 shadow-sm overflow-hidden">
                <img
                    src="{{ \Illuminate\Support\Facades\Storage::url($place->thumbnailPhoto->path) }}"
                    class="w-full max-h-[520px] object-cover"
                    alt="{{ $thumbAlt }}"
                >

                @if($place->thumbnailPhoto->caption_ja || $place->thumbnailPhoto->caption_en)
                    <div class="px-5 py-4 text-sm text-slate-600 bg-[#fbfaf7]/60 border-t border-slate-900/10">
                        {{ $isJa ? ($place->thumbnailPhoto->caption_ja ?: $place->thumbnailPhoto->caption_en) : ($place->thumbnailPhoto->caption_en ?: $place->thumbnailPhoto->caption_ja) }}
                    </div>
                @endif
            </section>
        @endif

        <section class="rounded-2xl border border-slate-900/10 bg-white/60 shadow-sm p-5 md:p-6">
            <div class="flex items-center gap-3 mb-4">
                <h2 class="text-lg tracking-wide" style="font-family:'Noto Serif JP', serif;">{{ $labels['basicInfo'] }}</h2>
                <div class="h-px flex-1 bg-slate-900/10"></div>

                @if($ratingStars)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md border border-[#c2412d]/25 bg-[#fbfaf7] text-[#a83626] text-xs shadow-sm">
                        {{ $labels['rating'] }} {{ $ratingStars }}
                    </span>
                @endif
            </div>

            <dl class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                <div class="rounded-xl border border-slate-900/10 bg-white/60 p-3">
                    <dt class="text-xs text-slate-600">{{ $labels['castleStyle'] }}</dt>
                    <dd class="mt-1 text-slate-900">{{ $castleStyle ?: '—' }}</dd>
                </div>

                <div class="rounded-xl border border-slate-900/10 bg-white/60 p-3">
                    <dt class="text-xs text-slate-600">{{ $labels['tenshuStyle'] }}</dt>
                    <dd class="mt-1 text-slate-900">{{ $tenshuStyle ?: '—' }}</dd>
                </div>

                <div class="rounded-xl border border-slate-900/10 bg-white/60 p-3">
                    <dt class="text-xs text-slate-600">{{ $labels['builder'] }}</dt>
                    <dd class="mt-1 text-slate-900">{{ $builder ?: '—' }}</dd>
                </div>

                <div class="rounded-xl border border-slate-900/10 bg-white/60 p-3">
                    <dt class="text-xs text-slate-600">{{ $labels['builtYear'] }}</dt>
                    <dd class="mt-1 text-slate-900">{{ $place->built_year ?: '—' }}</dd>
                </div>

                <div class="rounded-xl border border-slate-900/10 bg-white/60 p-3">
                    <dt class="text-xs text-slate-600">{{ $labels['abolishedYear'] }}</dt>
                    <dd class="mt-1 text-slate-900">{{ $place->abolished_year ?: '—' }}</dd>
                </div>

                <div class="rounded-xl border border-slate-900/10 bg-white/60 p-3">
                    <dt class="text-xs text-slate-600">{{ $labels['renovator'] }}</dt>
                    <dd class="mt-1 text-slate-900">{{ $renovator ?: '—' }}</dd>
                </div>

                <div class="md:col-span-2 rounded-xl border border-slate-900/10 bg-white/60 p-3">
                    <dt class="text-xs text-slate-600">{{ $labels['mainLords'] }}</dt>
                    <dd class="mt-1 text-slate-900 leading-relaxed">
                        {!! $mainLords ? nl2br(e($mainLords)) : '—' !!}
                    </dd>
                </div>

                <div class="md:col-span-2 rounded-xl border border-slate-900/10 bg-white/60 p-3">
                    <dt class="text-xs text-slate-600">{{ $labels['heritage'] }}</dt>
                    <dd class="mt-1 text-slate-900">{{ $heritage ?: '—' }}</dd>
                </div>

                <div class="md:col-span-2 rounded-xl border border-slate-900/10 bg-white/60 p-3">
                    <dt class="text-xs text-slate-600">{{ $labels['remains'] }}</dt>
                    <dd class="mt-1 text-slate-900 leading-relaxed">
                        {!! $remains ? nl2br(e($remains)) : '—' !!}
                    </dd>
                </div>

                <div class="md:col-span-2 rounded-xl border border-slate-900/10 bg-white/60 p-3">
                    <dt class="text-xs text-slate-600">{{ $labels['address'] }}</dt>
                    <dd class="mt-1 text-slate-900">{{ $addressText ?: '—' }}</dd>
                </div>

                @if($openingHours)
                    <div class="md:col-span-2 rounded-xl border border-slate-900/10 bg-white/60 p-3">
                        <dt class="text-xs text-slate-600">{{ $labels['openingHours'] }}</dt>
                        <dd class="mt-1 text-slate-900 leading-relaxed">{!! nl2br(e($openingHours)) !!}</dd>
                    </div>
                @endif

                @if($closedDays)
                    <div class="md:col-span-2 rounded-xl border border-slate-900/10 bg-white/60 p-3">
                        <dt class="text-xs text-slate-600">{{ $labels['closedDays'] }}</dt>
                        <dd class="mt-1 text-slate-900">{{ $closedDays }}</dd>
                    </div>
                @endif

                @if($admissionFee)
                    <div class="md:col-span-2 rounded-xl border border-slate-900/10 bg-white/60 p-3">
                        <dt class="text-xs text-slate-600">{{ $labels['admissionFee'] }}</dt>
                        <dd class="mt-1 text-slate-900">{{ $admissionFee }}</dd>
                    </div>
                @endif
            </dl>
        </section>

        @if($descriptionBody)
            <section class="rounded-2xl border border-slate-900/10 bg-white/60 shadow-sm p-5 md:p-6">
                <div class="flex items-center gap-3 mb-4">
                    <h2 class="text-lg tracking-wide" style="font-family:'Noto Serif JP', serif;">{{ $labels['overview'] }}</h2>
                    <div class="h-px flex-1 bg-slate-900/10"></div>
                </div>

                <div class="prose max-w-none prose-slate">
                    {!! nl2br(e($descriptionBody)) !!}
                </div>
            </section>
        @endif

        <section class="rounded-2xl border border-slate-900/10 bg-white/60 shadow-sm p-5 md:p-6 space-y-3">
            <div class="flex items-center gap-3">
                <h2 class="text-lg tracking-wide" style="font-family:'Noto Serif JP', serif;">{{ $labels['map'] }}</h2>
                <div class="h-px flex-1 bg-slate-900/10"></div>
            </div>

            @if($mapQuery)
                <iframe
                    class="w-full h-[360px] rounded-xl border border-slate-900/10"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="{{ $placeName }} map"
                    src="https://www.google.com/maps?q={{ urlencode($mapQuery) }}&output=embed">
                </iframe>

                <div class="text-xs text-slate-600">
                    {{ $labels['mapNote'] }}
                </div>
            @else
                <div class="text-sm text-slate-600">{{ $labels['mapFallback'] }}</div>
            @endif
        </section>

        @if($place->galleryPhotos && $place->galleryPhotos->count() > 0)
            <section class="space-y-4">
                <div class="flex items-center gap-3">
                    <h2 class="text-lg tracking-wide" style="font-family:'Noto Serif JP', serif;">{{ $labels['photos'] }}</h2>
                    <div class="h-px flex-1 bg-slate-900/10"></div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach($place->galleryPhotos as $photo)
                        @php
                            $cap = $isJa ? ($photo->caption_ja ?: $photo->caption_en) : ($photo->caption_en ?: $photo->caption_ja);
                            $photoAlt = $cap ?: $labels['galleryPhotoAltFallback'];
                        @endphp

                        <button
                            type="button"
                            class="group rounded-2xl border border-slate-900/10 bg-white/60 shadow-sm overflow-hidden text-left hover:shadow-md transition"
                            data-gallery="place-gallery"
                            data-src="{{ url(\Illuminate\Support\Facades\Storage::url($photo->path)) }}"
                            data-caption="{{ $cap ?? '' }}"
                        >
                            <img
                                src="{{ \Illuminate\Support\Facades\Storage::url($photo->path) }}"
                                class="w-full aspect-square object-cover group-hover:scale-[1.02] transition"
                                alt="{{ $photoAlt }}"
                            >

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

    <div id="galleryModal" class="fixed inset-0 z-50 hidden" data-close="1">
        <div id="galleryOverlay" class="absolute inset-0 bg-black/70" data-close="1"></div>

        <div class="relative w-full h-full flex items-center justify-center p-4" data-close="1">
            <div class="relative max-w-5xl w-full" data-close="0">
                <button id="galleryClose"
                        class="absolute -top-10 right-0 text-white text-sm px-3 py-2 rounded-xl border border-white/20 bg-black/40 hover:bg-black/60 backdrop-blur">
                    {{ $labels['close'] }}
                </button>

                <div class="relative bg-black rounded overflow-hidden">
                    <img id="galleryImage" src="" alt="" class="w-full max-h-[80vh] object-contain bg-black">

                    <button id="galleryPrev"
                            class="absolute left-2 top-1/2 -translate-y-1/2 text-white bg-black/40 hover:bg-black/60 rounded-full w-10 h-10 flex items-center justify-center"
                            aria-label="{{ $labels['prevPhoto'] }}">
                        ←
                    </button>

                    <button id="galleryNext"
                            class="absolute right-2 top-1/2 -translate-y-1/2 text-white bg-black/40 hover:bg-black/60 rounded-full w-10 h-10 flex items-center justify-center"
                            aria-label="{{ $labels['nextPhoto'] }}">
                        →
                    </button>
                </div>

                <div id="galleryCaption" class="mt-3 text-white text-sm bg-black/40 rounded p-3"></div>
            </div>
        </div>
    </div>
</x-public-layout>