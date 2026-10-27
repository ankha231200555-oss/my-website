@extends('admin.layouts.main')
@section('title', $trash ? 'Thùng rác' : 'Danh sách bài viết')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
<div><h1 class="h3 mb-1">{{ $trash ? 'Thùng rác' : 'Danh sách bài viết' }}</h1><p class="text-muted mb-0">{{ $rows->total() }} bài viết {{ $trash ? 'đã xóa tạm' : 'đang hoạt động' }}</p></div>
<div class="d-flex gap-2"><a class="btn btn-outline-secondary" href="{{ route('admin.tin.index', $trash ? [] : ['trash' => 1]) }}">{{ $trash ? 'Danh sách bài viết' : 'Thùng rác' }}</a><a class="btn btn-primary" href="{{ route('admin.tin.create') }}">+ Thêm bài viết</a></div>
</div>
<form method="GET" action="{{ route('admin.tin.index') }}" class="row g-2 mb-3">
@if($trash)<input type="hidden" name="trash" value="1">@endif
<div class="col-sm-7 col-md-5"><label for="kw" class="visually-hidden">Tìm theo tiêu đề</label><input id="kw" name="kw" class="form-control" placeholder="Tìm theo tiêu đề…" value="{{ $kw }}"></div>
<div class="col-auto"><button class="btn btn-secondary">Tìm kiếm</button><a class="btn btn-link" href="{{ route('admin.tin.index', $trash ? ['trash'=>1] : []) }}">Xóa bộ lọc</a></div>
</form>
<div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table table-hover align-middle mb-0">
<thead class="table-light"><tr><th>ID</th><th>Ảnh</th><th>Tiêu đề</th><th>Danh mục</th><th>Ngày đăng</th><th class="text-end">Thao tác</th></tr></thead><tbody>
@forelse($rows as $r)
<tr><td>{{ $r->id }}</td><td><img class="thumbnail rounded border" src="{{ $r->thumb_url }}" alt="Ảnh đại diện"></td><td><strong>{{ $r->tieude }}</strong><div class="text-muted small">{{ Str::limit($r->tomtat, 70) }}</div></td><td><span class="badge bg-info text-dark">{{ $r->danhMuc?->ten ?? 'Chưa phân loại' }}</span></td><td class="text-nowrap">{{ $r->ngaydang?->format('d/m/Y') }}</td><td class="text-end text-nowrap">
@if(!$trash)
<a class="btn btn-sm btn-outline-primary" href="{{ route('admin.tin.edit', $r) }}">Sửa</a>
<form class="d-inline" method="POST" action="{{ route('admin.tin.destroy', $r) }}" onsubmit="return confirm('Đưa bài viết vào thùng rác?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Xóa</button></form>
@else
<form class="d-inline" method="POST" action="{{ route('admin.tin.restore', $r->id) }}">@csrf<button class="btn btn-sm btn-outline-success">Khôi phục</button></form>
<form class="d-inline" method="POST" action="{{ route('admin.tin.force-delete', $r->id) }}" onsubmit="return confirm('Xóa vĩnh viễn bài viết và ảnh? Không thể hoàn tác.')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Xóa hẳn</button></form>
@endif
</td></tr>
@empty<tr><td colspan="6" class="text-center text-muted py-5">{{ $trash ? 'Thùng rác đang trống.' : 'Không tìm thấy bài viết.' }}</td></tr>@endforelse
</tbody></table></div></div><div class="mt-4">{{ $rows->links() }}</div>
@endsection
