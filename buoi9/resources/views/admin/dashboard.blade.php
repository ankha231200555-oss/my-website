<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Khu vực quản trị</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 text-sm text-gray-600">
                Trang này nằm sau middleware <span class="font-mono">auth</span> và <span class="font-mono">admin</span>.
                Tài khoản không có <span class="font-mono">is_admin</span> nhận lỗi 403.
                Sửa và xóa bài vẫn theo Policy: chỉ tác giả được phép.
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-900">Người dùng</h3>
                <ul class="mt-3 divide-y text-sm">
                    @foreach ($users as $user)
                        <li class="flex items-center justify-between py-2">
                            <span>{{ $user->name }} · {{ $user->email }}</span>
                            <span class="rounded-full px-2 py-0.5 text-xs {{ $user->is_admin ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $user->is_admin ? 'Admin' : 'User' }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900">Bài viết</h3>
                    <a href="{{ route('admin.articles.index') }}" class="text-sm text-indigo-600">Mở CRUD /admin/articles</a>
                </div>
                <ul class="mt-3 divide-y text-sm">
                    @forelse ($articles as $article)
                        <li class="py-2">
                            <a href="{{ route('articles.show', $article) }}" class="text-gray-900 hover:text-indigo-600">{{ $article->title }}</a>
                            <span class="text-gray-500"> — {{ $article->user->name }}</span>
                        </li>
                    @empty
                        <li class="py-2 text-gray-500">Chưa có bài viết.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
