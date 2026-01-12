@php
    $ogImage = $place->thumbnailPhoto ? Storage::url($place->thumbnailPhoto->path) : asset('images/ogp-default.png');
    // Storage::url は /storage/... なので、og:image には絶対URLが理想（SNSが読めないことがある）
    // より確実にするなら url() で絶対URL化
    $ogImage = url($ogImage);

    $desc = $place->short_desc_ja
        ?: ($place->description_ja ? mb_strimwidth(strip_tags($place->description_ja), 0, 120, '…') : '詳細ページ');
@endphp

<x-public-layout
    :title="$place->name_ja . '｜城・文化財'"
    :description="$desc"
    :ogImage="$ogImage"
    :ogUrl="route('public.places.show', $place)">

    <div class="space-y-6">
        <div class="space-y-2">
            <div class="text-sm text-gray-500">
                <a class="hover:underline" href="{{ route('public.categories.show', $place->category) }}">{{ $place->category?->name_ja }}</a>
                /
                {{ $place->prefecture?->name_ja }}
            </div>
            <h1 class="text-2xl font-bold">{{ $place->name_ja }}</h1>
            @if($place->name_en)
                <div class="text-gray-600">{{ $place->name_en }}</div>
            @endif

            <div class="flex flex-wrap gap-2">
                @foreach($place->tags as $tag)
                    <a href="{{ route('public.tags.show', $tag) }}" class="px-3 py-1 bg-gray-100 rounded hover:bg-gray-200 text-sm">
                        #{{ $tag->name_ja }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- サムネ --}}
        @if($place->thumbnailPhoto)
            <div class="bg-white rounded shadow overflow-hidden">
                <img src="{{ Storage::url($place->thumbnailPhoto->path) }}" class="w-full max-h-[520px] object-cover" alt="">
                @if($place->thumbnailPhoto->caption_ja)
                    <div class="p-3 text-sm text-gray-600">{{ $place->thumbnailPhoto->caption_ja }}</div>
                @endif
            </div>
        @endif

        {{-- 基本情報 --}}
        <div class="bg-white rounded shadow p-5">
            <h2 class="text-lg font-bold mb-4">基本情報</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                <div><span class="text-gray-500">城郭構造：</span>{{ $place->castle_style_ja }}</div>
                <div><span class="text-gray-500">天守構造：</span>{{ $place->tenshu_style_ja }}</div>
                <div><span class="text-gray-500">築城主：</span>{{ $place->builder_ja }}</div>
                <div><span class="text-gray-500">築城年：</span>{{ $place->built_year }}</div>
                <div><span class="text-gray-500">廃城年：</span>{{ $place->abolished_year }}</div>
                <div><span class="text-gray-500">主な改修者：</span>{{ $place->renovator_ja }}</div>
                <div class="md:col-span-2"><span class="text-gray-500">主な城主：</span>{!! nl2br(e($place->main_lords_ja)) !!}</div>
                <div class="md:col-span-2"><span class="text-gray-500">指定文化財：</span>{{ $place->heritage_designation_ja }}</div>
                <div class="md:col-span-2"><span class="text-gray-500">遺構：</span>{!! nl2br(e($place->remains_ja)) !!}</div>
                <div><span class="text-gray-500">おすすめ度：</span>{{ $place->rating ? str_repeat('★', (int)$place->rating) : '' }}</div>
                <div><span class="text-gray-500">住所：</span>{{ $place->address_ja }}</div>
            </div>
        </div>

        {{-- 概要 --}}
        @if($place->description_ja)
            <div class="bg-white rounded shadow p-5">
                <h2 class="text-lg font-bold mb-4">概要</h2>
                <div class="prose max-w-none">
                    {!! nl2br(e($place->description_ja)) !!}
                </div>
            </div>
        @endif

        {{-- Map --}}
        <div class="bg-white rounded shadow p-5 space-y-3">
            <h2 class="text-lg font-bold">地図</h2>
            @php
                $mapQuery = null;
                if (!is_null($place->lat) && !is_null($place->lng)) {
                    $mapQuery = $place->lat.','.$place->lng;
                } elseif ($place->address_ja) {
                    $mapQuery = $place->address_ja;
                }
            @endphp

            @if($mapQuery)
                <iframe
                    class="w-full h-[360px] rounded"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    src="https://www.google.com/maps?q={{ urlencode($mapQuery) }}&output=embed">
                </iframe>
            @else
                <div class="text-sm text-gray-500">地図を表示するには住所または緯度経度を登録してください。</div>
            @endif
        </div>

        {{-- 写真ギャラリー（登録している場合） --}}
        @if($place->galleryPhotos->count() > 0)
            <div class="space-y-3">
                <h2 class="text-lg font-bold">写真</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach($place->galleryPhotos as $photo)
                        <div class="bg-white rounded shadow overflow-hidden">
                            <img src="{{ Storage::url($photo->path) }}" class="w-full aspect-square object-cover" alt="">
                            @if($photo->caption_ja)
                                <div class="p-2 text-xs text-gray-600">{{ $photo->caption_ja }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-public-layout>
