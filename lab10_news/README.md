# BUỔI 11 – CRUD Tin tức, Upload ảnh, SoftDeletes

Dự án Laravel 12 độc lập cho bài tập 03 trong PDF BUỔI 11. Vì chưa có project buổi 10, dự án cung cấp sẵn layout admin, bảng danh mục và ba danh mục/bài viết mẫu. Module bài tập nằm tại `/admin/tin`. Không cần npm hay Vite; Bootstrap 5.3.3 đã được lưu trong `public/vendor/bootstrap`.

## Chạy bằng Laragon trên Windows

1. Dùng Laragon với **PHP 8.2 trở lên** (khuyên dùng 8.3/8.4), Composer 2 và MySQL/MariaDB. Trong Laragon chọn **Start All**, rồi mở **Terminal** của Laragon.
2. Giải nén thư mục `lab10_news` vào `C:\laragon\www\lab10_news`.
3. Mở HeidiSQL/phpMyAdmin từ Laragon, tạo database **lab10_news**, charset `utf8mb4`, collation `utf8mb4_unicode_ci`. Hoặc chạy SQL: `CREATE DATABASE lab10_news CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;` (chỉ chạy nếu database chưa tồn tại).
4. Trong Terminal:

```bat
cd C:\laragon\www\lab10_news
composer install
copy .env.example .env
php artisan key:generate
```

Chỉ copy `.env.example` khi **chưa có** `.env`. Mở `.env`, đặt đúng cấu hình MySQL của máy bạn:

```dotenv
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lab10_news
DB_USERNAME=root
DB_PASSWORD=
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

`root` và mật khẩu trống là cấu hình thường gặp của Laragon; nếu máy đã đặt mật khẩu/port riêng, nhập cấu hình thực tế. Không gửi mật khẩu vào chat.

5. Tiếp tục:

```bat
php artisan optimize:clear
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

6. Mở **http://127.0.0.1:8000/admin/tin**. Giữ Terminal đang chạy `artisan serve`.

Có thể dùng `SETUP-LARAGON.bat` từ Terminal của Laragon để thực hiện bước cài đặt sau khi tạo database và chuẩn bị `.env`; tập lệnh không ghi đè `.env` đã có. Nếu thay đổi thông tin kết nối, sửa `.env` trước khi chạy. Không dùng `migrate:fresh` trên database có dữ liệu cần giữ.

### Dùng tên miền tự động của Laragon (tùy chọn)

Nếu dùng Apache Virtual Host, đặt DocumentRoot trỏ tới `C:/laragon/www/lab10_news/public`, bật rewrite và `AllowOverride All`; với Nginx đặt root tương tự và cấu hình fallback Laravel. Không trỏ web root vào toàn bộ thư mục dự án. Reload Laragon, đổi `APP_URL` sang tên miền của bạn và chạy `php artisan optimize:clear`. Cách `artisan serve` phía trên không cần sửa virtual host.

## Chức năng đã làm

- Danh sách bài viết, quan hệ danh mục, thumbnail, tìm theo tiêu đề, phân trang 10 dòng và giữ bộ lọc.
- Thêm/sửa: tiêu đề tối đa 200 ký tự, tóm tắt 300, nội dung bắt buộc, ngày hợp lệ, danh mục tồn tại.
- Ảnh JPG/JPEG/PNG/WEBP tối đa 2MB, xem trước ảnh, lưu trên public disk vào `storage/app/public/news`.
- Khi thêm không nhập ngày: lấy ngày hiện tại, múi giờ Việt Nam. Khi sửa không chọn ảnh: giữ ảnh cũ.
- Thay ảnh: lưu ảnh mới, cập nhật database thành công rồi xóa ảnh cũ; nếu database lỗi thì dọn ảnh mới.
- Xóa tạm: giữ ảnh để khôi phục; thùng rác chỉ hiển thị bản ghi đã xóa.
- Khôi phục/xóa hẳn chỉ áp dụng bài trong thùng rác; xóa hẳn dọn ảnh trên public disk.
- Form Request, CSRF, flash message, Bootstrap 5 cục bộ, ảnh mặc định có sẵn.

