<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
// Ghi chú: Thêm các Controller mới để xử lý tài khoản và sản phẩm yêu thích (Wishlist)
use App\Http\Controllers\AccountController;
use App\Http\Controllers\WishlistController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Trang chủ & Tìm kiếm
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('products.search');

// 2. Chi tiết sản phẩm
Route::get('/san-pham/{id}', [ProductController::class, 'show'])->name('products.show');

// Ghi chú: Route cho Trang Danh mục & Bộ lọc đa năng (Sử dụng {id} theo đúng cấu trúc database hiện tại)
Route::get('/danh-muc/{id}', [ProductController::class, 'category'])->name('category.show');

// 3. Quản lý Giỏ hàng (Cart)
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{key}', [CartController::class, 'remove'])->name('remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
});

// 4. Tài khoản Khách hàng (Đăng nhập, Đăng ký, Quản lý)
Route::middleware('guest')->group(function () {
    Route::get('/dang-nhap', [AccountController::class, 'showLoginForm'])->name('login');
    Route::post('/dang-nhap', [AccountController::class, 'login'])->name('login.post');
    Route::get('/dang-ky', [AccountController::class, 'showRegisterForm'])->name('register');
    Route::post('/dang-ky', [AccountController::class, 'register'])->name('register.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/dang-xuat', [AccountController::class, 'logout'])->name('logout');
    
    Route::prefix('tai-khoan')->name('account.')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('index');
        Route::get('/don-hang', [AccountController::class, 'orders'])->name('orders');
        Route::get('/dia-chi', [AccountController::class, 'addresses'])->name('addresses');
        
        // Wishlist (Sản phẩm yêu thích)
        Route::get('/yeu-thich', [WishlistController::class, 'index'])->name('wishlist.index');
        Route::post('/yeu-thich/add', [WishlistController::class, 'add'])->name('wishlist.add');
        Route::delete('/yeu-thich/remove/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    });
});
