<?php


use App\Http\Controllers\Api\V1\AdminController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

//Admin endpoint routes
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

//User endpoint routes
Route::prefix('user')->name('user.')->controller(UserController::class)->group(function () {
    Route::post('login', 'login')->name('login');
    Route::post('forgot-password', 'forgotPassword')->name('forgot-password');
    Route::post('reset-password-token', 'resetPasswordToken')->name('reset-password-token');

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/', 'index')->name('index');
        Route::delete('/', 'delete')->name('delete');
        Route::get('logout', 'logout')->name('logout');
        Route::post('create', 'create')->name('create');
        Route::put('edit', 'edit')->name('edit');
        Route::get('orders', 'orders')->name('orders');
    });
});
