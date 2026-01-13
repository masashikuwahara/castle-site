<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Place 編集</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('status'))
                <div class="p-3 bg-green-100 rounded">{{ session('status') }}</div>
            @endif

            <div class="bg-white shadow rounded p-6">
                <form method="POST" action="{{ route('admin.places.update', $place) }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')
                    @include('admin.places._form')
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.places.index') }}" class="px-4 py-2 border rounded">戻る</a>
                        <button class="px-4 py-2 bg-gray-900 text-white rounded">更新</button>
                    </div>
                </form>
                <div class="bg-white shadow rounded p-6">
                    <h3 class="font-semibold text-lg mb-3">ギャラリー写真</h3>

                    <form method="POST"
                        action="{{ route('admin.places.photos.store', $place) }}"
                        enctype="multipart/form-data"
                        class="space-y-3">
                        @csrf

                        <input type="file" name="photos[]" multiple class="block w-full">
                        @error('photos') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                        @error('photos.*') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror

                        <button class="px-4 py-2 bg-gray-900 text-white rounded">追加アップロード</button>
                    </form>

                    <div class="mt-5 grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach($place->galleryPhotos as $photo)
                        <div class="bg-white border rounded overflow-hidden">
                        <img src="{{ Storage::url($photo->path) }}" class="w-full aspect-square object-cover" alt="">

                        <div class="p-2 flex items-center justify-between gap-2">
                            <div class="text-xs text-gray-500">#{{ $photo->sort_order }}</div>

                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-2">
                                    <form method="POST" action="{{ route('admin.place_photos.make_thumbnail', $photo) }}">
                                        @csrf
                                        <button class="text-xs px-2 py-1 border rounded hover:bg-gray-50">サムネ</button>
                                    </form>
                                </div>

                                <form method="POST" action="{{ route('admin.place_photos.move_up', $photo) }}">
                                    @csrf
                                    <button class="text-xs px-2 py-1 border rounded hover:bg-gray-50" title="上へ">↑</button>
                                </form>

                                <form method="POST" action="{{ route('admin.place_photos.move_down', $photo) }}">
                                    @csrf
                                    <button class="text-xs px-2 py-1 border rounded hover:bg-gray-50" title="下へ">↓</button>
                                </form>

                                <form method="POST" action="{{ route('admin.place_photos.destroy', $photo) }}"
                                    onsubmit="return confirm('この写真を削除しますか？')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs text-red-600 hover:underline">削除</button>
                                </form>
                            </div>
                        </div>

                        {{-- ここからキャプション編集 --}}
                        <form method="POST" action="{{ route('admin.place_photos.update', $photo) }}" class="p-3 border-t space-y-2">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-xs text-gray-600 mb-1">caption_ja</label>
                                <input name="caption_ja"
                                    value="{{ old('caption_ja', $photo->caption_ja) }}"
                                    class="w-full rounded border-gray-300 text-sm">
                            </div>

                            <div>
                                <label class="block text-xs text-gray-600 mb-1">caption_en</label>
                                <input name="caption_en"
                                    value="{{ old('caption_en', $photo->caption_en) }}"
                                    class="w-full rounded border-gray-300 text-sm">
                            </div>

                            <div class="flex items-center justify-between gap-2">
                                <div class="flex-1">
                                    <label class="block text-xs text-gray-600 mb-1">taken_at</label>
                                    <input type="date" name="taken_at"
                                        value="{{ old('taken_at', optional($photo->taken_at)->format('Y-m-d')) }}"
                                        class="w-full rounded border-gray-300 text-sm">
                                        <div class="pt-1">
                                            <button class="w-full px-3 py-2 bg-gray-900 text-white rounded text-sm">
                                                保存
                                            </button>
                                        </div>
                                </div>
                            </div>
                        </form>
                    </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
