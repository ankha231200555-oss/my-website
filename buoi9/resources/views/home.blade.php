<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Buổi 9 — Validation, Middleware, Authentication & Authorization
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <p class="text-gray-700">
                    Dự án mẫu Laravel 12 cho module Article: Form Request, custom rule, upload ảnh,
                    Breeze, middleware <span class="font-mono text-sm">admin</span>, throttle và Policy theo tác giả.
                </p>
                <div class="mt-4 flex flex-wrap gap-3">
                    <a href="{{ route('articles.index') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                        Xem bài viết
                    </a>
                    @auth
                        <a href="{{ route('articles.create') }}" class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Viết bài
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Đăng nhập
                        </a>
                    @endauth
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900">Tài khoản mẫu</h3>
                <p class="mt-1 text-sm text-gray-500">Chạy <span class="font-mono">php artisan db:seed</span> nếu chưa có dữ liệu.</p>
                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500">
                                <th class="py-2 pr-4">Vai trò</th>
                                <th class="py-2 pr-4">Email</th>
                                <th class="py-2">Mật khẩu</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-800">
                            <tr class="border-t">
                                <td class="py-2 pr-4">Người dùng (tác giả)</td>
                                <td class="py-2 pr-4 font-mono">demo@huit.edu.vn</td>
                                <td class="py-2 font-mono">password123</td>
                            </tr>
                            <tr class="border-t">
                                <td class="py-2 pr-4">Quản trị (middleware admin)</td>
                                <td class="py-2 pr-4 font-mono">admin@huit.edu.vn</td>
                                <td class="py-2 font-mono">password123</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-900">Bài 01–02 · Validation</h3>
                    <p class="mt-2 text-sm text-gray-600">Tiêu đề bắt buộc, không trùng, không chứa từ cấm (test, spam, xxx). Nội dung tối thiểu 10 ký tự. Ảnh jpg/jpeg/png, tối đa 2MB. Lỗi hiện dưới từng ô và giữ lại dữ liệu đã nhập.</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-900">Bài 03 · Middleware</h3>
                    <p class="mt-2 text-sm text-gray-600">Alias <span class="font-mono">admin</span> trong bootstrap/app.php. Người không phải admin vào /admin nhận 403. Đăng nhập tối đa 5 lần/phút. API <span class="font-mono">/api/public-info</span> tối đa 60 lần/phút.</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-900">Bài 04 · Breeze</h3>
                    <p class="mt-2 text-sm text-gray-600">Đăng ký, đăng nhập, quên mật khẩu. Khách mở /articles/create sẽ bị chuyển về trang đăng nhập. Menu đổi theo trạng thái khách và người đã đăng nhập.</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-900">Bài 05 · Policy</h3>
                    <p class="mt-2 text-sm text-gray-600">Chỉ tác giả thấy nút Sửa/Xóa. Người khác gọi thẳng URL sửa hoặc xóa nhận 403. Quyền được kiểm ở route, controller và Blade.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
