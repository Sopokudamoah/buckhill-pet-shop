<?php


use App\Http\Controllers\Api\V1\AdminController;
use App\Http\Controllers\Api\V1\BrandController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\FileController;
use App\Http\Controllers\Api\V1\ProductController;
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
    Route::post('create', 'create')->name('create');
    Route::post('forgot-password', 'forgotPassword')->name('forgot-password');
    Route::post('reset-password-token', 'resetPasswordToken')->name('reset-password-token');

    Route::get('password-reset/{token}', 'passwordReset')->name('password-reset');

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/', 'index')->name('index');
        Route::delete('/', 'delete')->name('delete');
        Route::get('logout', 'logout')->name('logout');
        Route::put('edit', 'edit')->name('edit');
        Route::get('orders', 'orders')->name('orders');
    });
});

//Products endpoint
Route::middleware(['auth:sanctum'])->name('product.')->controller(ProductController::class)->group(function () {
    Route::prefix('product')->group(function () {
        Route::post('create', 'create')->name('create');
        Route::put('{product}', 'update')->name('update');
        Route::delete('{product}', 'delete')->name('delete');
        Route::get('{product}', 'show')->name('show');
    });

    Route::get('products', 'index')->name('index');
});

// Brands endpoint
Route::middleware(['auth:sanctum'])->name('brand.')->controller(BrandController::class)->group(function () {
    Route::prefix('brand')->group(function () {
        Route::post('create', 'create')->name('create');
        Route::put('{brand}', 'update')->name('update');
        Route::delete('{brand}', 'delete')->name('delete');
        Route::get('{brand}', 'show')->name('show');
    });

    Route::get('brands', 'index')->name('index');
});


// Categories endpoint
Route::middleware(['auth:sanctum'])->name('category.')->controller(CategoryController::class)->group(function () {
    Route::prefix('category')->group(function () {
        Route::post('create', 'create')->name('create');
        Route::put('{category}', 'update')->name('update');
        Route::delete('{category}', 'delete')->name('delete');
        Route::get('{category}', 'show')->name('show');
    });

    Route::get('categories', 'index')->name('index');
});


// Categories endpoint
Route::middleware(['auth:sanctum'])->name('file.')->controller(FileController::class)->group(function () {
    Route::prefix('file')->group(function () {
        Route::post('upload', 'upload')->name('upload');
        Route::get('{file}', 'show')->name('show');
    });
});
