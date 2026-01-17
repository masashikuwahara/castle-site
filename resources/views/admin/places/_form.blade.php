@php
    $isEdit = isset($place);
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm mb-1">Type</label>
        <select name="type" class="w-full rounded border-gray-300">
            @foreach (['castle' => '城', 'cultural_property' => '文化財'] as $k => $v)
                <option value="{{ $k }}" @selected(old('type', $place->type ?? 'castle') === $k)>{{ $v }}</option>
            @endforeach
        </select>
        @error('type') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block text-sm mb-1">公開</label>
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="is_published" value="1"
                   @checked(old('is_published', $place->is_published ?? false))>
            <span>公開する</span>
        </label>
        @error('is_published') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block text-sm mb-1">カテゴリ</label>
        <select name="category_id" class="w-full rounded border-gray-300">
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" @selected((int)old('category_id', $place->category_id ?? 0) === $cat->id)>
                    {{ $cat->name_ja }}
                </option>
            @endforeach
        </select>
        @error('category_id') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block text-sm mb-1">都道府県</label>
        <select name="prefecture_id" class="w-full rounded border-gray-300">
            @foreach($prefectures as $pref)
                <option value="{{ $pref->id }}" @selected((int)old('prefecture_id', $place->prefecture_id ?? 0) === $pref->id)>
                    {{ $pref->name_ja }}
                </option>
            @endforeach
        </select>
        @error('prefecture_id') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block text-sm mb-1">slug（URL）</label>
        <input name="slug" value="{{ old('slug', $place->slug ?? '') }}" class="w-full rounded border-gray-300" placeholder="himeji-castle">
        @error('slug') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block text-sm mb-1">かな（任意）</label>
        <input name="kana" value="{{ old('kana', $place->kana ?? '') }}" class="w-full rounded border-gray-300" placeholder="ひめじじょう">
        @error('kana') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block text-sm mb-1">名称（日本語）</label>
        <input name="name_ja" value="{{ old('name_ja', $place->name_ja ?? '') }}" class="w-full rounded border-gray-300">
        @error('name_ja') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block text-sm mb-1">Name（English）</label>
        <input name="name_en" value="{{ old('name_en', $place->name_en ?? '') }}" class="w-full rounded border-gray-300">
        @error('name_en') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">開城時間（日本語）</label>
        <textarea name="opening_hours_ja" class="mt-1 w-full rounded border-gray-300" rows="2">{{ old('opening_hours_ja', $place->opening_hours_ja ?? '') }}</textarea>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Opening hours (EN)</label>
        <textarea name="opening_hours_en" class="mt-1 w-full rounded border-gray-300" rows="2">{{ old('opening_hours_en', $place->opening_hours_en ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">休城日（日本語）</label>
        <input name="closed_days_ja" class="mt-1 w-full rounded border-gray-300"
               value="{{ old('closed_days_ja', $place->closed_days_ja ?? '') }}">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Closed days (EN)</label>
        <input name="closed_days_en" class="mt-1 w-full rounded border-gray-300"
               value="{{ old('closed_days_en', $place->closed_days_en ?? '') }}">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">入城料金（日本語）</label>
        <input name="admission_fee_ja" class="mt-1 w-full rounded border-gray-300"
               value="{{ old('admission_fee_ja', $place->admission_fee_ja ?? '') }}">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Admission fee (EN)</label>
        <input name="admission_fee_en" class="mt-1 w-full rounded border-gray-300"
               value="{{ old('admission_fee_en', $place->admission_fee_en ?? '') }}">
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm mb-1">短い説明（一覧・OG用）</label>
        <input name="short_desc_ja" value="{{ old('short_desc_ja', $place->short_desc_ja ?? '') }}" class="w-full rounded border-gray-300" placeholder="姫路城は白鷺城とも呼ばれる…">
        @error('short_desc_ja') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm mb-1">短い説明（EN）</label>
        <input name="short_desc_en"
            value="{{ old('short_desc_en', $place->short_desc_en ?? '') }}"
            class="w-full rounded border-gray-300"
            placeholder="Short description for list/OGP">
        @error('short_desc_en') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm mb-1">本文（日本語）</label>
        <textarea name="description_ja" rows="6" class="w-full rounded border-gray-300">{{ old('description_ja', $place->description_ja ?? '') }}</textarea>
        @error('description_ja') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm mb-1">本文（EN）</label>
        <textarea name="description_en" rows="6"
                class="w-full rounded border-gray-300">{{ old('description_en', $place->description_en ?? '') }}</textarea>
        @error('description_en') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
    </div>

    {{-- 住所・緯度経度 --}}
    <div class="md:col-span-2 border-t pt-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm mb-1">住所（日本語）</label>
                <input name="address_ja"
                    value="{{ old('address_ja', $place->address_ja ?? '') }}"
                    class="w-full rounded border-gray-300"
                    placeholder="兵庫県姫路市本町68">
                @error('address_ja') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-sm mb-1">Address（EN）</label>
                <input name="address_en"
                    value="{{ old('address_en', $place->address_en ?? '') }}"
                    class="w-full rounded border-gray-300"
                    placeholder="68 Honmachi, Himeji, Hyogo">
                @error('address_en') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-sm mb-1">緯度（lat）</label>
                <input name="lat" inputmode="decimal"
                    value="{{ old('lat', $place->lat ?? '') }}"
                    class="w-full rounded border-gray-300"
                    placeholder="34.8394324">
                @error('lat') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-sm mb-1">経度（lng）</label>
                <input name="lng" inputmode="decimal"
                    value="{{ old('lng', $place->lng ?? '') }}"
                    class="w-full rounded border-gray-300"
                    placeholder="134.6938940">
                @error('lng') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>

    {{-- 基本情報（城） --}}
    <div class="md:col-span-2 border-t pt-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm mb-1">築城年</label>
                <input name="built_year"
                    value="{{ old('built_year', $place->built_year ?? '') }}"
                    class="w-full rounded border-gray-300"
                    placeholder="1609年頃 / 〇〇年（諸説）">
                @error('built_year') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-sm mb-1">廃城年</label>
                <input name="abolished_year"
                    value="{{ old('abolished_year', $place->abolished_year ?? '') }}"
                    class="w-full rounded border-gray-300"
                    placeholder="1873年（明治6年）">
                @error('abolished_year') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-sm mb-1">築城主（日本語）</label>
                <input name="builder_ja"
                    value="{{ old('builder_ja', $place->builder_ja ?? '') }}"
                    class="w-full rounded border-gray-300">
                @error('builder_ja') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-sm mb-1">Builder（EN）</label>
                <input name="builder_en"
                    value="{{ old('builder_en', $place->builder_en ?? '') }}"
                    class="w-full rounded border-gray-300">
                @error('builder_en') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm mb-1">主な城主（日本語）</label>
                <textarea name="main_lords_ja" rows="3"
                        class="w-full rounded border-gray-300">{{ old('main_lords_ja', $place->main_lords_ja ?? '') }}</textarea>
                @error('main_lords_ja') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm mb-1">Main lords（EN）</label>
                <textarea name="main_lords_en" rows="3"
                        class="w-full rounded border-gray-300">{{ old('main_lords_en', $place->main_lords_en ?? '') }}</textarea>
                @error('main_lords_en') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-sm mb-1">主な改修者（日本語）</label>
                <input name="renovator_ja"
                    value="{{ old('renovator_ja', $place->renovator_ja ?? '') }}"
                    class="w-full rounded border-gray-300">
                @error('renovator_ja') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-sm mb-1">Renovator（EN）</label>
                <input name="renovator_en"
                    value="{{ old('renovator_en', $place->renovator_en ?? '') }}"
                    class="w-full rounded border-gray-300">
                @error('renovator_en') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-sm mb-1">城郭構造（日本語）</label>
                <input name="castle_style_ja"
                    value="{{ old('castle_style_ja', $place->castle_style_ja ?? '') }}"
                    class="w-full rounded border-gray-300"
                    placeholder="平城 / 山城 / 平山城 など">
                @error('castle_style_ja') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-sm mb-1">Castle style（EN）</label>
                <input name="castle_style_en"
                    value="{{ old('castle_style_en', $place->castle_style_en ?? '') }}"
                    class="w-full rounded border-gray-300"
                    placeholder="Flatland / Mountain / Hilltop...">
                @error('castle_style_en') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-sm mb-1">天守構造（日本語）</label>
                <input name="tenshu_style_ja"
                    value="{{ old('tenshu_style_ja', $place->tenshu_style_ja ?? '') }}"
                    class="w-full rounded border-gray-300"
                    placeholder="現存 / 非現存 / 復元 / 連立式...">
                @error('tenshu_style_ja') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-sm mb-1">Tenshu style（EN）</label>
                <input name="tenshu_style_en"
                    value="{{ old('tenshu_style_en', $place->tenshu_style_en ?? '') }}"
                    class="w-full rounded border-gray-300">
                @error('tenshu_style_en') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-sm mb-1">指定文化財（日本語）</label>
                <input name="heritage_designation_ja"
                    value="{{ old('heritage_designation_ja', $place->heritage_designation_ja ?? '') }}"
                    class="w-full rounded border-gray-300"
                    placeholder="国宝 / 重要文化財 / 史跡 / 特別史跡...">
                @error('heritage_designation_ja') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-sm mb-1">Heritage designation（EN）</label>
                <input name="heritage_designation_en"
                    value="{{ old('heritage_designation_en', $place->heritage_designation_en ?? '') }}"
                    class="w-full rounded border-gray-300">
                @error('heritage_designation_en') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm mb-1">遺構（日本語）</label>
                <textarea name="remains_ja" rows="3"
                        class="w-full rounded border-gray-300">{{ old('remains_ja', $place->remains_ja ?? '') }}</textarea>
                @error('remains_ja') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm mb-1">Remains（EN）</label>
                <textarea name="remains_en" rows="3"
                        class="w-full rounded border-gray-300">{{ old('remains_en', $place->remains_en ?? '') }}</textarea>
                @error('remains_en') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>

    {{-- おすすめ度 --}}
    <div>
        <label class="block text-sm mb-1">おすすめ度（1〜5）</label>
        <select name="rating" class="w-full rounded border-gray-300">
            <option value="">未設定</option>
            @for($i=1;$i<=5;$i++)
                <option value="{{ $i }}" @selected((string)old('rating', $place->rating ?? '') === (string)$i)>
                    {{ $i }}
                </option>
            @endfor
        </select>
        @error('rating') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm mb-1">Tags</label>
        <select name="tag_ids[]" multiple class="w-full rounded border-gray-300 h-40">
            @php
                $selected = old('tag_ids', $selectedTagIds ?? []);
            @endphp
            @foreach($tags as $tag)
                <option value="{{ $tag->id }}" @selected(in_array($tag->id, $selected, true))>
                    #{{ $tag->name_ja }}
                </option>
            @endforeach
        </select>
        @error('tag_ids') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
    </div>

    <div class="md:col-span-2 border-t pt-4">
        <label class="block text-sm mb-2">サムネ画像（1枚）</label>

        @if($isEdit && $place->thumbnailPhoto)
            <div class="flex items-center gap-4 mb-3">
                <img src="{{ Storage::url($place->thumbnailPhoto->path) }}" class="w-28 h-28 object-cover rounded">
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="remove_thumbnail" value="1">
                    <span>サムネを削除する</span>
                </label>
            </div>
        @endif

        <input type="file" name="thumbnail" class="block w-full">
        @error('thumbnail') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror

        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
            <div>
                <label class="block text-sm mb-1">caption_ja</label>
                <input name="thumbnail_caption_ja" value="{{ old('thumbnail_caption_ja') }}" class="w-full rounded border-gray-300">
            </div>
            <div>
                <label class="block text-sm mb-1">caption_en</label>
                <input name="thumbnail_caption_en" value="{{ old('thumbnail_caption_en') }}" class="w-full rounded border-gray-300">
            </div>
            <div>
                <label class="block text-sm mb-1">taken_at</label>
                <input type="date" name="thumbnail_taken_at" value="{{ old('thumbnail_taken_at') }}" class="w-full rounded border-gray-300">
            </div>
        </div>
    </div>
</div>
