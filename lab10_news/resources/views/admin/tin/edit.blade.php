@extends('admin.layouts.main')
@section('title', 'Cập nhật bài viết')
@section('content')
<div class="form-shell mx-auto"><h1 class="h3 mb-4">Cập nhật bài viết</h1>
<form method="POST" action="{{ route('admin.tin.update', $tin) }}" enctype="multipart/form-data" class="card border-0 shadow-sm p-4">
@method('PUT')
@include('admin.tin._form')
</form></div>
@endsection
