<x-public-layout
    :title="$category->name_ja . '｜城・文化財'"
    :description="$category->name_ja . 'の一覧ページ。写真中心のグリッドで紹介します。'">

    <div class="space-y-4">

        @include('public._breadcrumb', [
          'items' => [
            ['label' => 'トップ', 'url' => route('public.home')],
            ['label' => $category->name_ja, 'url' => null],
          ]
        ])

        <div class="flex items-end justify-between">
            <h1 class="text-xl md:text-2xl tracking-wide"
                style="font-family:'Noto Serif JP', serif;">
                {{ $category->name_ja }}
            </h1>
            <div class="text-sm text-slate-600">{{ $places->total() }}件</div>
        </div>

        @include('public._place_grid', ['places' => $places])

        <div class="pt-2">{{ $places->links() }}</div>
    </div>
</x-public-layout>
