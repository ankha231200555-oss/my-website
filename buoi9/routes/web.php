<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/articles/create', [ArticleController::class, 'create'])
        ->middleware('can:create,App\Models\Article')
        ->name('articles.create');
    Route::post('/articles', [ArticleController::class, 'store'])
        ->middleware('can:create,App\Models\Article')
        ->name('articles.store');
});

Route::resource('articles', ArticleController::class)->only(['index', 'show']);

Route::middleware('auth')->group(function () {
    Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])
        ->middleware('can:update,article')
        ->name('articles.edit');
    Route::put('/articles/{article}', [ArticleController::class, 'update'])
        ->middleware('can:update,article')
        ->name('articles.update');
    Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])
        ->middleware('can:delete,article')
        ->name('articles.destroy');
});

Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('articles', ArticleController::class);
    });

require __DIR__.'/auth.php';
