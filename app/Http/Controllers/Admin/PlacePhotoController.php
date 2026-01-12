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
}
