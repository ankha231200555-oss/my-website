<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') · Buổi 11</title>
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}">
    <style>body{background:#f4f6fa} .page-shell{max-width:1200px} .thumbnail{width:72px;height:54px;object-fit:cover} .form-shell{max-width:900px} textarea{resize:vertical}</style>
</head>
<body>
<nav class="navbar navbar-dark bg-dark mb-4"><div class="container page-shell"><a class="navbar-brand" href="{{ route('admin.tin.index') }}">Quản trị tin tức</a><span class="navbar-text">Laravel · Buổi 11</span></div></nav>
<main class="container page-shell pb-5">
@if(session('ok'))<div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('ok') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button></div>@endif
@yield('content')
</main>
<script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
</body></html>
