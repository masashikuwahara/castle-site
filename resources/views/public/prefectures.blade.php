@php
  // $isJa = app()->getLocale() === 'ja';
  $locale = app()->getLocale();
  $isJa = str_starts_with($locale, 'ja');

  // prefectures: id, slug, name_ja, name_en, places_count
  $prefList = $prefectures->map(fn($p) => [
    'id' => $p->id,
    'slug' => $p->slug,
    'name' => $isJa ? $p->name_ja : ($p->name_en ?? $p->name_ja),
    'count' => (int)($p->places_count ?? 0),
  ])->values();

  // places: groupBy(prefecture_id)
  // place fields: slug, name_ja/en, short_desc_ja/en, rating
  $placeMap = [];
  foreach ($prefectures as $p) {
    $items = $places[$p->id] ?? collect();
    $placeMap[$p->id] = collect($items)->map(fn($pl) => [
      'slug' => $pl->slug,
      'name' => $isJa ? $pl->name_ja : ($pl->name_en ?? $pl->name_ja),
      'desc' => $isJa ? ($pl->short_desc_ja ?? '') : ($pl->short_desc_en ?? ''),
      'rating' => (int)($pl->rating ?? 0),
      'url' => route('public.places.show', ['place' => $pl->slug]),
    ])->values();
  }

  // 初期選択：城がある都道府県の先頭、なければ先頭
  $defaultPrefId = $prefList->firstWhere('count', '>', 0)['id'] ?? ($prefList->first()['id'] ?? null);
@endphp

