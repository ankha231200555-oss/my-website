<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $demo = User::updateOrCreate(
            ['email' => 'demo@huit.edu.vn'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                // 'is_admin' => true, // nếu có cột is_admin
            ]
        );

        $admin = User::updateOrCreate(
            ['email' => 'admin@huit.edu.vn'],
            [
                'name' => 'Quản trị viên',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'is_admin' => true,
            ]
        );

        Article::updateOrCreate(
            ['title' => 'Làm quen Laravel Validation'],
            [
                'user_id' => $demo->id,
                'body' => 'Form Request giữ validation gọn, thông điệp lỗi tiếng Việt và dữ liệu cũ bằng old().',
                'tags' => 'validation, form-request',
            ]
        );

        Article::updateOrCreate(
            ['title' => 'Middleware và phân quyền tác giả'],
            [
                'user_id' => $admin->id,
                'body' => 'Chỉ tác giả được sửa hoặc xóa bài viết. Khách vẫn xem được danh sách và chi tiết.',
                'tags' => 'policy, middleware',
            ]
        );
    }
}
