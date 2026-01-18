<x-public-layout title="近くのスポット｜城・文化財" description="現在地から近い城・文化財を距離順に表示します。">
    <div class="space-y-4">

        <div class="flex items-end justify-between">
            <h1 class="text-xl font-bold">近くのスポット</h1>

            <form method="GET" action="{{ route('public.near') }}" class="flex items-center gap-2">
                <input type="hidden" name="lat" value="{{ $lat }}">
                <input type="hidden" name="lng" value="{{ $lng }}">

                <label class="text-sm text-gray-600">半径</label>
                <select name="r" class="rounded border-gray-300 text-sm">
                    @foreach([10,20,30,50,100] as $r)
                        <option value="{{ $r }}" @selected((int)$radiusKm === $r)>{{ $r }}km</option>
                    @endforeach
                </select>

                <button class="px-3 py-2 bg-gray-900 text-white rounded text-sm">更新</button>
            </form>
        </div>

        {{-- 位置情報取得 --}}
        <div class="bg-white rounded shadow p-4 space-y-2">
            <div class="text-sm text-gray-700">
                位置情報を許可すると、近い順に表示します。
            </div>

            <div class="flex flex-wrap gap-2 items-center">
                <button id="btnGetLocation" class="px-4 py-2 bg-gray-900 text-white rounded">
                    現在地から探す
                </button>

                <div id="locStatus" class="text-sm text-gray-600"></div>
            </div>

            <form method="GET" action="{{ route('public.near') }}" class="flex flex-wrap items-end gap-2 mt-3">
                <input type="hidden" name="r" value="{{ (int)$radiusKm }}">

                <div>
                    <label class="block text-xs text-gray-600 mb-1">位置情報が使えない場合はコチラ（都道府県起点）</label>
                    <select name="prefecture_id" class="rounded border-gray-300 text-sm">
                        <option value="">選択してください</option>
                        @foreach($prefectures as $p)
                            <option value="{{ $p->id }}">{{ $p->name_ja }}</option>
                        @endforeach
                    </select>
                </div>

                <button class="px-3 py-2 bg-gray-900 text-white rounded text-sm">
                    この都道府県から探す
                </button>
            </form>

            @if($lat && $lng)
                <div class="text-xs text-gray-500">
                    現在地：{{ $lat }}, {{ $lng }}
                </div>
            @endif
        </div>

        {{-- 結果 --}}
        @if($places)
            <div class="flex items-end justify-between">
                <div class="text-sm text-gray-600">{{ $places->total() }}件</div>
                <div class="text-sm text-gray-600">半径：{{ (int)$radiusKm }}km（概算）</div>
            </div>

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
                                {{ $place->prefecture?->name_ja }} / {{ $place->category?->name_ja }}
                            </div>
                            @php
                                // place側のlat/lngがあればそれ、無ければ住所
                                $mapQuery = (!is_null($place->lat) && !is_null($place->lng))
                                    ? ($place->lat . ',' . $place->lng)
                                    : ($place->address_ja ?? $place->name_ja);

                                $mapUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($mapQuery);
                            @endphp

                            <a href="{{ $mapUrl }}"
                            target="_blank" rel="noopener"
                            class="inline-block text-xs text-blue-600 hover:underline">
                                地図で開く
                            </a>
                            @if(isset($place->distance_km))
                                <div class="text-xs text-gray-700">
                                    距離：{{ number_format((float)$place->distance_km, 1) }} km
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>

            <div>{{ $places->links() }}</div>
        @else
            <div class="text-sm text-gray-600">
                まずは「現在地から探す」を押してください。
            </div>
        @endif
    </div>

    <script>
      (function () {
        const btn = document.getElementById('btnGetLocation');
        const status = document.getElementById('locStatus');

        function setStatus(msg) {
          if (status) status.textContent = msg;
        }

        btn?.addEventListener('click', function () {
          if (!navigator.geolocation) {
            setStatus('このブラウザは位置情報に対応していません。');
            return;
          }

          setStatus('位置情報を取得中…（許可が必要です）');

          navigator.geolocation.getCurrentPosition(
            function (pos) {
              const lat = pos.coords.latitude;
              const lng = pos.coords.longitude;
              const r = new URLSearchParams(window.location.search).get('r') || '{{ (int)$radiusKm }}';
              const url = new URL('{{ route('public.near') }}', window.location.origin);
              url.searchParams.set('lat', lat);
              url.searchParams.set('lng', lng);
              url.searchParams.set('r', r);
              window.location.href = url.toString();
            },
            function (err) {
              // 1: PERMISSION_DENIED / 2: POSITION_UNAVAILABLE / 3: TIMEOUT
              if (err.code === 1) setStatus('位置情報が拒否されました。ブラウザ設定で許可してください。');
              else if (err.code === 2) setStatus('位置情報を取得できませんでした。');
              else if (err.code === 3) setStatus('タイムアウトしました。もう一度お試しください。');
              else setStatus('位置情報の取得に失敗しました。');
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 30000 }
          );
        });
      })();
    </script>
</x-public-layout>
