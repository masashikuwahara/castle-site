<x-public-layout
    title="城・文化財"
    description="訪れた城・文化財を写真とメモで紹介する記録サイト。日本100名城・続100名城・その他の城・文化財を掲載。">

    <div class="space-y-10">

        {{-- Hero + Menu 統合 --}}
        <section class="rounded-2xl border border-slate-900/10 bg-white/60 shadow-sm overflow-hidden">
            <div class="p-6 md:p-8">
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl md:text-3xl tracking-wide"
                        style="font-family:'Noto Serif JP', serif;">
                        Daytripper｜城・文化財の記録
                    </h1>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs
                                 border border-slate-900/10 bg-[#fbfaf7] text-slate-700">
                        ベータ版
                    </span>
                </div>

                <p class="mt-3 text-slate-700 leading-relaxed">
                    写真つきで、見どころ・アクセス・周辺散策をまとめています。上部の検索から城名やタグで探せます。
                </p>

                {{-- まず押してほしい導線（メニュー） --}}
                <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-3">
                    <a href="{{ route('public.about') }}"
                       class="p-4 rounded-xl border border-slate-900/10 bg-white/70 hover:bg-white shadow-sm">
                        <div class="text-sm font-medium">初めて訪れる人へ</div>
                        <div class="text-xs text-slate-600 mt-1">使い方・見方</div>
                    </a>

                    {{-- ここは将来機能の枠 --}}
                    <div class="p-4 rounded-xl border border-slate-900/10 bg-white/40 text-slate-500">
                        <div class="text-sm font-medium">Coming Soon</div>
                        <div class="text-xs mt-1">近くのスポット など</div>
                    </div>

                    @foreach($categories as $category)
                        <a href="{{ route('public.categories.show', $category) }}"
                           class="p-4 rounded-xl border border-slate-900/10 bg-white/70 hover:bg-white shadow-sm">
                            <div class="text-sm font-medium">{{ $category->name_ja }}</div>
                            <div class="text-xs text-slate-600 mt-1">カテゴリ</div>
                        </a>
                    @endforeach
                </div>

                {{-- 小さめの補助導線（検索を促す） --}}
                <div class="mt-5 text-xs text-slate-600 flex flex-wrap gap-x-5 gap-y-2">
                    <span>・検索：城名 / 地域 / タグ</span>
                    <span>・写真つき</span>
                    <span>・日本100名城 / 続100名城 / 城跡</span>
                </div>
            </div>

            <div class="h-px bg-slate-900/10"></div>

            {{-- ちょい和風な飾り（任意） --}}
            <div class="px-6 md:px-8 py-3 text-xs text-slate-600 bg-[#fbfaf7]/60">
                「旅の手帖」みたいに、少しずつ積み上げていきます。
            </div>
        </section>

        {{-- 最近追加 --}}
        <section class="space-y-4">
            <div class="flex items-center gap-3">
                <h2 class="text-lg tracking-wide" style="font-family:'Noto Serif JP', serif;">最近追加した公開コンテンツ</h2>
                <div class="h-px flex-1 bg-slate-900/10"></div>
            </div>
            @include('public._place_grid', ['places' => $latestPlaces])
        </section>

        {{-- 更新履歴（元のままでもOK。カード化だけ） --}}
        @php
            $logs = $changelogs ?? collect();
            $newBorder = now()->subDays(7);
        @endphp

        @if($logs->isNotEmpty())
            <section class="rounded-2xl border border-slate-900/10 bg-white/60 shadow-sm p-5 md:p-6">
                <div class="flex items-center gap-3 mb-4">
                    <h2 class="text-lg tracking-wide" style="font-family:'Noto Serif JP', serif;">更新履歴</h2>
                    <div class="h-px flex-1 bg-slate-900/10"></div>
                </div>

                <ul class="space-y-3 text-sm">
                    @foreach($logs->take(10) as $log)
                        @php
                            $date = \Illuminate\Support\Carbon::parse($log['date']);
                            $isNew = $date->greaterThanOrEqualTo($newBorder);
                        @endphp

                        <li class="flex items-start gap-3">
                            <span class="text-slate-500 tabular-nums w-24 shrink-0">
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
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-md
                                                 border border-[#c2412d]/25 bg-[#fbfaf7] text-[#a83626] text-xs
                                                 rotate-[-2deg]">
                                        NEW
                                    </span>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

    </div>

</x-public-layout>
