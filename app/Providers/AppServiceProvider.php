<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        // Tự động nạp dữ liệu mẫu (sản phẩm, danh mục, biến thể, voucher, tài khoản)
        // nếu database đã có bảng nhưng chưa có dữ liệu (ví dụ khi mới clone sang máy khác)
        if (!$this->app->runningInConsole()) {
            try {
                if (
                    Schema::hasTable('categories') &&
                    Schema::hasTable('products') &&
                    DB::table('categories')->count() === 0 &&
                    DB::table('products')->count() === 0
                ) {
                    Artisan::call('db:seed', ['--force' => true]);
                }
            } catch (\Throwable $e) {
                // Bỏ qua nếu chưa cấu hình kết nối DB hoặc chưa chạy migrate
            }
        }
    }
}
