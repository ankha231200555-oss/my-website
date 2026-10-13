<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tạo bài viết</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('articles.store') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Tiêu đề</label>
                        <input id="title" type="text" name="title" value="{{ old('title') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('title')
                            <div class="text-danger mt-1 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="body" class="block text-sm font-medium text-gray-700">Nội dung</label>
                        <textarea id="body" name="body" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('body') }}</textarea>
                        @error('body')
                            <div class="text-danger mt-1 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700">Thẻ (tuỳ chọn)</label>
                        <input id="tags" type="text" name="tags" value="{{ old('tags') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('tags')
                            <div class="text-danger mt-1 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700">Ảnh minh hoạ (tuỳ chọn)</label>
                        <input id="image" type="file" name="image" accept=".jpg,.jpeg,.png" class="mt-1 block w-full text-sm text-gray-700">
                        @error('image')
                            <div class="text-danger mt-1 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Lưu</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
