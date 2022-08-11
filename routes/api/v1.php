<?php


use App\Http\Controllers\Api\V1\AdminController;
use App\Http\Controllers\Api\V1\BrandController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\FileController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\OrderStatusController;
use App\Http\Controllers\Api\V1\PaymentsController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\MainPageController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

//Admin endpoint routes
Route::prefix('admin')->name('admin.')->controller(AdminController::class)->group(function () {
    Route::post('login', 'login')->name('login');

    Route::middleware(['auth:api', AdminMiddleware::class])->group(function () {
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

    Route::middleware(['auth:api'])->group(function () {
        Route::get('/', 'index')->name('index');
        Route::delete('/', 'delete')->name('delete');
        Route::get('logout', 'logout')->name('logout');
        Route::put('edit', 'edit')->name('edit');
        Route::get('orders', 'orders')->name('orders');
    });
});

//Products endpoint
Route::middleware(['auth:api'])->name('product.')->controller(ProductController::class)->group(function () {
    Route::prefix('product')->group(function () {
        Route::post('create', 'create')->name('create');
        Route::put('{product}', 'update')->name('update');
        Route::delete('{product}', 'delete')->name('delete');
        Route::get('{product}', 'show')->name('show');
    });

    Route::get('products', 'index')->name('index');
});

// Brands endpoint
Route::middleware(['auth:api'])->name('brand.')->controller(BrandController::class)->group(function () {
    Route::prefix('brand')->group(function () {
        Route::post('create', 'create')->name('create');
        Route::put('{brand}', 'update')->name('update');
        Route::delete('{brand}', 'delete')->name('delete');
        Route::get('{brand}', 'show')->name('show');
    });

    Route::get('brands', 'index')->name('index');
});


// Categories endpoint
Route::middleware(['auth:api'])->name('category.')->controller(CategoryController::class)->group(function () {
    Route::prefix('category')->group(function () {
        Route::post('create', 'create')->name('create');
        Route::put('{category}', 'update')->name('update');
        Route::delete('{category}', 'delete')->name('delete');
        Route::get('{category}', 'show')->name('show');
    });

    Route::get('categories', 'index')->name('index');
});


// Files endpoint
Route::middleware(['auth:api'])->name('file.')->controller(FileController::class)->group(function () {
    Route::prefix('file')->group(function () {
        Route::post('upload', 'upload')->name('upload');
        Route::get('{file}', 'show')->name('show');
    });
});


// Orders endpoint
Route::middleware(['auth:api'])->name('order.')->controller(OrderController::class)->group(function () {
    Route::prefix('order')->group(function () {
        Route::post('create', 'create')->name('create');
        Route::put('{order}', 'update')->name('update');
        Route::delete('{order}', 'delete')->name('delete');
        Route::get('{order}', 'show')->name('show');
    });

    Route::get('orders', 'index')->name('index');
});


// Payments endpoint
Route::middleware(['auth:api'])->name('payments.')->controller(PaymentsController::class)->group(function () {
    Route::prefix('payments')->group(function () {
        Route::post('create', 'create')->name('create');
        Route::put('{payment}', 'update')->name('update');
        Route::delete('{payment}', 'delete')->name('delete');
        Route::get('{payment}', 'show')->name('show');
    });

    Route::get('payments', 'index')->name('index');
});


// Orders statuses endpoint
Route::middleware(['auth:api'])->name('order-status.')->controller(OrderStatusController::class)->group(function () {
    Route::prefix('order-status')->group(function () {
        Route::post('create', 'create')->name('create');
        Route::put('{order_status}', 'update')->name('update');
        Route::delete('{order_status}', 'delete')->name('delete');
        Route::get('{order_status}', 'show')->name('show');
    });

    Route::get('order-statuses', 'index')->name('index');
});


//Main page endpoint
Route::name('main.')->prefix('main')->controller(MainPageController::class)->group(function () {
    Route::get('blog', 'blog')->name('blog');
    Route::get('blog/{post}', 'showBlog')->name('show-blog');
    Route::get('promotions', 'promotions')->name('promotions');
});
