<x-public-layout
  title="検索結果：{{ $q }} | Daytripper"
  robots="noindex,follow"
>

    <div class="space-y-4">
        <div class="flex items-end justify-between">
            <h1 class="text-xl font-bold">検索</h1>
        </div>
        @if(!empty($alert))
    <div class="mb-4 rounded border border-amber-200 bg-amber-50 px-4 py-3 text-amber-800 text-sm">
        {{ $alert }}
    </div>
    @endif

    @if(is_null($places))
        <div class="text-sm text-gray-500">
            キーワードを入力して検索してください。
        </div>
    @else
        @if($places->total() === 0)
            <div class="mb-4 rounded border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700">
                「{{ $q }}」では見つかりませんでした。
            </div>
        @endif

        <div class="text-sm text-gray-500">{{ $places->total() }}件</div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach($places as $place)
            @endforeach
        </div>
        @include('public._place_grid', ['places' => $places])
        <div class="mt-6">
            {{ $places->links() }}
        </div>
    @endif

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

    </div>
</x-public-layout>
