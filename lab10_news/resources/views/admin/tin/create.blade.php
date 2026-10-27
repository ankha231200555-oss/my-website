@extends('admin.layouts.main')
@section('title', 'Thêm bài viết mới')
@section('content')
<div class="form-shell mx-auto"><h1 class="h3 mb-4">Thêm bài viết mới</h1>
<form method="POST" action="{{ route('admin.tin.store') }}" enctype="multipart/form-data" class="card border-0 shadow-sm p-4">

@include('admin.tin._form')
</form></div>
@endsection
