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
                                <div class="p-2 flex items-center justify-between">
                                    <div class="text-xs text-gray-500">#{{ $photo->sort_order }}</div>

                                    <form method="POST" action="{{ route('admin.place_photos.destroy', $photo) }}"
                                        onsubmit="return confirm('この写真を削除しますか？')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-xs text-red-600 hover:underline">削除</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
