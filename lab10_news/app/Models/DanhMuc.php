<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class DanhMuc extends Model {
    protected $table = 'danh_mucs';
    protected $fillable = ['ten'];
    public function tinTucs(): HasMany { return $this->hasMany(TinTuc::class, 'danhmuc_id'); }
}
