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

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&family=Noto+Serif+JP:wght@600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if(!empty($jsonLd))
        {!! $jsonLd !!}
    @endif

</head>
<body class="bg-washi text-slate-900 antialiased"
      style="font-family: 'Noto Sans JP', ui-sans-serif, system-ui;">

<header class="sticky top-0 z-40 backdrop-blur bg-[#fbfaf7]/85 border-b border-slate-900/10">
    <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
        <a href="{{ route('public.home') }}" class="flex items-center gap-3">
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-900/15 bg-white/60 shadow-sm">
                <span class="text-sm" style="font-family:'Noto Serif JP', serif;">旅</span>
            </span>
            <div class="leading-tight">
                <div class="text-lg tracking-wide" style="font-family:'Noto Serif JP', serif;">Daytripper</div>
                <div class="text-xs text-slate-600">城・城跡の日帰り旅行ガイド</div>
            </div>
        </a>

        <form action="{{ route('public.search') }}" method="GET"
              class="flex gap-2 w-full max-w-md">
            <input name="q" value="{{ request('q') }}"
                class="w-full rounded-xl border border-slate-900/15 bg-white/70 px-3 py-2
                       placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/15"
                placeholder="城名・地域・タグなど">
            <button class="px-4 py-2 rounded-xl whitespace-nowrap shrink-0 text-white
                           bg-[#233d5d] hover:bg-[#2b4a6f] shadow-sm">
                検索
            </button>
        </form>
    </div>
</header>

<main class="max-w-6xl mx-auto px-4 py-8">
    {{ $slot }}
</main>

<footer class="border-t border-slate-900/10 bg-[#fbfaf7]">
    <div class="max-w-6xl mx-auto px-4 py-8 text-sm text-slate-600 flex items-center justify-between">
        <span>© {{ date('Y') }} Daytripper</span>
    </div>
    <!-- version.1.1.0 -->
</footer>

</body>
</html>
