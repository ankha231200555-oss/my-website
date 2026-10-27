<?php
namespace Database\Seeders;
use App\Models\DanhMuc;
use App\Models\TinTuc;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder {
    public function run(): void {
        foreach (['Công nghệ', 'Giáo dục', 'Đời sống'] as $name) {
            $dm = DanhMuc::firstOrCreate(['ten' => $name]);
            TinTuc::firstOrCreate(['tieude' => 'Tin mẫu: '.$name], [
                'tomtat' => 'Bài viết mẫu để thực hành quản trị tin tức.',
                'noidung' => 'Bạn có thể sửa bài viết, tải ảnh, đưa vào thùng rác rồi khôi phục. Đây là dữ liệu minh họa của bài thực hành Buổi 11.',
                'ngaydang' => now()->toDateString(), 'danhmuc_id' => $dm->id,
            ]);
        }
    }
}
