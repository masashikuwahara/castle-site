<x-public-layout
    title="検索｜城・文化財"
    :description="$q ? '「'.$q.'」の検索結果ページ。' : 'キーワード検索ページ。'">

    <div class="space-y-4">
        <div class="flex items-end justify-between">
            <h1 class="text-xl font-bold">検索</h1>
            <div class="text-sm text-gray-500">{{ $places->total() }}件</div>
        </div>

        @if($q !== '' && count($tags) > 0)
            <div class="bg-white rounded shadow p-4">
                <div class="text-sm font-semibold mb-2">タグ候補</div>
                <div class="flex flex-wrap gap-2">
                    @foreach($tags as $tag)
                        <a href="{{ route('public.tags.show', $tag) }}"
                           class="px-3 py-1 bg-gray-100 rounded hover:bg-gray-200 text-sm">
                            #{{ $tag->name_ja }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @include('public._place_grid', ['places' => $places])

        <div>{{ $places->links() }}</div>
    </div>
</x-public-layout>
