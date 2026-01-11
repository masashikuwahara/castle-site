<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    @foreach($places as $place)
        <a href="{{ route('public.places.show', $place) }}"
           class="bg-white rounded shadow hover:shadow-md transition overflow-hidden">
            <div class="aspect-square bg-gray-100">
                @if($place->thumbnailPhoto)
                    <img src="{{ Storage::url($place->thumbnailPhoto->path) }}"
                         class="w-full h-full object-cover" alt="">
                @else
                    <div class="w-full h-full flex items-center justify-center text-xs text-gray-500">no image</div>
                @endif
            </div>

            <div class="p-3 space-y-1">
                <div class="text-sm font-semibold line-clamp-2">{{ $place->name_ja }}</div>
                <div class="text-xs text-gray-500">
                    {{ $place->prefecture?->name_ja }}
                    / {{ $place->category?->name_ja }}
                </div>

                @if(!empty($place->rating))
                    <div class="text-xs text-gray-700">おすすめ：{{ str_repeat('★', (int)$place->rating) }}</div>
                @endif

                <div class="text-[11px] text-gray-400 line-clamp-2">
                    {{ $place->short_desc_ja }}
                </div>

                <div class="text-[11px] text-gray-400">
                    @foreach($place->tags->take(3) as $tag)
                        <span class="mr-1">#{{ $tag->name_ja }}</span>
                    @endforeach
                </div>
            </div>
        </a>
    @endforeach
</div>
