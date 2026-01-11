<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? '城・文化財' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900">
<header class="border-b bg-white">
    <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between gap-4">
        <a href="{{ route('public.home') }}" class="font-bold text-lg">城・文化財</a>

        <form action="{{ route('public.search') }}" method="GET" class="flex gap-2 w-full max-w-md">
            <input name="q" value="{{ request('q') }}"
                   class="w-full rounded border-gray-300"
                   placeholder="検索（名前・slug など）">
            <button class="px-4 py-2 bg-gray-900 text-white rounded">検索</button>
        </form>

        <a href="{{ route('admin.places.index') }}" class="text-sm text-gray-600 hover:underline">
            管理
        </a>
    </div>
</header>

<main class="max-w-6xl mx-auto px-4 py-6">
    {{ $slot }}
</main>

<footer class="border-t bg-white">
    <div class="max-w-6xl mx-auto px-4 py-6 text-sm text-gray-500">
        © {{ date('Y') }} 城・文化財
    </div>
</footer>
</body>
</html>
