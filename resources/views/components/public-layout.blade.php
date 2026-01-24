<!DOCTYPE html>
<html lang="ja">
<head>
    {{-- @php
        $metaTitle = $title ?? '城・文化財';
        $metaDescription = $description ?? '訪れた城・文化財を写真とメモで紹介する記録サイト。';
        $metaUrl = $ogUrl ?? request()->fullUrl();
        $metaImage = $ogImage ?? asset('images/ogp-default.png'); // とりあえずのデフォルト
    @endphp

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">

    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $metaUrl }}">
    <meta property="og:image" content="{{ $metaImage }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $metaImage }}">
    @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    @php
        $locale = app()->getLocale(); // 'ja' or 'en'
        $siteName = 'Daytripper';
        $defaultTitle = $locale === 'ja'
            ? 'Daytripper｜城・城跡の日帰り旅行ガイド'
            : 'Daytripper | Day-trip guide to Japanese castles';
        $defaultDesc = $locale === 'ja'
            ? '日本各地の城・城跡を写真付きで紹介。見どころ、アクセス、周辺散策、タグ検索、現在地から近い城も。'
            : 'Explore Japanese castles and ruins with photos. Highlights, access, tags, and nearby places from your location.';
    @endphp

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? $defaultTitle }}</title>
    <meta name="description" content="{{ $description ?? $defaultDesc }}">

    <link rel="canonical" href="{{ $canonical ?? url()->current() }}">

    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $ogTitle ?? ($title ?? $defaultTitle) }}">
    <meta property="og:description" content="{{ $ogDescription ?? ($description ?? $defaultDesc) }}">
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:url" content="{{ $ogUrl ?? ($canonical ?? url()->current()) }}">
    @if(!empty($ogImage))
        <meta property="og:image" content="{{ $ogImage }}">
    @endif

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $twitterTitle ?? ($title ?? $defaultTitle) }}">
    <meta name="twitter:description" content="{{ $twitterDescription ?? ($description ?? $defaultDesc) }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if(!empty($ogImage))
        <meta name="twitter:image" content="{{ $ogImage }}">
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
