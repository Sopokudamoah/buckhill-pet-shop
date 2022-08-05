<?php


use App\Http\Controllers\Api\V1\AdminController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->controller(AdminController::class)->group(function () {
    Route::post('login', 'login')->name('login');

    Route::middleware(['auth:sanctum', AdminMiddleware::class])->group(function () {
        Route::get('logout', 'logout')->name('logout');
        Route::post('create', 'create')->name('create');
        Route::get('user-listing', 'userListing')->name('user-listing');
        Route::put('user-edit/{user}', 'userEdit')->name('user-edit');
        Route::delete('user-delete/{user}', 'userDelete')->name('user-delete');
    });
});
