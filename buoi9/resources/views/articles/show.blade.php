<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $article->title }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-semibold text-gray-900">{{ $article->title }}</h1>
                <p class="mt-4 whitespace-pre-wrap text-gray-800">{{ $article->body }}</p>

                @if ($article->image_path)
                    <img src="{{ asset('storage/'.$article->image_path) }}" alt="Ảnh minh hoạ" width="300" class="mt-4 rounded">
                @endif

                @if ($article->tags)
                    <p class="mt-4 text-sm text-gray-600">Thẻ: {{ $article->tags }}</p>
                @endif

                <p class="mt-4 text-sm text-gray-500">
                    Tác giả: {{ $article->user->name }} | Ngày tạo: {{ $article->created_at->format('d/m/Y H:i') }}
                </p>

                <div class="mt-5 flex items-center gap-4">
                    @can('update', $article)
                        <a href="{{ route('articles.edit', $article) }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-700">Sửa</a>
                    @endcan

                    @can('delete', $article)
                        <form action="{{ route('articles.destroy', $article) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center rounded-md bg-red-600 px-3 py-2 text-sm font-medium text-white hover:bg-red-700" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                        </form>
                    @endcan
                </div>

                @cannot('update', $article)
                    <p class="text-muted mt-4 text-sm text-gray-500">Bạn không phải tác giả của bài viết này.</p>
                @endcannot

                <a href="{{ route('articles.index') }}" class="mt-6 inline-block text-sm text-indigo-600 hover:text-indigo-800">← Danh sách bài viết</a>
            </div>
        </div>
    </div>
</x-app-layout>
