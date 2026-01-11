<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Place 新規作成</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">
                <form method="POST" action="{{ route('admin.places.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @include('admin.places._form')
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.places.index') }}" class="px-4 py-2 border rounded">戻る</a>
                        <button class="px-4 py-2 bg-gray-900 text-white rounded">保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
