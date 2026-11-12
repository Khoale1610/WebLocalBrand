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
    Route::post('/voucher/apply', [CartController::class, 'applyVoucher'])->name('voucher.apply');
    Route::post('/voucher/remove', [CartController::class, 'removeVoucher'])->name('voucher.remove');
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


// 5. Khu vực Quản trị viên (Admin Panel)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index']);

    // Quản lý Danh mục (Category)
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->except(['create', 'show', 'edit']);

    // Quản lý Thuộc tính (Size & Color)
    Route::get('attributes', [\App\Http\Controllers\Admin\AttributeController::class, 'index'])->name('attributes.index');
    Route::post('attributes/sizes', [\App\Http\Controllers\Admin\AttributeController::class, 'storeSize'])->name('attributes.sizes.store');
    Route::put('attributes/sizes/{size}', [\App\Http\Controllers\Admin\AttributeController::class, 'updateSize'])->name('attributes.sizes.update');
    Route::delete('attributes/sizes/{size}', [\App\Http\Controllers\Admin\AttributeController::class, 'destroySize'])->name('attributes.sizes.destroy');

    Route::post('attributes/colors', [\App\Http\Controllers\Admin\AttributeController::class, 'storeColor'])->name('attributes.colors.store');
    Route::put('attributes/colors/{color}', [\App\Http\Controllers\Admin\AttributeController::class, 'updateColor'])->name('attributes.colors.update');
    Route::delete('attributes/colors/{color}', [\App\Http\Controllers\Admin\AttributeController::class, 'destroyColor'])->name('attributes.colors.destroy');

    // Quản lý Sản phẩm (Product & ProductVariant)
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::post('products/{product}/variants', [\App\Http\Controllers\Admin\ProductController::class, 'storeVariant'])->name('products.variants.store');
    Route::delete('variants/{variant}', [\App\Http\Controllers\Admin\ProductController::class, 'destroyVariant'])->name('variants.destroy');

    // Quản lý Đơn hàng (Orders)
    Route::get('orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::put('orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.status.update');
    Route::get('orders/{order}/invoice', [\App\Http\Controllers\Admin\OrderController::class, 'invoice'])->name('orders.invoice');

    // Quản lý Voucher (Mã giảm giá)
    Route::resource('vouchers', \App\Http\Controllers\Admin\VoucherController::class)->except(['create', 'show', 'edit']);
    Route::patch('vouchers/{voucher}/toggle', [\App\Http\Controllers\Admin\VoucherController::class, 'toggle'])->name('vouchers.toggle');

    // Quản lý Khách hàng & Thành viên (Users / CRM)
    Route::get('users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
    Route::patch('users/{user}/role', [\App\Http\Controllers\Admin\UserController::class, 'updateRole'])->name('users.role.update');
});
