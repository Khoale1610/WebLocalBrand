<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

// Tìm kiếm sản phẩm
Route::get('/search', [HomeController::class, 'search'])->name('products.search');
// Giỏ hàng
//Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
// Giỏ hàng (tạm thời)
Route::get('/cart', function () {
    return view('pages.cart'); // hoặc trả về trang giỏ hàng của bạn
})->name('cart.index');