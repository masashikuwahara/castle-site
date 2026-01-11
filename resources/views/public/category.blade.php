<x-public-layout :title="$category->name_ja">
    <div class="space-y-4">
        <div class="flex items-end justify-between">
            <h1 class="text-xl font-bold">{{ $category->name_ja }}</h1>
            <div class="text-sm text-gray-500">{{ $places->total() }}件</div>
        </div>

        @include('public._place_grid', ['places' => $places])

        <div>{{ $places->links() }}</div>
    </div>
</x-public-layout>
