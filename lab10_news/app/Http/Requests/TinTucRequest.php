<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class TinTucRequest extends FormRequest {
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'tieude' => ['required', 'string', 'max:200'],
            'tomtat' => ['nullable', 'string', 'max:300'],
            'noidung' => ['required', 'string'],
            'ngaydang' => ['nullable', 'date'],
            'danhmuc_id' => ['nullable', 'integer', 'exists:danh_mucs,id'],
            'hinhanh_up' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
    public function messages(): array {
        return [
            'tieude.required' => 'Tiêu đề bắt buộc nhập.',
            'tieude.max' => 'Tiêu đề tối đa :max ký tự.',
            'tomtat.max' => 'Tóm tắt tối đa :max ký tự.',
            'noidung.required' => 'Nội dung bài viết bắt buộc nhập.',
            'ngaydang.date' => 'Ngày đăng không hợp lệ.',
            'danhmuc_id.exists' => 'Danh mục không tồn tại.',
            'danhmuc_id.integer' => 'Danh mục không hợp lệ.',
            'hinhanh_up.uploaded' => 'Không tải được ảnh. Hãy kiểm tra giới hạn upload của PHP.',
            'hinhanh_up.image' => 'Tệp tải lên phải là hình ảnh.',
            'hinhanh_up.mimes' => 'Định dạng cho phép: jpg, jpeg, png, webp.',
            'hinhanh_up.max' => 'Kích thước ảnh tối đa 2MB.',
        ];
    }
}
