<x-public-layout
    title="城・文化財"
    description="訪れた城・文化財を写真とメモで紹介する記録サイト。日本100名城・続100名城・その他の城・文化財を掲載。">

    <div class="space-y-8">

        <section class="bg-white rounded shadow p-5">
            <h1 class="text-xl font-bold mb-3">メニュー</h1>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <a href="{{ route('public.about') }}" class="p-4 border rounded hover:bg-gray-50">
                    初めて訪れる人へ
                </a>
                {{-- <a href="{{ route('public.near') }}" class="p-4 border rounded hover:bg-gray-50">
                    近くのスポット
                </a> --}}
                @foreach($categories as $category)
                    <a href="{{ route('public.categories.show', $category) }}" class="p-4 border rounded hover:bg-gray-50">
                        {{ $category->name_ja }}
                    </a>
                @endforeach
            </div>
        </section>
        <section>
            <div class="flex items-end justify-between mb-3">
                <h2 class="text-lg font-bold">最近追加した公開コンテンツ</h2>
                <a class="text-sm text-blue-600 hover:underline" href="{{ route('public.search') }}">全部見る</a>
            </div>
            @include('public._place_grid', ['places' => $latestPlaces])
        </section>

    </div>
</x-public-layout>
