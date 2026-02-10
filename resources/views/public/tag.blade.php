<x-public-layout
    :title="'#'.$tag->name_ja . '｜城・文化財'"
    :description="'タグ「#'.$tag->name_ja.'」の一覧ページ。'">

    <div class="space-y-4">
        <div class="flex items-end justify-between">
            <h1 class="text-xl font-bold">#{{ $tag->name_ja }}</h1>
            <div class="text-sm text-gray-500">{{ $places->total() }}件</div>
        </div>

        @include('public._place_grid', ['places' => $places])

        <div>{{ $places->links() }}</div>
    </div>
</x-public-layout>
