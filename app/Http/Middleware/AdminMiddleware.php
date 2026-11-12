<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Kiểm tra đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập tài khoản Quản trị để tiếp tục.');
        }

        // 2. Kiểm tra quyền Admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Bạn không có quyền truy cập vào bảng Quản trị viên (Admin Panel).');
        }

        return $next($request);
    }
}
