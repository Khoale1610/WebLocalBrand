<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ChatbotController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Trang chủ & Tìm kiếm
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('products.search');
Route::get('/search/suggest', [HomeController::class, 'searchSuggest'])->name('products.search.suggest');

// 2. Chi tiết sản phẩm
Route::get('/san-pham/{id}', [ProductController::class, 'show'])->name('products.show');

// Route cho Trang Danh mục & Bộ lọc đa năng Sử dụng {id} 
Route::get('/danh-muc/{id}', [ProductController::class, 'category'])->name('category.show');

// 3. Quản lý Giỏ hàng (Cart)
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{key}', [CartController::class, 'remove'])->name('remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
});

// 3.5. Quy trình Thanh toán (Checkout & Gateways)
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/process', [CheckoutController::class, 'process'])->name('process');
    Route::get('/vietqr/{order_code}', [CheckoutController::class, 'vietqr'])->name('vietqr');
    Route::post('/vietqr/{order_code}/confirm', [CheckoutController::class, 'vietqrConfirm'])->name('vietqr.confirm');
    Route::get('/vnpay-sandbox/{order_code}', [CheckoutController::class, 'vnpaySandbox'])->name('vnpay.sandbox');
    Route::get('/vnpay-return', [CheckoutController::class, 'vnpayReturn'])->name('vnpay.return');
    Route::get('/success/{order_code}', [CheckoutController::class, 'success'])->name('success');
});

// 3.6. Trợ lý AI Chatbot
Route::post('/chatbot/message', [ChatbotController::class, 'reply'])->name('chatbot.reply');


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
