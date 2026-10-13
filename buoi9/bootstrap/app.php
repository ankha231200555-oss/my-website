<?php

use App\Http\Middleware\CheckAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 1) Đăng ký alias: dùng tên ngắn 'admin' trong route
        $middleware->alias([
            'admin' => CheckAdmin::class,
        ]);

        // 2) (Tuỳ chọn) Thao tác với nhóm 'web'/'api'
        // Ví dụ: thêm một middleware tuỳ biến vào group 'web'
        // $middleware->appendToGroup('web', \App\Http\Middleware\AddSecurityHeaders::class);

        // 3) (Tuỳ chọn) Cấu hình ngoại lệ CSRF
        // $middleware->validateCsrfTokens(except: ['webhook/*']);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
