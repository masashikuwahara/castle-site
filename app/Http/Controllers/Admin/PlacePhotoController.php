<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\PlacePhoto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PlacePhotoController extends Controller
{
    public function store(Request $request, Place $place): RedirectResponse
    {
        $validated = $request->validate([
            'photos' => ['required', 'array', 'min:1'],
            'photos.*' => ['file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], // 5MB
        ]);

        DB::transaction(function () use ($place, $request) {
            // 既存の最大sort_orderの次から振る
            $start = (int) PlacePhoto::query()
                ->where('place_id', $place->id)
                ->where('is_thumbnail', false)
                ->max('sort_order');

            $sort = $start + 1;

            foreach ($request->file('photos') as $file) {
                $filename = uniqid('gallery_') . '.' . strtolower($file->getClientOriginalExtension());

                $storedPath = Storage::disk('public')->putFileAs(
                    'places/' . $place->id,
                    $file,
                    $filename
                );

                PlacePhoto::create([
                    'place_id' => $place->id,
                    'path' => $storedPath,
                    'caption_ja' => null,
                    'caption_en' => null,
                    'taken_at' => null,
                    'sort_order' => $sort++,
                    'is_thumbnail' => false,
                ]);
            }
        });

        return redirect()
            ->route('admin.places.edit', $place)
            ->with('status', 'ギャラリー写真を追加しました');
    }

    public function destroy(PlacePhoto $photo): RedirectResponse
    {
        // サムネはここから消さない（Place編集側で管理）
        if ($photo->is_thumbnail) {
            return back()->with('status', 'サムネはここから削除できません');
        }

        $placeId = $photo->place_id;

        DB::transaction(function () use ($photo) {
            Storage::disk('public')->delete($photo->path);
            $photo->delete();
        });

        return redirect()
            ->route('admin.places.edit', $placeId)
            ->with('status', '写真を削除しました');
    }

    public function moveUp(PlacePhoto $photo): \Illuminate\Http\RedirectResponse
    {
        if ($photo->is_thumbnail) {
            return back();
        }

        DB::transaction(function () use ($photo) {
            // 同じplaceのギャラリー写真で、ひとつ上（sort_orderが小さい最大）を探す
            $prev = PlacePhoto::query()
                ->where('place_id', $photo->place_id)
                ->where('is_thumbnail', false)
                ->where('sort_order', '<', $photo->sort_order)
                ->orderByDesc('sort_order')
                ->first();

            if (!$prev) {
                return;
            }

            $currentOrder = $photo->sort_order;
            $photo->update(['sort_order' => $prev->sort_order]);
            $prev->update(['sort_order' => $currentOrder]);
        });

        return redirect()
            ->route('admin.places.edit', $photo->place_id)
            ->with('status', '並び順を変更しました');
    }

    public function moveDown(PlacePhoto $photo): \Illuminate\Http\RedirectResponse
    {
        if ($photo->is_thumbnail) {
            return back();
        }

        DB::transaction(function () use ($photo) {
            // 同じplaceのギャラリー写真で、ひとつ下（sort_orderが大きい最小）を探す
            $next = PlacePhoto::query()
                ->where('place_id', $photo->place_id)
                ->where('is_thumbnail', false)
                ->where('sort_order', '>', $photo->sort_order)
                ->orderBy('sort_order')
                ->first();

            if (!$next) {
                return;
            }

            $currentOrder = $photo->sort_order;
            $photo->update(['sort_order' => $next->sort_order]);
            $next->update(['sort_order' => $currentOrder]);
        });

        return redirect()
            ->route('admin.places.edit', $photo->place_id)
            ->with('status', '並び順を変更しました');
    }

    public function update(\Illuminate\Http\Request $request, PlacePhoto $photo): \Illuminate\Http\RedirectResponse
    {
        if ($photo->is_thumbnail) {
            return back()->with('status', 'サムネはここでは編集しません');
        }

        $validated = $request->validate([
            'caption_ja' => ['nullable', 'string', 'max:200'],
            'caption_en' => ['nullable', 'string', 'max:240'],
            'taken_at' => ['nullable', 'date'],
        ]);

        $photo->update($validated);

        return redirect()
            ->route('admin.places.edit', $photo->place_id)
            ->with('status', 'キャプションを更新しました');
    }

    public function makeThumbnail(PlacePhoto $photo): \Illuminate\Http\RedirectResponse
    {
        // すでにサムネなら何もしない
        if ($photo->is_thumbnail) {
            return redirect()
                ->route('admin.places.edit', $photo->place_id)
                ->with('status', 'すでにサムネです');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($photo) {
            // 1) 同じplaceの既存サムネを解除
            PlacePhoto::query()
                ->where('place_id', $photo->place_id)
                ->where('is_thumbnail', true)
                ->update(['is_thumbnail' => false]);

            // 2) 対象をサムネにする（sort_orderはそのまま）
            $photo->update(['is_thumbnail' => true]);
        });

        return redirect()
            ->route('admin.places.edit', $photo->place_id)
            ->with('status', 'サムネを更新しました');
    }
}