<x-public-layout>
  <div
    x-data="prefExplorer({
      prefectures: @js($prefList),
      placesByPref: @js($placeMap),
      defaultPrefId: @js($defaultPrefId),
      isJa: @js($isJa),
    })"
    class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8"
  >
    <div class="mb-6">
      <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight">
        {{ $isJa ? '都道府県から探す' : 'Browse by Prefecture' }}
      </h1>
      <p class="mt-2 text-sm text-slate-600">
        {{ $isJa
          ? 'PC版では左の都道府県をクリックすると右側に城一覧が表示されます。モバイル版では都道府県をタップして展開します。'
          : 'On desktop, click a prefecture to see the list. On mobile, tap a prefecture to expand.' }}
      </p>
    </div>

    {{-- PC: 2カラム（左=地図/選択、右=城リスト） --}}
    <div class="hidden lg:grid grid-cols-12 gap-6">
      {{-- 左：地図（最短=クリック可能な都道府県ボタン一覧） --}}
      <div class="col-span-5">
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
          <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between">
            <div class="text-sm font-medium text-slate-800">
              {{ $isJa ? '都道府県を選択' : 'Select a prefecture' }}
            </div>
            {{-- <a
              class="text-xs text-slate-600 hover:text-slate-900 underline"
              :href="selectedPref ? prefectureShowUrl(selectedPref.slug) : '#'"
            >
              {{ $isJa ? '都道府県ページへ' : 'Open prefecture page' }}
            </a> --}}
          </div>

          {{-- ここを将来的にSVG日本地図に差し替え可能 --}}
          <div class="p-4">
            <div class="grid grid-cols-2 gap-2">
              <template x-for="p in prefectures" :key="p.id">
                <button
                  type="button"
                  class="text-left rounded-xl border px-3 py-2 transition
                         hover:bg-slate-50"
                  :class="selectedPrefId === p.id
                    ? 'border-slate-900 bg-slate-50'
                    : 'border-slate-200 bg-white'"
                  @click="selectPref(p.id)"
                >
                  <div class="flex items-center justify-between gap-2">
                    <div class="text-sm font-medium text-slate-900" x-text="p.name"></div>
                    <div class="text-xs text-slate-600" x-text="p.count"></div>
                  </div>
                  <div class="mt-1 text-[11px] text-slate-500">
                    {{ $isJa ? 'クリックで右側に一覧表示' : 'Click to show the list' }}
                  </div>
                </button>
              </template>
            </div>

            <div class="mt-4 text-xs text-slate-500">
              {{ $isJa
                ? '※最短実装としてボタン型の簡易地図です。後でSVG地図に差し替えできます。'
                : '* This is a minimal clickable list. You can replace it with an SVG map later.' }}
            </div>
          </div>
        </div>
      </div>

      {{-- 右：城リスト --}}
      <div class="col-span-7">
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
          <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between">
            <div>
              <div class="text-sm text-slate-600">
                {{ $isJa ? '選択中' : 'Selected' }}
              </div>
              <div class="text-lg font-semibold text-slate-900" x-text="selectedPref?.name ?? ''"></div>
            </div>
            <div class="text-sm text-slate-600">
              <span x-text="selectedPref?.count ?? 0"></span>
              <span>{{ $isJa ? '件' : 'places' }}</span>
            </div>
          </div>

          <div class="p-4">
            <template x-if="(currentPlaces?.length ?? 0) === 0">
              <div class="text-sm text-slate-600">
                {{ $isJa ? '公開中の城データがまだありません。' : 'No published places yet.' }}
              </div>
            </template>

            <div class="space-y-3" x-show="(currentPlaces?.length ?? 0) > 0">
              <template x-for="pl in currentPlaces" :key="pl.slug">
                <a
                  class="block rounded-xl border border-slate-200 bg-white p-4 hover:bg-slate-50 transition"
                  :href="pl.url"
                >
                  <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                      <div class="font-semibold text-slate-900 truncate" x-text="pl.name"></div>
                      <div class="mt-1 text-sm text-slate-600 line-clamp-2" x-text="pl.desc"></div>
                    </div>
                    <div class="shrink-0 text-xs text-slate-500">
                      <span class="inline-flex items-center rounded-full border border-slate-200 px-2 py-1">
                        ★ <span class="ml-1" x-text="pl.rating"></span>
                      </span>
                    </div>
                  </div>
                </a>
              </template>

              <div class="pt-2">
                {{-- <a
                  class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm hover:bg-slate-50 transition"
                  :href="selectedPref ? prefectureShowUrl(selectedPref.slug) : '#'"
                >
                  {{ $isJa ? 'この都道府県の一覧ページへ' : 'Open prefecture page' }}
                  <span class="ml-2">→</span>
                </a> --}}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Mobile: アコーディオン --}}
    <div class="lg:hidden space-y-3">
      <template x-for="p in prefectures" :key="p.id">
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
          <button
            type="button"
            class="w-full px-4 py-4 flex items-center justify-between"
            @click="toggleAccordion(p.id)"
          >
            <div class="text-left">
              <div class="font-semibold text-slate-900" x-text="p.name"></div>
              <div class="mt-1 text-xs text-slate-500">
                <span x-text="p.count"></span>
                <span>{{ $isJa ? '件' : 'places' }}</span>
              </div>
            </div>

            <div class="text-slate-600">
              <svg class="w-5 h-5 transition" :class="openPrefId === p.id ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
              </svg>
            </div>
          </button>

          <div x-show="openPrefId === p.id" x-collapse>
            <div class="px-4 pb-4">
              <template x-if="(placesByPref[p.id]?.length ?? 0) === 0">
                <div class="text-sm text-slate-600">
                  {{ $isJa ? '公開中の城データがまだありません。' : 'No published places yet.' }}
                </div>
              </template>

              <div class="space-y-2" x-show="(placesByPref[p.id]?.length ?? 0) > 0">
                <template x-for="pl in placesByPref[p.id]" :key="pl.slug">
                  <a
                    class="block rounded-xl border border-slate-200 bg-white p-3 hover:bg-slate-50 transition"
                    :href="pl.url"
                  >
                    <div class="font-semibold text-slate-900" x-text="pl.name"></div>
                    <div class="mt-1 text-sm text-slate-600 line-clamp-2" x-text="pl.desc"></div>
                  </a>
                </template>
                {{-- <a
                  class="mt-2 inline-flex items-center text-sm underline text-slate-700 hover:text-slate-900"
                  :href="prefectureShowUrl(p.slug)"
                >
                  {{ $isJa ? 'この都道府県の一覧ページへ' : 'Open prefecture page' }} →
                </a> --}}
              </div>
            </div>
          </div>
        </div>
      </template>
    </div>

    {{-- Alpineロジック --}}
    <script>
      function prefExplorer({ prefectures, placesByPref, defaultPrefId, isJa }) {
        return {
          prefectures,
          placesByPref,
          isJa,
          selectedPrefId: defaultPrefId,
          openPrefId: null,

          init() {
            // mobile: 最初に開く（城がある県があればそこ）
            this.openPrefId = this.selectedPrefId;
          },

          get selectedPref() {
            return this.prefectures.find(p => p.id === this.selectedPrefId) || null;
          },

          get currentPlaces() {
            return this.placesByPref?.[this.selectedPrefId] || [];
          },

          selectPref(id) {
            this.selectedPrefId = id;
          },

          toggleAccordion(id) {
            this.openPrefId = (this.openPrefId === id) ? null : id;
          },

          prefectureShowUrl(slug) {
            // ルート名 public.prefectures.show を使っている前提で、URLは /prefectures/{slug}
            return `/prefectures/${slug}`;
          },
        };
      }
    </script>
  </div>
</x-public-layout>
