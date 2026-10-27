<?php
use App\Http\Controllers\Admin\TinTucAdminController;
use Illuminate\Support\Facades\Route;
Route::redirect('/', '/admin/tin');
Route::redirect('/admin', '/admin/tin');
Route::prefix('admin')->name('admin.')->group(function () {
    Route::post('tin/{id}/restore', [TinTucAdminController::class, 'restore'])->whereNumber('id')->name('tin.restore');
    Route::delete('tin/{id}/force', [TinTucAdminController::class, 'forceDelete'])->whereNumber('id')->name('tin.force-delete');
    Route::resource('tin', TinTucAdminController::class)->except('show');
});
