<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Places</h2>
            <a href="{{ route('admin.places.create') }}"
               class="px-4 py-2 bg-gray-900 text-white rounded">新規作成</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('status'))
                <div class="p-3 bg-green-100 rounded">{{ session('status') }}</div>
            @endif

            <form method="GET" class="flex gap-2">
                <input name="q" value="{{ request('q') }}"
                       class="w-full rounded border-gray-300"
                       placeholder="検索（名前/slug/かな/短文）">
                <button class="px-4 py-2 bg-gray-800 text-white rounded">検索</button>
            </form>

            <div class="bg-white shadow rounded">
                <table class="w-full text-sm">
                    <thead class="border-b">
                        <tr class="text-left">
                            <th class="p-3">サムネ</th>
                            <th class="p-3">名称</th>
                            <th class="p-3">カテゴリ</th>
                            <th class="p-3">都道府県</th>
                            <th class="p-3">公開</th>
                            <th class="p-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($places as $place)
                            <tr class="border-b">
                                <td class="p-3">
                                    @if ($place->thumbnailPhoto)
                                        <img src="{{ Storage::url($place->thumbnailPhoto->path) }}"
                                             class="w-16 h-16 object-cover rounded">
                                    @else
                                        <div class="w-16 h-16 bg-gray-100 rounded flex items-center justify-center text-xs text-gray-500">
                                            no image
                                        </div>
                                    @endif
                                </td>
                                <td class="p-3">
                                    <div class="font-semibold">{{ $place->name_ja }}</div>
                                    <div class="text-gray-500">{{ $place->slug }}</div>
                                    <div class="text-gray-400 text-xs">
                                        @foreach($place->tags as $tag)
                                            <span class="mr-1">#{{ $tag->name_ja }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="p-3">{{ $place->category?->name_ja }}</td>
                                <td class="p-3">{{ $place->prefecture?->name_ja }}</td>
                                <td class="p-3">
                                    @if($place->is_published)
                                        <span class="px-2 py-1 bg-green-100 rounded">公開</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 rounded">非公開</span>
                                    @endif
                                </td>
                                <td class="p-3 text-right">
                                    <a class="text-blue-600" href="{{ route('admin.places.edit', $place) }}">編集</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $places->links() }}
        </div>
    </div>
</x-app-layout>