Đây là bài thực hành admin cục bộ, **chưa có đăng nhập/phân quyền**; không đưa trực tiếp lên Internet trước khi bổ sung xác thực. Bản độc lập này chưa xây dựng CRUD danh mục của BT2; ba danh mục được seed sẵn để thực hành BT3. Nếu có project buổi 10, cần đối chiếu schema/layout/routes trước khi tích hợp, không chép đè migration vào database cũ.

## Các tệp chính

- `database/migrations/2026_01_01_000001_create_danh_mucs_table.php`: bảng danh mục nền.
- `database/migrations/2026_01_01_000002_create_tin_tucs_table.php`: các cột tin tức, khóa ngoại, `deleted_at`.
- `app/Models/TinTuc.php`: SoftDeletes, fillable, cast ngày, quan hệ và accessor `thumb_url`.
- `app/Http/Requests/TinTucRequest.php`: validate và thông báo tiếng Việt.
- `app/Http/Controllers/Admin/TinTucAdminController.php`: CRUD, ảnh, khôi phục và xóa hẳn.
- `routes/web.php`: resource và route bổ sung.
- `resources/views/admin/tin/`: index/create/edit và form dùng chung.
- `tests/Feature/TinTucTest.php`: kiểm thử chức năng của bài tập.

## Kiểm thử

```bat
php artisan test
```

Bộ test dùng SQLite in-memory theo `phpunit.xml`, không xóa dữ liệu MySQL của bạn. Cần bật `pdo_sqlite`/`sqlite3`, `gd`, `mbstring`, `fileinfo`, `dom`, `xml`, `xmlwriter` trong PHP. Chạy ứng dụng MySQL cần `pdo_mysql`. Laravel cũng cần các extension tiêu chuẩn như curl, openssl, tokenizer, ctype; Composer sẽ kiểm tra yêu cầu nền tảng.

Thử thủ công: thêm có/không ảnh; sửa và thay ảnh; kiểm tra thư mục `storage/app/public/news`; xóa tạm và khôi phục; xóa hẳn; tạo hơn 10 bài để thử tìm kiếm/phân trang. Kiểm tra lỗi khi nhập tiêu đề rỗng, ảnh >2MB, GIF hoặc tệp không phải ảnh.

## Xử lý lỗi Laragon

- **Ảnh 404**: chạy `php artisan storage:link`. Nếu Windows báo thiếu quyền symlink, bật Developer Mode hoặc chạy Terminal bằng quyền quản trị rồi thử lại. Sau khi di chuyển dự án, nếu `public/storage` vẫn là symlink cũ, gỡ riêng liên kết đó và tạo lại; không xóa dữ liệu trong `storage/app/public`.
- **SQLSTATE 1049/1045**: tạo database đúng tên, kiểm tra username/password/port trong `.env`, bật MySQL, rồi chạy `php artisan optimize:clear`.
- **419**: tạo APP_KEY, giữ `@csrf`, cho phép cookie; dùng cùng một hostname khi mở form và gửi form.
- **Không upload được**: bật `fileinfo` và đặt `upload_max_filesize=3M`, `post_max_size=8M` (hoặc lớn hơn) trong `php.ini`; restart Laragon. Ứng dụng vẫn giới hạn ảnh ở 2MB.
- **could not find driver**: bật `pdo_mysql` để chạy MySQL; `pdo_sqlite` để chạy test.
- **Composer báo phiên bản PHP**: kiểm tra `php -v` trong chính Terminal của Laragon; chọn PHP 8.2+ rồi mở lại Terminal.

Bản ZIP không mang `.env`, khóa ứng dụng, database hay liên kết storage của máy phát triển. Cần tạo chúng theo hướng dẫn trên máy bạn. Kiểm thử Linux/SQLite không thay thế kiểm thử thực tế trên Windows/Laragon/MySQL.

## Kết quả kiểm tra của bản bàn giao

- Linux, PHP 8.4, Laravel 12; migration và seed chạy thành công trên SQLite.
- 14 kiểm thử tự động qua; bao gồm 13 kiểm thử chức năng tin tức và điều hướng trang chủ.
- HTTP thực tế: tạo bài, hiển thị form sửa, xóa tạm, xem thùng rác, xóa hẳn, Bootstrap/ảnh mặc định trả 200; gửi POST thiếu CSRF bị chặn 419.
- Đã biên dịch Blade thành công. Chưa chạy trực tiếp Windows/Laragon/MySQL hoặc kiểm tra thao tác tương tác bằng trình duyệt đồ họa.
