<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PlaceStoreRequest;
use App\Http\Requests\Admin\PlaceUpdateRequest;
use App\Models\Category;
use App\Models\Place;
use App\Models\PlacePhoto;
use App\Models\Prefecture;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PlaceController extends Controller
{
    public function index(Request $request): View
    {
        $places = Place::query()
            ->with(['category', 'prefecture', 'thumbnailPhoto', 'tags'])
            ->keyword($request->string('q')->toString())
            ->orderByDesc('updated_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.places.index', compact('places'));
    }

    public function create(): View
    {
        [$categories, $prefectures, $tags] = $this->masterData();

        return view('admin.places.create', compact('categories', 'prefectures', 'tags'));
    }

    public function store(PlaceStoreRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {

            $data = $request->safe()->except([
                'tag_ids',
                'thumbnail',
                'thumbnail_caption_ja',
                'thumbnail_caption_en',
                'thumbnail_taken_at',
            ]);

            // ★追加：この6項目を取得して $data にマージ
            $extra = $request->validate([
                'opening_hours_ja' => ['nullable', 'string'],
                'opening_hours_en' => ['nullable', 'string'],
                'closed_days_ja' => ['nullable', 'string', 'max:255'],
                'closed_days_en' => ['nullable', 'string', 'max:255'],
                'admission_fee_ja' => ['nullable', 'string', 'max:255'],
                'admission_fee_en' => ['nullable', 'string', 'max:255'],
            ]);

            $data = array_merge($data, $extra);

            // ★checkbox対策：未チェックだと送信されないので boolean化して確実に反映
            $data['is_published'] = $request->boolean('is_published');
            $data['published_at'] = $data['is_published'] ? ($data['published_at'] ?? now()) : null;

            $place = Place::create($data);

            // tags
            $place->tags()->sync($request->input('tag_ids', []));

            // thumbnail
            if ($request->hasFile('thumbnail')) {
                $this->upsertThumbnailPhoto(
                    place: $place,
                    fileKey: 'thumbnail',
                    captionJa: $request->input('thumbnail_caption_ja'),
                    captionEn: $request->input('thumbnail_caption_en'),
                    takenAt: $request->input('thumbnail_taken_at'),
                );
            }
        });

        return redirect()->route('admin.places.index')->with('status', '登録しました');
    }

    public function edit(Place $place): View
    {
        $place->load(['thumbnailPhoto', 'tags', 'galleryPhotos']);
        [$categories, $prefectures, $tags] = $this->masterData();

        $selectedTagIds = $place->tags->pluck('id')->all();

        return view('admin.places.edit', compact('place', 'categories', 'prefectures', 'tags', 'selectedTagIds'));
    }

    public function update(PlaceUpdateRequest $request, Place $place): RedirectResponse
    {
        DB::transaction(function () use ($request, $place) {

            $data = $request->safe()->except([
                'tag_ids',
                'thumbnail',
                'thumbnail_caption_ja',
                'thumbnail_caption_en',
                'thumbnail_taken_at',
                'remove_thumbnail',
            ]);

            $extra = $request->validate([
                'opening_hours_ja' => ['nullable', 'string'],
                'opening_hours_en' => ['nullable', 'string'],
                'closed_days_ja' => ['nullable', 'string', 'max:255'],
                'closed_days_en' => ['nullable', 'string', 'max:255'],
                'admission_fee_ja' => ['nullable', 'string', 'max:255'],
                'admission_fee_en' => ['nullable', 'string', 'max:255'],
            ]);

            $data = array_merge($data, $extra);

            $data['is_published'] = $request->boolean('is_published');
            $data['published_at'] = $data['is_published'] ? ($place->published_at ?? now()) : null;

            $place->update($data);

            // tags
            $place->tags()->sync($request->input('tag_ids', []));

            // remove thumbnail
            if ($request->boolean('remove_thumbnail')) {
                $this->deleteThumbnailPhoto($place);
            }

            // thumbnail upload
            if ($request->hasFile('thumbnail')) {
                $this->upsertThumbnailPhoto(
                    place: $place,
                    fileKey: 'thumbnail',
                    captionJa: $request->input('thumbnail_caption_ja'),
                    captionEn: $request->input('thumbnail_caption_en'),
                    takenAt: $request->input('thumbnail_taken_at'),
                );
            }
        });

        return redirect()->route('admin.places.edit', $place)->with('status', '更新しました');
    }

    // 今回は削除なし運用なので destroy は使わない（Routeは残ってもOK）
    public function destroy(Place $place): RedirectResponse
    {
        return redirect()->route('admin.places.index');
    }

    private function masterData(): array
    {
        $categories = Category::query()->orderBy('sort_order')->get();
        $prefectures = Prefecture::query()->orderBy('id')->get();
        $tags = Tag::query()->orderBy('name_ja')->get();

        return [$categories, $prefectures, $tags];
    }

    private function upsertThumbnailPhoto(
        Place $place,
        string $fileKey,
        ?string $captionJa,
        ?string $captionEn,
        ?string $takenAt,
    ): void {
        // 既存サムネを外す（unique(place_id, is_thumbnail)対策）
        PlacePhoto::query()
            ->where('place_id', $place->id)
            ->where('is_thumbnail', true)
            ->update(['is_thumbnail' => false]);

        $path = $place->id.'/'.uniqid('thumb_').'.'.$place->getUploadedFileExtension($fileKey);

        // 実際の保存（publicディスク前提）
        $storedPath = Storage::disk('public')->putFileAs(
            'places/'.$place->id,
            request()->file($fileKey),
            basename($path)
        );

        // サムネ行を作る（最短なので1枚を代表に）
        PlacePhoto::create([
            'place_id' => $place->id,
            'path' => $storedPath,
            'caption_ja' => $captionJa,
            'caption_en' => $captionEn,
            'taken_at' => $takenAt,
            'sort_order' => 0,
            'is_thumbnail' => true,
        ]);
    }

    private function deleteThumbnailPhoto(Place $place): void
    {
        $thumb = $place->thumbnailPhoto()->first();
        if (!$thumb) {
            return;
        }

        Storage::disk('public')->delete($thumb->path);
        $thumb->delete();
    }
}
