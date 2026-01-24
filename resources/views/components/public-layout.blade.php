<!DOCTYPE html>
<html lang="ja">
<head>
    @php
        $locale = app()->getLocale();
        $siteName = 'Daytripper';

        $defaultTitle = $locale === 'ja'
            ? 'Daytripper｜城・城跡の日帰り旅行ガイド'
            : 'Daytripper | Day-trip guide to Japanese castles';

        $defaultDesc = $locale === 'ja'
            ? '日本各地の城・城跡を写真付きで紹介。見どころ、アクセス、周辺散策、タグ検索、現在地から近い城も。'
            : 'Explore Japanese castles and ruins with photos. Highlights, access, tags, and nearby places from your location.';

        $metaTitle = $title ?? $defaultTitle;
        $metaDesc  = $description ?? $defaultDesc;

        $metaCanonical = $canonical ?? url()->current();
        $metaOgUrl = $ogUrl ?? $metaCanonical;

        // ★重要：ogImage は “絶対URL” 推奨
        $metaOgImage = $ogImage ?? url(asset('images/ogp-default.png'));

        $metaOgTitle = $ogTitle ?? $metaTitle;
        $metaOgDesc  = $ogDescription ?? $metaDesc;

        $metaTwitterTitle = $twitterTitle ?? $metaTitle;
        $metaTwitterDesc  = $twitterDescription ?? $metaDesc;
        $metaTwitterImage = $twitterImage ?? $metaOgImage;
    @endphp

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDesc }}">

    @if(!empty($robots))
        <meta name="robots" content="{{ $robots }}">
    @endif

    <link rel="canonical" href="{{ $metaCanonical }}">

    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $metaOgTitle }}">
    <meta property="og:description" content="{{ $metaOgDesc }}">
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:url" content="{{ $metaOgUrl }}">
    <meta property="og:image" content="{{ $metaOgImage }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTwitterTitle }}">
    <meta name="twitter:description" content="{{ $metaTwitterDesc }}">
    <meta name="twitter:image" content="{{ $metaTwitterImage }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if(!empty($jsonLd))
        {!! $jsonLd !!}
    @endif

</head>
<body class="bg-gray-50 text-gray-900">
<header class="border-b bg-white">
    <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between gap-4">
        <a href="{{ route('public.home') }}" class="font-bold text-lg">Daytripper</a>
        <form action="{{ route('public.search') }}" method="GET" class="flex gap-2 w-full max-w-md">
            <input name="q" value="{{ request('q') }}"
                class="w-full rounded border-gray-300"
                placeholder="城名などを入力">
            <button class="px-4 py-2 bg-gray-900 text-white rounded whitespace-nowrap shrink-0">
                検索
            </button>
        </form>
    </div>
</header>

<main class="max-w-6xl mx-auto px-4 py-6">
    {{ $slot }}
</main>

<footer class="border-t bg-white">
    <div class="max-w-6xl mx-auto px-4 py-6 text-sm text-gray-500">
        © {{ date('Y') }} Daytripper
    </div>
    {{-- version.β1.0.0 --}}
</footer>
</body>
</html>
