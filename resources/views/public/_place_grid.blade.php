<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    @foreach($places as $place)
        <a href="{{ route('public.places.show', $place) }}"
           class="group rounded-2xl border border-slate-900/10 bg-white/60 shadow-sm hover:shadow-md
                  transition overflow-hidden">
            <div class="aspect-square bg-slate-100 overflow-hidden">
                @if($place->thumbnailPhoto)
                    <img src="{{ Storage::url($place->thumbnailPhoto->path) }}"
                         class="w-full h-full object-cover group-hover:scale-[1.02] transition"
                         alt="">
                @else
                    <div class="w-full h-full flex items-center justify-center text-xs text-slate-500">
                        no image
                    </div>
                @endif
            </div>

            <div class="p-4 space-y-2">
                {{-- タイトル（明朝寄せ） --}}
                <div class="text-sm leading-snug line-clamp-2 tracking-wide"
                     style="font-family:'Noto Serif JP', serif;">
                    {{ $place->name_ja }}
                </div>

                {{-- 都道府県 / カテゴリ（札のメタ情報） --}}
                <div class="text-xs text-slate-600 flex flex-wrap gap-2">
                    @if($place->prefecture?->name_ja)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full border border-slate-900/10 bg-[#fbfaf7]">
                            {{ $place->prefecture->name_ja }}
                        </span>
                    @endif
                    @if($place->category?->name_ja)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full border border-slate-900/10 bg-white/70">
                            {{ $place->category->name_ja }}
                        </span>
                    @endif
                </div>

                {{-- おすすめ（朱のスタンプ感） --}}
                @if(!empty($place->rating))
                    <div class="text-xs inline-flex items-center gap-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md border border-[#c2412d]/25 bg-[#fbfaf7] text-[#a83626] text-xs shadow-sm">
                            おすすめ
                        </span>
                        <span class="text-slate-700 tabular-nums">
                            {{ str_repeat('★', (int)$place->rating) }}
                        </span>
                    </div>
                @endif

                {{-- 概要 --}}
                @if(!empty($place->short_desc_ja))
                    <div class="text-[11px] text-slate-600/90 line-clamp-2 leading-relaxed">
                        {{ $place->short_desc_ja }}
                    </div>
                @endif

                {{-- タグ（藍/苔の薄色チップ） --}}
                @if($place->tags?->isNotEmpty())
                    <div class="flex flex-wrap gap-1.5 pt-1">
                        @foreach($place->tags->take(3) as $tag)
                            <span class="text-[11px] px-2 py-0.5 rounded-full
                                         bg-slate-900/5 text-slate-600">
                                #{{ $tag->name_ja }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
        </a>
    @endforeach
</div>
