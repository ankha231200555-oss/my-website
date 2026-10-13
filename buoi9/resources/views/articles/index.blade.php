<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Bài viết</h2>
            @auth
                <a href="{{ route('articles.create') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-700">Viết bài</a>
            @endauth
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if ($articles->isEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-600">
                    Chưa có bài viết.
                </div>
            @else
                <div class="grid gap-4">
                    @foreach ($articles as $article)
                        <article class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                            <div class="sm:flex">
                                @if ($article->image_path)
                                    <img src="{{ asset('storage/'.$article->image_path) }}" alt="Ảnh minh hoạ" class="h-40 w-full object-cover sm:w-56">
                                @endif
                                <div class="p-5 flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        <a href="{{ route('articles.show', $article) }}" class="hover:text-indigo-600">{{ $article->title }}</a>
                                    </h3>
                                    <p class="mt-2 text-sm text-gray-600 line-clamp-2">{{ $article->body }}</p>
                                    <p class="mt-3 text-xs text-gray-500">
                                        Tác giả: {{ $article->user->name }}
                                        · {{ $article->created_at->format('d/m/Y H:i') }}
                                        @if ($article->tags)
                                            · Thẻ: {{ $article->tags }}
                                        @endif
                                    </p>
                                    <div class="mt-3 flex items-center gap-3 text-sm">
                                        <a href="{{ route('articles.show', $article) }}" class="text-indigo-600 hover:text-indigo-800">Xem</a>
                                        @can('update', $article)
                                            <a href="{{ route('articles.edit', $article) }}">Sửa</a>
                                        @endcan
                                        @can('delete', $article)
                                            <form action="{{ route('articles.destroy', $article) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
