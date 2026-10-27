<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\TinTucRequest;
use App\Models\DanhMuc;
use App\Models\TinTuc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;
class TinTucAdminController extends Controller {
    public function index(Request $request) {
        $kw = trim((string) $request->query('kw', ''));
        $trash = $request->boolean('trash');
        $query = TinTuc::with('danhMuc');
        if ($kw !== '') { $query->where('tieude', 'like', "%{$kw}%"); }
        if ($trash) { $query->onlyTrashed(); }
        $rows = $query->orderByDesc('id')->paginate(10)->withQueryString();
        return view('admin.tin.index', compact('rows', 'kw', 'trash'));
    }
    public function create() {
        return view('admin.tin.create', ['dm' => DanhMuc::orderBy('ten')->get(), 'tin' => new TinTuc]);
    }
    public function store(TinTucRequest $request) {
        $data = $request->safe()->except('hinhanh_up');
        $data['ngaydang'] = ($data['ngaydang'] ?? null) ?: now()->toDateString();
        $path = $request->hasFile('hinhanh_up') ? $request->file('hinhanh_up')->store('news', 'public') : null;
        if ($path) { $data['hinhanh_path'] = $path; }
        try { DB::transaction(fn () => TinTuc::create($data)); }
        catch (Throwable $e) { if ($path) Storage::disk('public')->delete($path); throw $e; }
        return to_route('admin.tin.index')->with('ok', 'Đã thêm bài viết mới thành công.');
    }
    public function edit(TinTuc $tin) {
        return view('admin.tin.edit', ['tin' => $tin, 'dm' => DanhMuc::orderBy('ten')->get()]);
    }
    public function update(TinTucRequest $request, TinTuc $tin) {
        $data = $request->safe()->except('hinhanh_up');
        $data['ngaydang'] = $data['ngaydang'] ?? $tin->ngaydang?->toDateString() ?? now()->toDateString();
        $oldPath = $tin->hinhanh_path;
        $newPath = $request->hasFile('hinhanh_up') ? $request->file('hinhanh_up')->store('news', 'public') : null;
        if ($newPath) { $data['hinhanh_path'] = $newPath; }
        try { DB::transaction(fn () => $tin->update($data)); }
        catch (Throwable $e) { if ($newPath) Storage::disk('public')->delete($newPath); throw $e; }
        // Only remove the previous image once the database update has succeeded.
        if ($newPath && $oldPath) Storage::disk('public')->delete($oldPath);
        return to_route('admin.tin.index')->with('ok', 'Đã cập nhật bài viết.');
    }
    public function destroy(TinTuc $tin) {
        $tin->delete();
        return to_route('admin.tin.index')->with('ok', 'Đã đưa bài viết vào thùng rác; ảnh được giữ lại.');
    }
    public function restore(int $id) {
        TinTuc::onlyTrashed()->findOrFail($id)->restore();
        return to_route('admin.tin.index', ['trash' => 1])->with('ok', 'Đã khôi phục bài viết.');
    }
    public function forceDelete(int $id) {
        $tin = TinTuc::onlyTrashed()->findOrFail($id);
        $path = $tin->hinhanh_path;
        DB::transaction(fn () => $tin->forceDelete());
        if ($path) Storage::disk('public')->delete($path);
        return to_route('admin.tin.index', ['trash' => 1])->with('ok', 'Đã xóa vĩnh viễn bài viết và ảnh.');
    }
}
