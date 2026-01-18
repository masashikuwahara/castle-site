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
    @php
        $logs = $changelogs ?? collect();
        $newBorder = now()->subDays(7);
    @endphp

    @if($logs->isNotEmpty())
        <section class="mt-10 border-t pt-6">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-semibold">更新履歴</h2>
            </div>

            <ul class="space-y-2 text-sm">
                @foreach($logs->take(10) as $log)
                    @php
                        $date = \Illuminate\Support\Carbon::parse($log['date']);
                        $isNew = $date->greaterThanOrEqualTo($newBorder);
                    @endphp

                    <li class="flex items-start gap-3">
                        <span class="text-gray-500 tabular-nums w-24 shrink-0">
                            {{ $date->format('Y-m-d') }}
                        </span>

                        <div class="flex-1">
                            @if(!empty($log['url']))
                                <a href="{{ $log['url'] }}" class="hover:underline">
                                    {{ $log['title_ja'] ?? '' }}
                                </a>
                            @else
                                <span>{{ $log['title_ja'] ?? '' }}</span>
                            @endif

                            @if($isNew)
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded bg-red-600 text-white text-xs whitespace-nowrap">
                                    NEW
                                </span>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif

</x-public-layout>
