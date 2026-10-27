<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
class TinTuc extends Model {
    use SoftDeletes;
    protected $table = 'tin_tucs';
    protected $fillable = ['tieude', 'tomtat', 'noidung', 'ngaydang', 'danhmuc_id', 'hinhanh_path'];
    protected $casts = ['ngaydang' => 'date'];
    public function danhMuc(): BelongsTo { return $this->belongsTo(DanhMuc::class, 'danhmuc_id'); }
    public function getThumbUrlAttribute(): string {
        return $this->hinhanh_path ? Storage::disk('public')->url($this->hinhanh_path) : asset('images/no-image.svg');
    }
}
